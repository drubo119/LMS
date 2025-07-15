<!--  -->

<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_name']) && !isset($_SESSION['admin_name'])) {
    header("Location: user_login.php");
    exit();
}

// Get logged in user/admin name and role
$name = $_SESSION['user_name'] ?? $_SESSION['admin_name'] ?? 'User';
$role = isset($_SESSION['admin_name']) ? 'Admin' : 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard - Library Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" defer></script>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="dashboard.php">📖 LMS Dashboard</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
      <ul class="navbar-nav align-items-center">
        <li class="nav-item me-3">
          <span class="nav-link text-white">Hello, <strong><?= htmlspecialchars($name) ?> (<?= $role ?>)</strong></span>
        </li>
        <li class="nav-item">
          <a class="btn btn-outline-light" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Main Dashboard Content -->
<div class="container my-5">
  <h2 class="mb-4">Dashboard</h2>
  <div class="row g-4">

    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">📚 Book Management</h5>
          <p class="card-text flex-grow-1">Add, update, delete, and view book records.</p>
          <a href="add_book.php" class="btn btn-primary mt-auto">Add Book</a>
          <a href="view_books.php" class="btn btn-secondary mt-2">View Books</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">👥 Member Management</h5>
          <p class="card-text flex-grow-1">Manage library members and track borrowing history.</p>
          <a href="add_member.php" class="btn btn-primary mt-auto">Add Member</a>
          <a href="view_members.php" class="btn btn-secondary mt-2">View Members</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">📖 Borrowing Management</h5>
          <p class="card-text flex-grow-1">Manage borrow/return transactions and fines.</p>
          <a href="borrow_book.php" class="btn btn-primary mt-auto">Borrow Book</a>
          <a href="return_book.php" class="btn btn-secondary mt-2">Return Book</a>
          <a href="view_borrowings.php" class="btn btn-secondary mt-2">View Borrowings</a>
        </div>
      </div>
    </div>

  </div>
</div>

</body>
</html>
