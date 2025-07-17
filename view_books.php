
<?php
include 'db_connect.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT b.Book_ID, b.Title, a.Author_Name, c.Category_Name, b.Publisher, b.ISBN, b.Language
          FROM Book b
          LEFT JOIN Author a ON b.Author_ID = a.Author_ID
          LEFT JOIN Category c ON b.Category_ID = c.Category_ID
          WHERE b.Title LIKE '%$search%' OR a.Author_Name LIKE '%$search%' OR c.Category_Name LIKE '%$search%'";

$result = mysqli_query($conn, $query);
?>


<!DOCTYPE html>
<html>
<head>
    <title>View Books</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">All Books</h2>
    <a href="admin_dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>
    <form method="GET" class="d-flex mb-3" role="search">
    <input type="text" name="search" class="form-control me-2" placeholder="Search by Title, Author or Category" value="<?= htmlspecialchars($search) ?>">
    <button class="btn btn-primary" type="submit">Search</button>
</form>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Publisher</th>
                <th>ISBN</th>
                <th>Language</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['Title'] ?></td>
                <td><?= $row['Author_Name'] ?></td>
                <td><?= $row['Category_Name'] ?></td>
                <td><?= $row['Publisher'] ?></td>
                <td><?= $row['ISBN'] ?></td>
                <td><?= $row['Language'] ?></td>
                <td>
                    <a href="update_book.php?id=<?= $row['Book_ID'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="delete_book.php?id=<?= $row['Book_ID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this book?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
