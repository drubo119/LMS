<?php
include 'db_connect.php';

// 1. Create table if not exists
$create_table_sql = "
CREATE TABLE IF NOT EXISTS Penalty (
    Penalty_ID INT AUTO_INCREMENT PRIMARY KEY,
    Book_Condition VARCHAR(50) UNIQUE NOT NULL,
    Amount INT NOT NULL
)";
$conn->query($create_table_sql);

// 2. Handle add/update
$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $condition = trim($_POST['book_condition'] ?? '');
    $amount    = floatval($_POST['amount'] ?? 0);

    if ($condition === '' || $amount < 0) {
        $error = "Please provide a valid condition and amount.";
    
    
    } else {
        // Check if condition already exists
        $stmt = $conn->prepare("SELECT Penalty_ID FROM Penalty WHERE Book_Condition=?");
        $stmt->bind_param("s", $condition);
        $stmt->execute();
        $exists = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($exists) {
            // Update existing
            $stmt = $conn->prepare("UPDATE Penalty SET Amount=? WHERE Penalty_ID=?");
            $stmt->bind_param("di", $amount, $exists['Penalty_ID']);
            if ($stmt->execute()) {
                $success = "Penalty updated successfully.";
            } else {
                $error = "Failed to update penalty.";
            }
            $stmt->close();
        } else {
            // Insert new
            $stmt = $conn->prepare("INSERT INTO Penalty (Book_Condition, Amount) VALUES (?, ?)");
            $stmt->bind_param("sd", $condition, $amount);
            if ($stmt->execute()) {
                $success = "Penalty added successfully.";
            } else {
                $error = "Failed to add penalty.";
            }
            $stmt->close();
        }
    }
}

// 3. Fetch all penalties
$penalties = $conn->query("SELECT * FROM Penalty ORDER BY Book_Condition ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Lost/Damaged Penalties</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4 text-center">Lost / Damaged Book Penalties</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Form to add/update -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="post">
                        <div class="mb-3">
                            <label for="book_condition" class="form-label">Book Condition</label>
                            <select name="book_condition" id="book_condition" class="form-select" required>
                                <option value="">-- Select Condition --</option>
                                <option value="Good">Good</option>
                                <option value="Damaged">Damaged</option>
                                <option value="Lost">Lost</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Penalty Amount</label>
                            <input type="number" step="0.01" min="0" name="amount" id="amount" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Save Penalty</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Display all penalties -->
    <div class="row justify-content-center">
        <div class="col-md-6">
            <table class="table table-bordered table-striped shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Condition</th>
                        <th>Amount ($)</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($row = $penalties->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['Book_Condition']) ?></td>
                        <td><?= number_format($row['Amount'], 2) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
            <div class="text-center mt-3">
                <a href="staff_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
