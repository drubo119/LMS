<?php
session_start();
include 'config/db.php';

// Ensure only staff can access
if (!isset($_SESSION['staff_id'])) {
    header("Location: staff_login.php");
    exit();
}

$staff_name = $_SESSION['staff_name'] ?? 'Staff';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Staff Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Library Staff Dashboard</a>
        <div class="ms-auto text-white">
            Welcome, <?= htmlspecialchars($staff_name) ?>
            <a href="index.php" class="btn btn-sm btn-outline-light ms-2">Logout</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    <h2 class="mb-4">Quick Actions</h2>
    <div class="row g-4">

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Borrow Book</h5>
                    <p class="card-text">Issue books to members and manage loans.</p>
                    <a href="borrow_book.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Return Book</h5>
                    <p class="card-text">Record book returns and calculate fines.</p>
                    <a href="return_book.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Update Book Status</h5>
                    <p class="card-text">Mark books as Available, Loaned, Reserved, Damaged, or Lost.</p>
                    <a href="update_book_status.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Manage Reservations</h5>
                    <p class="card-text">Approve or cancel member reservation requests.</p>
                    <a href="staff_dashboard.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Calculate Fines</h5>
                    <p class="card-text">Check overdue fines for members.</p>
                    <a href="calculate_fine.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Lost/Damaged Penalties</h5>
                    <p class="card-text">Apply penalties for lost or damaged books.</p>
                    <a href="damage_penalty.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

    </div>
</div>
</body>
</html>
