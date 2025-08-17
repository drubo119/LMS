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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body style="background: url('photos/admin.jpg') center center / cover no-repeat; height: 100vh;">

<nav class="navbar navbar-dark bg-dark px-4">
  <a class="navbar-brand fw-bold" href="#">📖 Admin Panel</a>
  <span class="text-white animate__animated animate__fadeInUp animate__delay-1s">Welcome, <?= htmlspecialchars($_SESSION['admin_name']) ?></span>
  <a href="logout.php" class="btn btn-outline-light ms-3">Logout</a>
</nav>

<div class="container my-5" >
  <h2 class="mb-4 text-white animate__animated animate__lightSpeedInLeft">Admin Dashboard</h2>
  <div class="row g-4">

    <!-- Book Management -->
    <div class="col-md-4">
      <div class="card shadow h-100">
        <div class="card-body">
          <h5 class="card-title">📘 Book Management</h5>
          <ul class="list-unstyled">
          <li class="p-3" style="transition: background-color 0.3s;">
  <a href="add_book.php" 
     class="text-decoration-none d-block" 
     style="color: inherit;" 
     onmouseover="this.parentElement.style.backgroundColor='#FFCDD2'" 
     onmouseout="this.parentElement.style.backgroundColor='transparent'">
    Add Book
  </a>
</li>

            <li class="p-3" style="transition: background-color 0.3s;">
  <a href="view_books.php" 
     class="text-decoration-none d-block" 
     style="color: inherit;" 
     onmouseover="this.parentElement.style.backgroundColor='#FFCDD2'" 
     onmouseout="this.parentElement.style.backgroundColor='transparent'">
    View/Edit/Delete Books
  </a>
</li>
            <li class="p-3" style="transition: background-color 0.3s;">
  <a href="manage_authors.php" 
     class="text-decoration-none d-block" 
     style="color: inherit;" 
     onmouseover="this.parentElement.style.backgroundColor='#FFCDD2'" 
     onmouseout="this.parentElement.style.backgroundColor='transparent'">
    Manage Authors
  </a>
</li>
            <li class="p-3" style="transition: background-color 0.3s;">
  <a href="manage_categories.php" 
     class="text-decoration-none d-block" 
     style="color: inherit;" 
     onmouseover="this.parentElement.style.backgroundColor='#FFCDD2'" 
     onmouseout="this.parentElement.style.backgroundColor='transparent'">
    Manage Categories
  </a>
</li>
            <li class="p-3" style="transition: background-color 0.3s;">
  <a href="track_book_condition.php" 
     class="text-decoration-none d-block" 
     style="color: inherit;" 
     onmouseover="this.parentElement.style.backgroundColor='#FFCDD2'" 
     onmouseout="this.parentElement.style.backgroundColor='transparent'">
    Track Book Condition
  </a>
</li>
            <li class="p-3" style="transition: background-color 0.3s;">
  <a href="manage_book_copies.php" 
     class="text-decoration-none d-block" 
     style="color: inherit;" 
     onmouseover="this.parentElement.style.backgroundColor='#FFCDD2'" 
     onmouseout="this.parentElement.style.backgroundColor='transparent'">
    Manage Copies
  </a>
</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Member Management -->
    <div class="col-md-4">
      <div class="card shadow h-100">
        <div class="card-body">
          <h5 class="card-title">👥 Member Management</h5>
          <ul class="list-unstyled">
            <li class="p-3" style="transition: background-color 0.3s;">
  <a href="register_member.php" 
     class="text-decoration-none d-block" 
     style="color: inherit;" 
     onmouseover="this.parentElement.style.backgroundColor='#FFCDD2'" 
     onmouseout="this.parentElement.style.backgroundColor='transparent'">
    Register Member
  </a>
</li>
            <li class="p-3" style="transition: background-color 0.3s;">
  <a href="manage_members.php" 
     class="text-decoration-none d-block" 
     style="color: inherit;" 
     onmouseover="this.parentElement.style.backgroundColor='#FFCDD2'" 
     onmouseout="this.parentElement.style.backgroundColor='transparent'">
    Manage Members
  </a>
</li>
            <li class="p-3" style="transition: background-color 0.3s;">
  <a href="borrow_history.php" 
     class="text-decoration-none d-block" 
     style="color: inherit;" 
     onmouseover="this.parentElement.style.backgroundColor='#FFCDD2'" 
     onmouseout="this.parentElement.style.backgroundColor='transparent'">
    Borrowing History
  </a>
</li>
            <li class="p-3" style="transition: background-color 0.3s;">
  <a href="manage_membership_tiers.php" 
     class="text-decoration-none d-block" 
     style="color: inherit;" 
     onmouseover="this.parentElement.style.backgroundColor='#FFCDD2'" 
     onmouseout="this.parentElement.style.backgroundColor='transparent'">
    Manage Membership Tiers
  </a>
</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Borrowing Management -->
    <div class="col-md-4">
      <div class="card shadow h-100">
        <div class="card-body">
          <h5 class="card-title">📖 Borrowing Management</h5>
          <ul class="list-unstyled">
            <li class="p-3" style="transition: background-color 0.3s;">
  <a href="borrow_return.php" 
     class="text-decoration-none d-block" 
     style="color: inherit;" 
     onmouseover="this.parentElement.style.backgroundColor='#FFCDD2'" 
     onmouseout="this.parentElement.style.backgroundColor='transparent'">
    Borrow Book
  </a>
</li>
            <li class="p-3" style="transition: background-color 0.3s;">
  <a href="return_book.php" 
     class="text-decoration-none d-block" 
     style="color: inherit;" 
     onmouseover="this.parentElement.style.backgroundColor='#FFCDD2'" 
     onmouseout="this.parentElement.style.backgroundColor='transparent'">
    Return Book
  </a>
</li>
            <li class="p-3" style="transition: background-color 0.3s;">
  <a href="update_book_status.php" 
     class="text-decoration-none d-block" 
     style="color: inherit;" 
     onmouseover="this.parentElement.style.backgroundColor='#FFCDD2'" 
     onmouseout="this.parentElement.style.backgroundColor='transparent'">
    Update Book Status
  </a>
</li>
            <li class="p-3" style="transition: background-color 0.3s;">
  <a href="manage_reservations.php" 
     class="text-decoration-none d-block" 
     style="color: inherit;" 
     onmouseover="this.parentElement.style.backgroundColor='#FFCDD2'" 
     onmouseout="this.parentElement.style.backgroundColor='transparent'">
    Manage Reservations
  </a>
</li>
            <li class="p-3" style="transition: background-color 0.3s;">
  <a href="util_fines.php" 
     class="text-decoration-none d-block" 
     style="color: inherit;" 
     onmouseover="this.parentElement.style.backgroundColor='#FFCDD2'" 
     onmouseout="this.parentElement.style.backgroundColor='transparent'">
    Calculate Fines
  </a>
</li>
            <li class="p-3" style="transition: background-color 0.3s;">
  <a href="lost_damaged_books.php" 
     class="text-decoration-none d-block" 
     style="color: inherit;" 
     onmouseover="this.parentElement.style.backgroundColor='#FFCDD2'" 
     onmouseout="this.parentElement.style.backgroundColor='transparent'">
    Lost/Damaged Penalties
  </a>
</li>
          </ul>
        </div>
      </div>
    </div>

  </div>
</div>

</body>
</html>
