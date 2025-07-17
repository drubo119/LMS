<?php
include 'db_connect.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $author_id = $_POST["author_id"];
    $category_id = $_POST["category_id"];
    $publisher = $_POST["publisher"];
    $isbn = $_POST["isbn"];
    $language = $_POST["language"];

    $sql = "INSERT INTO Book (Title, Author_ID, Category_ID, Publisher, ISBN, Language)
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siisss", $title, $author_id, $category_id, $publisher, $isbn, $language);

    if ($stmt->execute()) {
        $message = "✅ Book added successfully!";
    } else {
        $message = "❌ Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Add Book</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h2 class="mb-4">Add New Book</h2>

    <?php if ($message): ?>
      <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label class="form-label">Book Title</label>
        <input type="text" class="form-control" name="title" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Author</label>
        <select class="form-select" name="author_id" required>
          <option selected disabled>Select Author</option>
          <?php
          $authors = $conn->query("SELECT Author_ID, Author_Name FROM Author");
          while ($row = $authors->fetch_assoc()) {
              echo "<option value='{$row['Author_ID']}'>{$row['Author_Name']}</option>";
          }
          ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Category</label>
        <select class="form-select" name="category_id" required>
          <option selected disabled>Select Category</option>
          <?php
          $categories = $conn->query("SELECT Category_ID, Category_Name FROM Category");
          while ($row = $categories->fetch_assoc()) {
              echo "<option value='{$row['Category_ID']}'>{$row['Category_Name']}</option>";
          }
          ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Publisher</label>
        <input type="text" class="form-control" name="publisher">
      </div>

      <div class="mb-3">
        <label class="form-label">ISBN</label>
        <input type="text" class="form-control" name="isbn">
      </div>

      <div class="mb-3">
        <label class="form-label">Language</label>
        <input type="text" class="form-control" name="language">
      </div>

      <button type="submit" class="btn btn-primary">Add Book</button>
      <a href="admin_dashboard.php" class="btn btn-secondary ">
  ← Back to Dashboard
</a>

    </form>
  </div>
</body>
</html>
