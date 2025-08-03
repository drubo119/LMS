<?php
include 'db_connect.php';

echo "You are in track_book_condition.php<br>";
echo "<pre>";
print_r($_GET);
echo "</pre>";

// Validate and get copy_id
if (!isset($_GET['copy_id']) || !is_numeric($_GET['copy_id'])) {
    die("Invalid copy ID.");
}
$copy_id = $_GET['Copy_ID'];

// Fetch the copy data using correct column names
$stmt = $conn->prepare("SELECT Copy_ID, Book_ID, `Condition`, Availability_Status FROM Book_Copy WHERE Copy_ID = ?");
$stmt->bind_param("i", $copy_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    die("Book copy not found.");
}

$stmt->bind_result($copy_id, $book_id, $condition, $availability);
$stmt->fetch();

$copy = [
    'Copy_ID' => $copy_id,
    'Book_ID' => $book_id,
    'Condition' => $condition,
    'Availability_Status' => $availability,
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $new_condition = $_POST['condition'];
    $new_availability = $_POST['availability'];

    $update_stmt = $conn->prepare("UPDATE Book_Copy SET `Condition` = ?, Availability_Status = ? WHERE Copy_ID = ?");
    $update_stmt->bind_param("ssi", $new_condition, $new_availability, $copy_id);
    
    if ($update_stmt->execute()) {
        header("Location: manage_book_copies.php");
        exit;
    } else {
        echo "Failed to update.";
    }
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
                <option value="New" <?= $copy['Condition'] === 'New' ? 'selected' : '' ?>>New</option>
                <option value="Damaged" <?= $copy['Condition'] === 'Damaged' ? 'selected' : '' ?>>Damaged</option>
                <option value="Lost" <?= $copy['Condition'] === 'Lost' ? 'selected' : '' ?>>Lost</option>
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

