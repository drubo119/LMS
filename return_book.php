<?php
include 'db_connect.php';
include 'penalties.php'; // Include penalties definitions

$success = $error = "";

// Handle Return Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loan_id   = intval($_POST['loan_id'] ?? 0);
    $on_return = $_POST['on_return'] ?? 'good';

    // Fetch loan info
    $stmt = $conn->prepare("
        SELECT l.Loan_ID, l.Book_Copy_ID, l.Member_ID, l.Loan_Date, l.Due_Date, l.Return_Date,
               bc.Condition_Status, bc.Availability_Status, m.Membership_Type, m.Name
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
        // Fine policy
        $policy_stmt = $conn->prepare("SELECT Fine_Per_Day, Max_Fine FROM Fine_Policy WHERE Membership_Type=? LIMIT 1");
        $policy_stmt->bind_param("s", $loan['Membership_Type']);
        $policy_stmt->execute();
        $policy = $policy_stmt->get_result()->fetch_assoc();
        $policy_stmt->close();

        $fine_per_day = (float)($policy['Fine_Per_Day'] ?? 1.00);
        $max_fine     = (float)($policy['Max_Fine'] ?? 100.00);

        // Calculate days late
        $due_date = new DateTime($loan['Due_Date']);
        $today = new DateTime();
        $days_late = max($today->diff($due_date)->days, 0);
        $base_fine = min($days_late * $fine_per_day, $max_fine);

        // Add penalty for damaged/lost
        $penalty = getPenalty($on_return);
        $total_fine = $base_fine + $penalty;

        // Determine new condition & availability
        $new_condition = ($on_return === 'lost') ? 'Lost' : (($on_return === 'damaged') ? 'Damaged' : 'Good');
        $new_avail     = ($on_return === 'lost') ? 'Lost' : 'Available';

        // Update Loan
        $updLoan = $conn->prepare("UPDATE Loan SET Return_Date=CURDATE(), Fine_Amount=?, Condition_Status=? WHERE Loan_ID=?");
        $updLoan->bind_param("dsi", $total_fine, $new_condition, $loan_id);
        $updLoan->execute();
        $updLoan->close();

        // Update Book_Copy
        $updCopy = $conn->prepare("UPDATE Book_Copy SET Condition_Status=?, Availability_Status=? WHERE Copy_ID=?");
        $updCopy->bind_param("ssi", $new_condition, $new_avail, $loan['Book_Copy_ID']);
        $updCopy->execute();
        $updCopy->close();

        $success = "Return processed for <strong>" . htmlspecialchars($loan['Name']) . "</strong>. Total fine: $" . number_format($total_fine, 2);
    }
}

// Fetch all open loans
$open_loans = $conn->query("
    SELECT l.Loan_ID, l.Book_Copy_ID, l.Loan_Date, l.Due_Date, b.Title, m.Name
    FROM Loan l
    JOIN Book_Copy bc ON bc.Copy_ID = l.Book_Copy_ID
    JOIN Book b ON b.Book_ID = bc.Book_ID
    JOIN Member m ON m.Member_ID = l.Member_ID
    WHERE l.Return_Date IS NULL
    ORDER BY l.Due_Date ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Return Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h2 class="mb-4 text-center">📚 Return Book</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Loan ID</th>
                        <th>Member</th>
                        <th>Book Title</th>
                        <th>Loan Date</th>
                        <th>Due Date</th>
                        <th>Return Condition</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($open_loans->num_rows > 0): ?>
                    <?php while ($row = $open_loans->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['Loan_ID'] ?></td>
                            <td><?= htmlspecialchars($row['Name']) ?></td>
                            <td><?= htmlspecialchars($row['Title']) ?></td>
                            <td><?= $row['Loan_Date'] ?></td>
                            <td><?= $row['Due_Date'] ?></td>
                            <td>
                                <form method="post" class="d-flex gap-2">
                                    <input type="hidden" name="loan_id" value="<?= $row['Loan_ID'] ?>">
                                    <select name="on_return" class="form-select form-select-sm" required>
                                        <option value="good">Good</option>
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
        <a class="btn btn-secondary" href="staff_dashboard.php">Back to Dashboard</a>
    </div>
</div>
</body>
</html>
