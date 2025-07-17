<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>View Categories</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <h2>Category List</h2>
    <table class="table table-bordered">
      <tr><th>ID</th><th>Category Name</th></tr>
      <?php
      $result = $conn->query("SELECT * FROM Category");
      while ($row = $result->fetch_assoc()) {
        echo "<tr>
          <td>{$row['Category_ID']}</td>
          <td>{$row['Category_Name']}</td>
        </tr>";
      }
      ?>
    </table>
    <a href="add_category.php" class="btn btn-success">Add Category</a>
    <a href="admin_dashboard.php" class="btn btn-secondary">Back</a>
  </div>
</body>
</html>
