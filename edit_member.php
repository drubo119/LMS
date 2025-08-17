<?php
include 'db_connect.php';

if (!isset($_GET['id'])) {
    header("Location: manage_members.php");
    exit;
}

$member_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM Member WHERE Member_ID = ?");
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Member not found!";
    exit;
}

$member = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $membership_type = trim($_POST['membership_type']);
    $email = trim($_POST['email']);
    $street = trim($_POST['street']);
    $city = trim($_POST['city']);
    $postal_code = trim($_POST['postal_code']);
    $phone = trim($_POST['phone']);

    $update_stmt = $conn->prepare("UPDATE Member SET Name=?, Membership_Type=?, Email=?, Street=?, City=?, Postal_Code=?, Phone=? WHERE Member_ID=?");
    $update_stmt->bind_param("sssssssi", $name, $membership_type, $email, $street, $city, $postal_code, $phone, $member_id);
    $update_stmt->execute();

    header("Location: manage_members.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Member</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h2 class="mb-4">Edit Member</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($member['Name']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Membership Type</label>
            <input type="text" class="form-control" name="membership_type" value="<?= htmlspecialchars($member['Membership_Type']); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($member['Email']); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Street</label>
            <input type="text" class="form-control" name="street" value="<?= htmlspecialchars($member['Street']); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">City</label>
            <input type="text" class="form-control" name="city" value="<?= htmlspecialchars($member['City']); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Postal Code</label>
            <input type="text" class="form-control" name="postal_code" value="<?= htmlspecialchars($member['Postal_Code']); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($member['Phone']); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update Member</button>
        <a href="manage_members.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>

