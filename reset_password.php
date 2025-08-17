<?php
include 'db_connect.php';

if (!isset($_GET['id'])) {
    die("Member ID not provided.");
}

$member_id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE UserAccount SET Password=? WHERE Member_ID=?");
    $stmt->bind_param("si", $new_password, $member_id);

    if ($stmt->execute()) {
        header("Location: manage_members.php?msg=Password reset successfully");
        exit;
    } else {
        echo "Error updating password: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h2>Reset Password</h2>
    <form method="POST">
        <div class="mb-3">
            <label>New Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-warning">Update Password</button>
        <a href="manage_members.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
