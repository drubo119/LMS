<?php
include 'db_connect.php';

$query = "SELECT bc.Copy_ID, b.Book_ID, b.Title, bc.Condition_Status, bc.Availability_Status 
          FROM Book_Copy bc 
          JOIN Book b ON bc.Book_ID = b.Book_ID";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Book Copies | Library</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
<div class="container">
    <h2 class="mb-4">Manage Book Copies</h2>

    <a href="admin_dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Copy ID</th>
                    <th>Book ID</th>
                    <th>Title</th>
                    <th>Condition</th>
                    <th>Availability</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['Copy_ID']) ?></td>
                    <td><?= htmlspecialchars($row['Book_ID']) ?></td>
                    <td><?= htmlspecialchars($row['Title']) ?></td>
                    <td><?= htmlspecialchars($row['Condition_Status']) ?></td>
                    <td><?= htmlspecialchars($row['Availability_Status']) ?></td>
                    <td>
                    <a href="track_book_condition.php?copy_id=<?= $row['Copy_ID']; ?>" class="btn btn-sm btn-primary">Edit</a>

                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">No book copies found.</div>
    <?php endif; ?>
</div>
</body>
</html>
