<?php
include 'db_connect.php';

$success = $error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $copy_id = intval($_POST['copy_id'] ?? 0);
    $condition = $_POST['condition'] ?? '';
    $availability = $_POST['availability'] ?? '';

    if ($copy_id > 0 && $condition && $availability) {
        $stmt = $conn->prepare("UPDATE Book_Copy SET Condition_Status=?, Availability_Status=? WHERE Copy_ID=?");
        $stmt->bind_param("ssi", $condition, $availability, $copy_id);

        if ($stmt->execute()) {
            $success = "Book copy status updated successfully!";
        } else {
            $error = "Error updating status: " . $conn->error;
        }

        $stmt->close();
    } else {
        $error = "All fields are required.";
    }
}

// Fetch all book copies for display
$copies = $conn->query("SELECT * FROM Book_Copy ORDER BY Copy_ID ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Book Copy Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h2 class="mb-4">Update Book Copy Status</h2>

    <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="POST" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Select Copy</label>
                    <select name="copy_id" class="form-select" required>
                        <option value="">-- Choose Copy --</option>
                        <?php while ($copy = $copies->fetch_assoc()): ?>
                            <option value="<?= (int)$copy['Copy_ID'] ?>">
                                Copy #<?= $copy['Copy_ID'] ?> - Book ID <?= $copy['Book_ID'] ?> (<?= htmlspecialchars($copy['Availability_Status']) ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Condition Status</label>
                    <select name="condition" class="form-select" required>
                        <option value="New">New</option>
                        <option value="Good">Good</option>
                        <option value="Damaged">Damaged</option>
                        <option value="Lost">Lost</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Availability Status</label>
                    <select name="availability" class="form-select" required>
                        <option value="Available">Available</option>
                        <option value="Loaned">Loaned</option>
                        <option value="Reserved">Reserved</option>
                        <option value="Lost">Lost</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                </div>
            </form>
        </div>
    </div>

    <h4>All Book Copies</h4>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Copy ID</th>
                    <th>Book ID</th>
                    <th>Condition Status</th>
                    <th>Availability Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $copies = $conn->query("SELECT * FROM Book_Copy ORDER BY Copy_ID ASC");
                if ($copies->num_rows > 0):
                    while ($c = $copies->fetch_assoc()): ?>
                        <tr>
                            <td><?= (int)$c['Copy_ID'] ?></td>
                            <td><?= (int)$c['Book_ID'] ?></td>
                            <td><?= htmlspecialchars($c['Condition_Status']) ?></td>
                            <td><?= htmlspecialchars($c['Availability_Status']) ?></td>
                        </tr>
                    <?php endwhile;
                else: ?>
                    <tr><td colspan="4" class="text-center">No book copies found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
