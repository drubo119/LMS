<?php
include 'db_connect.php';

$book_id = $_GET['id'];

if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $author_id = $_POST['author_id'];
    $category_id = $_POST['category_id'];
    $publisher = $_POST['publisher'];
    $isbn = $_POST['isbn'];
    $language = $_POST['language'];

    $update = "UPDATE Book SET Title='$title', Author_ID='$author_id', Category_ID='$category_id', 
               Publisher='$publisher', ISBN='$isbn', Language='$language' WHERE Book_ID=$book_id";
    mysqli_query($conn, $update);
    header("Location: view_books.php");
    exit();
}

$query = "SELECT * FROM Book WHERE Book_ID = $book_id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// Fetch authors & categories
$authors = mysqli_query($conn, "SELECT * FROM Author");
$categories = mysqli_query($conn, "SELECT * FROM Category");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Book</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Edit Book</h2>
    <a href="view_books.php" class="btn btn-secondary mb-3">← Back</a>
    <form method="POST">
        <div class="mb-3">
            <label>Title:</label>
            <input type="text" name="title" class="form-control" value="<?= $row['Title'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Author:</label>
            <select name="author_id" class="form-select" required>
                <?php while ($author = mysqli_fetch_assoc($authors)): ?>
                    <option value="<?= $author['Author_ID'] ?>" <?= ($author['Author_ID'] == $row['Author_ID']) ? 'selected' : '' ?>>
                        <?= $author['Author_Name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Category:</label>
            <select name="category_id" class="form-select" required>
                <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                    <option value="<?= $cat['Category_ID'] ?>" <?= ($cat['Category_ID'] == $row['Category_ID']) ? 'selected' : '' ?>>
                        <?= $cat['Category_Name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Publisher:</label>
            <input type="text" name="publisher" class="form-control" value="<?= $row['Publisher'] ?>">
        </div>
        <div class="mb-3">
            <label>ISBN:</label>
            <input type="text" name="isbn" class="form-control" value="<?= $row['ISBN'] ?>">
        </div>
        <div class="mb-3">
            <label>Language:</label>
            <input type="text" name="language" class="form-control" value="<?= $row['Language'] ?>">
        </div>
        <button type="submit" name="update" class="btn btn-success">Update Book</button>
    </form>
</div>
</body>
</html>
