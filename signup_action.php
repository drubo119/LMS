<?php
include 'config/db.php';

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$tier = $_POST['tier'];

$sql = "INSERT INTO users (name, email, password, tier) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $name, $email, $password, $tier);

if ($stmt->execute()) {
    echo "<script>alert('Signup successful!'); window.location.href='user_login.php';</script>";
} else {
    echo "<script>alert('Error: Email already exists or something went wrong.'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
