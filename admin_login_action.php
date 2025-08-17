<?php
session_start();
include 'config/db.php';

$username = trim($_POST['username']);
$password = trim($_POST['password']);

// Check Staff table
$sql = "SELECT * FROM Staff WHERE Username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$staff = $result->fetch_assoc();

if ($staff && password_verify($password, $staff['Password'])) {
    $_SESSION['staff_id']   = $staff['Staff_ID'];
    $_SESSION['staff_name'] = $staff['Name'];
    $_SESSION['staff_role'] = $staff['Role']; // Admin or Librarian

    // Redirect based on role
    if ($staff['Role'] === 'Admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: staff_dashboard.php");
    }
    exit();
} else {
    echo "<script>alert('Invalid credentials'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
