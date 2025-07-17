<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>View Authors</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <h2>Authors List</h2>
    <table class="table table-bordered">
      <tr><th>ID</th><th>Name</th><th>Nationality</th></tr>
      <?php
      $result = $conn->query("SELECT * FROM Author");
      while ($row = $result->fetch_assoc()) {
        echo "<tr>
          <td>{$row['Author_ID']}</td>
          <td>{$row['Author_Name']}</td>
          <td>{$row['Nationality']}</td>
        </tr>";
      }
      ?>
    </table>
    <a href="add_author.php" class="btn btn-success">Add Author</a>
    <a href="admin_dashboard.php" class="btn btn-secondary">Back</a>
  </div>
</body>
</html>
