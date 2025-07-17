<?php
include 'db_connect.php';

$query = "SELECT bc.Copy_ID, b.Title, bc.Condition_Status, bc.Availability_Status 
          FROM Book_Copy bc 
          JOIN Book b ON bc.Book_ID = b.Book_ID";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Book Copies</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
<div class="container">
    <h2>Manage Book Copies</h2>
    <a href="admin_dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Copy ID</th>
                <th>Title</th>
                <th>Condition</th>
                <th>Availability</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $row['Copy_ID'] ?></td>
                <td><?= $row['Title'] ?></td>
                <td><?= $row['Condition_Status'] ?></td>
                <td><?= $row['Availability_Status'] ?></td>
                <td>
                    <a href="edit_copy_condition.php?copy_id=<?= $row['Copy_ID'] ?>" class="btn btn-sm btn-primary">Edit</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
