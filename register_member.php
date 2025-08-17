<?php
session_start();
include 'config/db.php';

// Ensure only admin can access
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = trim($_POST['name']);
    $membership = trim($_POST['membership_type']); // Student, Faculty, Staff
    $email      = trim($_POST['email']);
    $username   = trim($_POST['username']);
    $password   = $_POST['password']; // plaintext, will hash
    $street     = trim($_POST['street']);
    $city       = trim($_POST['city']);
    $postal     = trim($_POST['postal']);
    $phone      = trim($_POST['phone']);

    // Validate required fields
    if (!$name || !$membership || !$email || !$username || !$password) {
        $error = "Please fill all required fields.";
    } else {
        // Check if email or name already exists
        $check = $conn->prepare("SELECT * FROM member WHERE email=? OR name=?");
        $check->bind_param("ss", $email, $name);
        $check->execute();
        $res = $check->get_result();
        if ($res->num_rows > 0) {
            $error = "Email or Name already exists.";
        } else {
            // Hash the password
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Insert into member table
            $stmt = $conn->prepare("INSERT INTO member 
                (Name, Membership_Type, Email, password, Street, City, Postal_Code, Phone) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $name, $membership, $email, $hash, $street, $city, $postal, $phone);
            if ($stmt->execute()) {
                $success = "Member registered successfully. They can now log in.";
            } else {
                $error = "Failed to register member: " . $conn->error;
            }
            $stmt->close();
        }
        $check->close();
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Register Member</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h2>Register New Member</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label class="form-label">Name*</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Membership Type*</label>
            <select name="membership_type" class="form-select" required>
                <option value="">Select</option>
                <option value="Student">Student</option>
                <option value="Faculty">Faculty</option>
                <option value="Staff">Staff</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Email*</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Username*</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password*</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Street</label>
            <input type="text" name="street" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">City</label>
            <input type="text" name="city" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Postal Code</label>
            <input type="text" name="postal" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Register Member</button>
        <a href="admin_dashboard.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
