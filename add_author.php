<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Add Author</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <h2>Add Author</h2>
    <form method="post" action="insert_author.php">
      <div class="mb-3">
        <label>Author Name</label>
        <input type="text" name="author_name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Nationality</label>
        <input type="text" name="nationality" class="form-control">
      </div>
      <button type="submit" class="btn btn-primary">Add Author</button>
      <a href="dashboard.php" class="btn btn-secondary">Back</a>
    </form>
  </div>
</body>
</html>
