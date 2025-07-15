<?php
session_start();
include 'config/db.php';

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM admins WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['admin_name'] = $admin['username'];
    $_SESSION['admin_id'] = $admin['id'];
    header("Location: dashboard.php");
    exit();
} else {
    echo "<script>alert('Invalid admin credentials'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
