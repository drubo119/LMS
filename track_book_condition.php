<?php
include 'db_connect.php';

if (!isset($_GET['copy_id']) || !is_numeric($_GET['copy_id'])) {
    die("Invalid copy ID.");
}
$copy_id = $_GET['copy_id'];

// Fetch the copy data
$stmt = $conn->prepare("SELECT * FROM Book_Copy WHERE Copy_ID = ?");
$stmt->bind_param("i", $copy_id);
$stmt->execute();
$result = $stmt->get_result();
$copy = $result->fetch_assoc();

if (!$copy) {
    die("Book copy not found.");
}

if (isset($_POST['update'])) {
    $condition = $_POST['condition'];
    $availability = $_POST['availability'];

    $update = $conn->prepare("UPDATE Book_Copy SET Condition_Status = ?, Availability_Status = ? WHERE Copy_ID = ?");
    $update->bind_param("ssi", $condition, $availability, $copy_id);
    $update->execute();

    header("Location: manage_book_copies.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Book Copy</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
<div class="container">
    <h2>Edit Book Copy Condition</h2>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Condition Status</label>
            <select name="condition" class="form-control" required>
                <option value="New" <?= $copy['Condition_Status'] === 'New' ? 'selected' : '' ?>>New</option>
                <option value="Damaged" <?= $copy['Condition_Status'] === 'Damaged' ? 'selected' : '' ?>>Damaged</option>
                <option value="Lost" <?= $copy['Condition_Status'] === 'Lost' ? 'selected' : '' ?>>Lost</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Availability Status</label>
            <select name="availability" class="form-control" required>
                <option value="Available" <?= $copy['Availability_Status'] === 'Available' ? 'selected' : '' ?>>Available</option>
                <option value="Reserved" <?= $copy['Availability_Status'] === 'Reserved' ? 'selected' : '' ?>>Reserved</option>
                <option value="Loaned" <?= $copy['Availability_Status'] === 'Loaned' ? 'selected' : '' ?>>Loaned</option>
            </select>
        </div>
        <button type="submit" name="update" class="btn btn-success">Update</button>
        <a href="manage_book_copies.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>

