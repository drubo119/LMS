<?php
include 'db_connect.php';

// Handle Add or Edit
if (isset($_POST['save'])) {
    $membership_type = $_POST['membership_type'];
    $borrow_limit = $_POST['borrow_limit'];
    $fine_per_day = $_POST['fine_per_day'];
    $max_fine = $_POST['max_fine'];

    if (!empty($_POST['id'])) {
        // Update
        $stmt = $conn->prepare("UPDATE Fine_Policy SET Membership_Type=?, Borrow_Limit=?, Fine_Per_Day=?, Max_Fine=? WHERE Policy_ID=?");
        $stmt->bind_param("siddi", $membership_type, $borrow_limit, $fine_per_day, $max_fine, $_POST['id']);
        $stmt->execute();
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO Fine_Policy (Membership_Type, Borrow_Limit, Fine_Per_Day, Max_Fine) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sidd", $membership_type, $borrow_limit, $fine_per_day, $max_fine);
        $stmt->execute();
    }
    header("Location: manage_membership_tiers.php");
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM Fine_Policy WHERE Policy_ID=$id");
    header("Location: manage_membership_tiers.php");
    exit;
}

// Fetch Records
$tiers = $conn->query("SELECT * FROM Fine_Policy ORDER BY Membership_Type ASC");

// Edit Mode
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM Fine_Policy WHERE Policy_ID=$id");
    $editData = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Membership Tiers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h2 class="mb-4">Manage Membership Tiers</h2>

    <!-- Add/Edit Form -->
    <div class="card p-4 mb-4">
        <h4><?= $editData ? "Edit Tier" : "Add New Tier" ?></h4>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $editData['Policy_ID'] ?? '' ?>">

            <div class="mb-3">
                <label class="form-label">Membership Type</label>
                <input type="text" name="membership_type" class="form-control" required value="<?= $editData['Membership_Type'] ?? '' ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Borrow Limit</label>
                <input type="number" name="borrow_limit" class="form-control" required value="<?= $editData['Borrow_Limit'] ?? '' ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Fine Per Day</label>
                <input type="number" step="0.01" name="fine_per_day" class="form-control" required value="<?= $editData['Fine_Per_Day'] ?? '' ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Max Fine</label>
                <input type="number" step="0.01" name="max_fine" class="form-control" required value="<?= $editData['Max_Fine'] ?? '' ?>">
            </div>

            <button type="submit" name="save" class="btn btn-success"><?= $editData ? "Update" : "Add" ?></button>
            <?php if ($editData): ?>
                <a href="manage_membership_tiers.php" class="btn btn-secondary">Cancel</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Display Membership Tiers -->
    <div class="card p-4">
        <h4>Membership Tiers</h4>
        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Membership Type</th>
                    <th>Borrow Limit</th>
                    <th>Fine Per Day</th>
                    <th>Max Fine</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $tiers->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['Policy_ID'] ?></td>
                        <td><?= htmlspecialchars($row['Membership_Type']) ?></td>
                        <td><?= $row['Borrow_Limit'] ?></td>
                        <td><?= number_format($row['Fine_Per_Day'], 2) ?></td>
                        <td><?= number_format($row['Max_Fine'], 2) ?></td>
                        <td>
                            <a href="?edit=<?= $row['Policy_ID'] ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="?delete=<?= $row['Policy_ID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this tier?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
