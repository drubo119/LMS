<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Add Category</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <h2>Add Category</h2>
    <form method="post" action="insert_category.php">
      <div class="mb-3">
        <label>Category Name</label>
        <input type="text" name="category_name" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary">Add Category</button>
      <a href="admin_dashboard.php" class="btn btn-secondary">Back</a>
    </form>
  </div>
</body>
</html>
