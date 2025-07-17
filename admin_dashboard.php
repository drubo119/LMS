<?php
session_start();
if (!isset($_SESSION['admin_name'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: url('photos/admin.jpg') center center / cover no-repeat; height: 100vh;">

<nav class="navbar navbar-dark bg-dark px-4">
  <a class="navbar-brand fw-bold" href="#">📖 Admin Panel</a>
  <span class="text-white">Welcome, <?= htmlspecialchars($_SESSION['admin_name']) ?></span>
  <a href="logout.php" class="btn btn-outline-light ms-3">Logout</a>
</nav>

<div class="container my-5" >
  <h2 class="mb-4 text-white">Admin Dashboard</h2>
  <div class="row g-4">

    <!-- Book Management -->
    <div class="col-md-4">
      <div class="card shadow h-100">
        <div class="card-body">
          <h5 class="card-title">📘 Book Management</h5>
          <ul>
            <li><a href="add_book.php">Add Book</a></li>
            <li><a href="view_books.php">View/Edit/Delete Books</a></li>
            <li><a href="manage_authors.php">Manage Authors </a></li>
            <li><a href="manage_categories.php">Manage Categories</a></li>
            <li><a href="edit_copy_condition.php">Track Book Condition</a></li>
            <li><a href="manage_book_copies.php">Manage Copies</a></li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Member Management -->
    <div class="col-md-4">
      <div class="card shadow h-100">
        <div class="card-body">
          <h5 class="card-title">👥 Member Management</h5>
          <ul>
            <li><a href="add_member.php">Register Member</a></li>
            <li><a href="view_members.php">Manage Members</a></li>
            <li><a href="member_history.php">Borrowing History</a></li>
            <li><a href="membership_tiers.php">Manage Membership Tiers</a></li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Borrowing Management -->
    <div class="col-md-4">
      <div class="card shadow h-100">
        <div class="card-body">
          <h5 class="card-title">📖 Borrowing Management</h5>
          <ul>
            <li><a href="borrow_book.php">Borrow Book</a></li>
            <li><a href="return_book.php">Return Book</a></li>
            <li><a href="update_book_status.php">Update Book Status</a></li>
            <li><a href="reserve_books.php">Manage Reservations</a></li>
            <li><a href="manage_fines.php">Calculate Fines</a></li>
            <li><a href="lost_damaged_books.php">Lost/Damaged Penalties</a></li>
          </ul>
        </div>
      </div>
    </div>

  </div>
</div>

</body>
</html>
