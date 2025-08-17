<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['id'])) {
    header("Location: login_action.php");
    exit;
}

$user_id = $_SESSION['id'];

// Get basic user info from users table
$stmt_user = $conn->prepare("SELECT name, email FROM users WHERE id=?");
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();

if (!$user) {
    die("User not found.");
}

// Try to get member info
$stmt_member = $conn->prepare("SELECT * FROM member WHERE Member_ID=?");
$stmt_member->bind_param("i", $user_id);
$stmt_member->execute();
$result_member = $stmt_member->get_result();
$member = $result_member->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $membership_type = trim($_POST['membership_type']);
    $email = trim($_POST['email']);
    $street = trim($_POST['street']);
    $city = trim($_POST['city']);
    $postal_code = trim($_POST['postal_code']);
    $phone = trim($_POST['phone']);

    if ($member) {
        // Update existing member
        $update = $conn->prepare("UPDATE member SET Name=?, Membership_Type=?, Email=?, Street=?, City=?, Postal_Code=?, Phone=? WHERE Member_ID=?");
        $update->bind_param("sssssssi", $name, $membership_type, $email, $street, $city, $postal_code, $phone, $user_id);
    } else {
        // Insert new member
        $update = $conn->prepare("INSERT INTO member (Member_ID, Name, Membership_Type, Email, Street, City, Postal_Code, Phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $update->bind_param("isssssss", $user_id, $name, $membership_type, $email, $street, $city, $postal_code, $phone);
    }

    if ($update->execute()) {
        $success = "Profile saved successfully!";
        // Refresh member data
        $member = [
            'Member_ID' => $user_id,
            'Name' => $name,
            'Membership_Type' => $membership_type,
            'Email' => $email,
            'Street' => $street,
            'City' => $city,
            'Postal_Code' => $postal_code,
            'Phone' => $phone
        ];
    } else {
        $error = "Error saving profile. Please try again.";
    }
}

// Pre-fill form: use member info if exists, otherwise use basic user info
$form_data = $member ?? [
    'Name' => $user['name'],
    'Membership_Type' => '',
    'Email' => $user['email'],
    'Street' => '',
    'City' => '',
    'Postal_Code' => '',
    'Phone' => ''
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4">
        <h3 class="mb-3">Edit Profile</h3>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php elseif (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($form_data['Name']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Membership Type</label>
                <input type="text" name="membership_type" class="form-control" value="<?= htmlspecialchars($form_data['Membership_Type']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($form_data['Email']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Street</label>
                <input type="text" name="street" class="form-control" value="<?= htmlspecialchars($form_data['Street'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">City</label>
                <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($form_data['City'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Postal Code</label>
                <input type="text" name="postal_code" class="form-control" value="<?= htmlspecialchars($form_data['Postal_Code'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($form_data['Phone'] ?? '') ?>">
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
</body>
</html>
