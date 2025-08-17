<?php
// Database connection
$servername = "localhost";
$username = "root"; // change if needed
$password = ""; // change if needed
$dbname = "library_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Update condition
if (isset($_POST['update_condition'])) {
    $copy_id = $_POST['copy_id'];
    $new_condition = $_POST['condition_status'];

    $sql = "UPDATE Book_Copy SET Condition_Status = '$new_condition' WHERE Copy_ID = $copy_id";
    if ($conn->query($sql) === TRUE) {
        $message = "<div class='alert alert-success'>Condition updated successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}

// Search for book copy
$book_data = null;
if (isset($_GET['search'])) {
    $copy_id = $_GET['copy_id'];
    $sql = "SELECT bc.Copy_ID, b.Title, bc.Condition_Status, bc.Availability_Status
            FROM Book_Copy bc
            JOIN Book b ON bc.Book_ID = b.Book_ID
            WHERE bc.Copy_ID = $copy_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $book_data = $result->fetch_assoc();
    } else {
        $message = "<div class='alert alert-warning'>No book copy found with that ID.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Book Condition</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="text-center text-primary mb-4">Update Book Condition</h2>
        <?php echo $message; ?>

        <!-- Search Form -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-8">
                <input type="number" class="form-control" name="copy_id" placeholder="Enter Copy ID" required>
            </div>
            <div class="col-md-4">
                <button type="submit" name="search" class="btn btn-primary w-100">Search</button>
            </div>
        </form>

        <!-- Book Data & Update Form -->
        <?php if ($book_data): ?>
            <div class="border rounded p-3 mb-3 bg-white">
                <h5 class="text-success"><?php echo $book_data['Title']; ?></h5>
                <p><strong>Copy ID:</strong> <?php echo $book_data['Copy_ID']; ?></p>
                <p><strong>Current Condition:</strong> <?php echo $book_data['Condition_Status']; ?></p>
                <p><strong>Availability:</strong> <?php echo $book_data['Availability_Status']; ?></p>
            </div>

            <form method="POST" class="row g-3">
                <input type="hidden" name="copy_id" value="<?php echo $book_data['Copy_ID']; ?>">
                <div class="col-md-8">
                    <select class="form-select" name="condition_status">
                        <option value="New" <?php if($book_data['Condition_Status']=="New") echo "selected"; ?>>New</option>
                        <option value="Damaged" <?php if($book_data['Condition_Status']=="Damaged") echo "selected"; ?>>Damaged</option>
                        <option value="Lost" <?php if($book_data['Condition_Status']=="Lost") echo "selected"; ?>>Lost</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" name="update_condition" class="btn btn-success w-100">Update Condition</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>

<?php $conn->close(); ?>



