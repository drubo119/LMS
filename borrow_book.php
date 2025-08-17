<?php
// borrow_book.php
include 'db_connect.php';

$success = $error = "";

// Handle borrow submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = intval($_POST['member_id'] ?? 0);
    $copy_id   = intval($_POST['copy_id'] ?? 0);

    if ($member_id <= 0 || $copy_id <= 0) {
        $error = "Please select a member and a copy.";
    } else {
        // Verify copy is AVAILABLE
        $chk = $conn->prepare("SELECT Book_ID, Availability_Status FROM Book_Copy WHERE Copy_ID=?");
        $chk->bind_param("i", $copy_id);
        $chk->execute();
        $copy = $chk->get_result()->fetch_assoc();
        $chk->close();

        if (!$copy || $copy['Availability_Status'] !== 'Available') {
            $error = "This copy is not available.";
        } else {
            // Create loan (2-week default)
            $loan = $conn->prepare("
                INSERT INTO Loan (Book_Copy_ID, Member_ID, Loan_Date, Due_Date, Return_Date, Fine_Amount)
                VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 14 DAY), NULL, 0.00)
            ");
            $loan->bind_param("ii", $copy_id, $member_id);
            if ($loan->execute()) {
                $loan->close();
                // Update copy status to LOANED
                $upd = $conn->prepare("UPDATE Book_Copy SET Availability_Status='Loaned' WHERE Copy_ID=?");
                $upd->bind_param("i", $copy_id);
                $upd->execute();
                $upd->close();
                $success = "Book successfully borrowed!";
            } else {
                $error = "Error creating loan: " . $conn->error;
            }
        }
    }
}

// Load members
$members = $conn->query("SELECT Member_ID, Name, Email FROM Member ORDER BY Name");

// Load AVAILABLE copies with Book title
$copies = $conn->query("
    SELECT bc.Copy_ID, b.Title 
    FROM Book_Copy bc 
    JOIN Book b ON b.Book_ID = bc.Book_ID
    WHERE bc.Availability_Status='Available'
    ORDER BY b.Title, bc.Copy_ID
");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Borrow Book</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <h2>Borrow Book</h2>

  <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
  <?php if ($error):   ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

  <form method="post" class="card p-4 shadow-sm">
    <div class="mb-3">
      <label class="form-label">Member</label>
      <select name="member_id" class="form-select" required>
        <option value="">Select Member</option>
        <?php while ($m = $members->fetch_assoc()): ?>
          <option value="<?= (int)$m['Member_ID'] ?>">
            <?= htmlspecialchars($m['Name']) ?> (<?= htmlspecialchars($m['Email']) ?>)
          </option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Available Copy</label>
      <select name="copy_id" class="form-select" required>
        <option value="">Select Copy</option>
        <?php while ($c = $copies->fetch_assoc()): ?>
          <option value="<?= (int)$c['Copy_ID'] ?>">
            <?= htmlspecialchars($c['Title']) ?> — Copy #<?= (int)$c['Copy_ID'] ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-primary">Borrow</button>
      <a href="admin_dashboard.php" class="btn btn-secondary">Back</a>
    </div>
  </form>
</div>
</body>
</html>

