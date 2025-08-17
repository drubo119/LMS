<?php
session_start();
include 'config/db.php';

$email_or_username = $_POST['email']; // email or username
$password = $_POST['password'];

// 1️⃣ Check in users table
$sql_user = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql_user);
$stmt->bind_param("s", $email_or_username);
$stmt->execute();
$result_user = $stmt->get_result();
$user = $result_user->fetch_assoc();
$stmt->close();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_type'] = 'user';
    header("Location: user_dashboard.php");
    exit();
}

// 2️⃣ If not found, check in member table (members added by admin)
$sql_member = "SELECT * FROM member WHERE email = ? OR name = ?";
$stmt = $conn->prepare($sql_member);
$stmt->bind_param("ss", $email_or_username, $email_or_username);
$stmt->execute();
$result_member = $stmt->get_result();
$member = $result_member->fetch_assoc();
$stmt->close();

if ($member && password_verify($password, $member['password'])) {
    $_SESSION['user_name'] = $member['Name'];
    $_SESSION['user_id'] = $member['Member_ID'];
    $_SESSION['user_type'] = 'member';
    header("Location: user_dashboard.php");
    exit();
}

// 3️⃣ If neither matches
echo "<script>alert('Invalid email/username or password'); window.history.back();</script>";
$conn->close();
?>
