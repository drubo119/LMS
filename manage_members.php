<?php
include 'db_connect.php';

// Search
$search = "";
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $stmt = $conn->prepare("SELECT * FROM Member WHERE Name LIKE ? OR Email LIKE ?");
    $searchTerm = "%$search%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
} else {
    $stmt = $conn->prepare("SELECT * FROM Member");
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Members</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <h2 class="mb-4">Manage Members</h2>
    <!-- <a href="borrow_history.php?id=<?= $row['Member_ID']; ?>" class="btn btn-info btn-sm">History</a> -->


    <a href="admin_dashboard.php" class="btn btn-success mb-3">← Back to Dashboard</a>
    

    <!-- Search Form -->
    <form method="GET" class="d-flex mb-3">
        <input type="text" name="search" class="form-control me-2" placeholder="Search by name or email" value="<?= htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-primary">Search</button>
        <a href="manage_members.php" class="btn btn-secondary ms-2">Reset</a>
    </form>

    <!-- Members Table -->
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Member ID</th>
                <th>Name</th>
                <th>Membership Type</th>
                <th>Email</th>
                <th>Street</th>
                <th>City</th>
                <th>Postal Code</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['Member_ID']; ?></td>
                    <td><?= htmlspecialchars($row['Name']); ?></td>
                    <td><?= htmlspecialchars($row['Membership_Type']); ?></td>
                    <td><?= htmlspecialchars($row['Email']); ?></td>
                    <td><?= htmlspecialchars($row['Street'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($row['City'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($row['Postal_Code'] ?? 'N/A'); ?></td>
                    <td><?= htmlspecialchars($row['Phone'] ?? 'N/A'); ?></td>
                    <td>
                        <a href="edit_member.php?id=<?= $row['Member_ID']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_member.php?id=<?= $row['Member_ID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this member?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="9" class="text-center">No members found</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>

