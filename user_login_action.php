<?php
session_start();
include 'config/db.php';

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_id'] = $user['id'];
    header("Location: user_dashboard.php");
    exit();
} else {
    echo "<script>alert('Invalid email or password'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>

