<?php
include 'db_connect.php'; // your DB connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $membership_type = $_POST['membership_type'];
    $email = $_POST['email'];
    
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // hash password
    $access_level = 'Member'; // default for new members

    // 1️⃣ Insert into Member table
    $sql_member = "INSERT INTO Member (Name, Membership_Type, Email, Street, City, Postal_Code) 
                   VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_member = $conn->prepare($sql_member);
    $stmt_member->bind_param("ssssss", $name, $membership_type, $email, $street, $city, $postal_code);
    $stmt_member->execute();
    $member_id = $stmt_member->insert_id; // get the new member's ID
    $stmt_member->close();

    // 2️⃣ Insert into UserAccount table
    $sql_user = "INSERT INTO UserAccount (Member_ID, Username, Password, Access_Level) 
                 VALUES (?, ?, ?, ?)";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("isss", $member_id, $username, $password, $access_level);
    $stmt_user->execute();
    $stmt_user->close();

    echo "<div class='alert alert-success'>✅ Member registered and account created successfully!</div>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Member</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4">Register New Member</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Membership Type</label>
                <select name="membership_type" class="form-select" required>
                    <option value="Student">Student</option>
                    <option value="Faculty">Faculty</option>
                    <option value="Staff">Staff</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            

            <h4 class="mt-4">Login Information</h4>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Register Member</button>
        </form>
    </div>
</div>
</body>
</html>
