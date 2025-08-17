<?php
// return_book.php
include 'db_connect.php';
include 'util_fines.php';

$success = $error = "";

// Handle return submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loan_id    = intval($_POST['loan_id'] ?? 0);
    $on_return  = $_POST['on_return'] ?? 'good'; // good | damaged | lost

    // Load the loan with member type and copy/book
    $stmt = $conn->prepare("
        SELECT l.Loan_ID, l.Book_Copy_ID, l.Member_ID, l.Loan_Date, l.Due_Date, l.Return_Date, l.Fine_Amount,
               bc.Book_ID, bc.Condition_Status, bc.Availability_Status,
               m.Membership_Type
        FROM Loan l
        JOIN Book_Copy bc ON bc.Copy_ID = l.Book_Copy_ID
        JOIN Member m ON m.Member_ID = l.Member_ID
        WHERE l.Loan_ID=? AND l.Return_Date IS NULL
        LIMIT 1
    ");
    $stmt->bind_param("i", $loan_id);
    $stmt->execute();
    $loan = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$loan) {
        $error = "Loan not found or already returned.";
    } else {
        // Compute lateness & base fine
        $policy = getFinePolicy($conn, $loan['Membership_Type'] ?? 'Student') ?: ['Fine_Per_Day' => 1.00, 'Max_Fine' => 100.00];
        $fine_per_day = (float)$policy['Fine_Per_Day'];
        $max_fine     = (float)$policy['Max_Fine'];

        // days late
        $late_q = $conn->query("SELECT GREATEST(DATEDIFF(CURDATE(), DATE('" . $conn->real_escape_string($loan['Due_Date']) . "')), 0) AS days_late");
        $days_late = (int)($late_q->fetch_assoc()['days_late'] ?? 0);

        $base_fine = min($days_late * $fine_per_day, $max_fine);
        $penalty = 0.0;
        $new_condition = $loan['Condition_Status'];
        $new_avail     = 'Available';

        if ($on_return === 'lost') {
            $penalty      = LOST_PENALTY_AMOUNT;
            $new_condition = 'Lost';
            $new_avail     = 'Lost';
        } elseif ($on_return === 'damaged') {
            $penalty      = DAMAGED_PENALTY_AMOUNT;
            $new_condition = 'Damaged';
            // You could also keep it 'Available' but flagged as damaged.
            $new_avail     = 'Available';
        }

        $total_fine = $base_fine + $penalty;

        // Update Loan: Return_Date + Fine_Amount
        $updLoan = $conn->prepare("UPDATE Loan SET Return_Date=CURDATE(), Fine_Amount=? WHERE Loan_ID=?");
        $updLoan->bind_param("di", $total_fine, $loan_id);
        $okLoan = $updLoan->execute();
        $updLoan->close();

        // Update copy condition & availability
        $updCopy = $conn->prepare("UPDATE Book_Copy SET Condition_Status=?, Availability_Status=? WHERE Copy_ID=?");
        $updCopy->bind_param("ssi", $new_condition, $new_avail, $loan['Book_Copy_ID']);
        $okCopy = $updCopy->execute();
        $updCopy->close();

        if ($okLoan && $okCopy) {
            // If book is available after return, check reservations
            if ($on_return === 'good' || $on_return === 'damaged') {
                // Find earliest pending reservation for this Book_ID
                $resv = $conn->prepare("
                    SELECT Reservation_ID, Member_ID 
                    FROM Reservation 
                    WHERE Book_ID=? AND Status='Pending'
                    ORDER BY Reservation_Date ASC, Reservation_ID ASC
                    LIMIT 1
                ");
                $resv->bind_param("i", $loan['Book_ID']);
                $resv->execute();
                $r = $resv->get_result()->fetch_assoc();
                $resv->close();

                if ($r) {
                    // Mark reservation fulfilled and hold this copy
                    $ful = $conn->prepare("UPDATE Reservation SET Status='Fulfilled' WHERE Reservation_ID=?");
                    $ful->bind_param("i", $r['Reservation_ID']);
                    $ful->execute();
                    $ful->close();

                    // Put copy on hold (Reserved)
                    $hold = $conn->prepare("UPDATE Book_Copy SET Availability_Status='Reserved' WHERE Copy_ID=?");
                    $hold->bind_param("i", $loan['Book_Copy_ID']);
                    $hold->execute();
                    $hold->close();
                }
            }

            $success = "Return processed. Total fine: " . number_format($total_fine, 2);
        } else {
            $error = "Failed to process return: " . $conn->error;
        }
    }
}

// Load open loans
$open = $conn->query("
    SELECT l.Loan_ID, l.Book_Copy_ID, l.Member_ID, l.Loan_Date, l.Due_Date,
           b.Title, bc.Copy_ID, m.Name
    FROM Loan l
    JOIN Book_Copy bc ON bc.Copy_ID = l.Book_Copy_ID
    JOIN Book b ON b.Book_ID = bc.Book_ID
    JOIN Member m ON m.Member_ID = l.Member_ID
    WHERE l.Return_Date IS NULL
    ORDER BY l.Due_Date ASC
");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Return Book</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <h2>Return Book</h2>

  <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
  <?php if ($error):   ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead class="table-dark">
          <tr>
            <th>Loan #</th>
            <th>Member</th>
            <th>Book / Copy</th>
            <th>Loan Date</th>
            <th>Due Date</th>
            <th>Return As</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php if ($open->num_rows > 0): ?>
          <?php while ($row = $open->fetch_assoc()): ?>
            <tr>
              <td><?= (int)$row['Loan_ID'] ?></td>
              <td><?= htmlspecialchars($row['Name']) ?></td>
              <td><?= htmlspecialchars($row['Title']) ?> — Copy #<?= (int)$row['Copy_ID'] ?></td>
              <td><?= htmlspecialchars($row['Loan_Date']) ?></td>
              <td><?= htmlspecialchars($row['Due_Date']) ?></td>
              <td>
                <form method="post" class="d-flex align-items-center gap-2">
                  <input type="hidden" name="loan_id" value="<?= (int)$row['Loan_ID'] ?>">
                  <select name="on_return" class="form-select form-select-sm" required>
                    <option value="good">Good (normal)</option>
                    <option value="damaged">Damaged</option>
                    <option value="lost">Lost</option>
                  </select>
              </td>
              <td>
                  <button class="btn btn-sm btn-primary">Process Return</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="7" class="text-center py-3">No open loans.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    <a class="btn btn-secondary" href="admin_dashboard.php">Back</a>
  </div>
</div>
</body>
</html>
