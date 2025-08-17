<?php
include 'penalties.php';

$penalty = null;
$condition = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $condition = $_POST['condition'] ?? 'good';
    $penalty = getPenalty($condition);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lost / Damaged Penalties</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4 text-center">Lost / Damaged Book Penalties</h2>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="post">
                        <div class="mb-3">
                            <label for="condition" class="form-label">Select Book Condition</label>
                            <select name="condition" id="condition" class="form-select" required>
                                <option value="good" <?= $condition === 'good' ? 'selected' : '' ?>>Good</option>
                                <option value="damaged" <?= $condition === 'damaged' ? 'selected' : '' ?>>Damaged</option>
                                <option value="lost" <?= $condition === 'lost' ? 'selected' : '' ?>>Lost</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Calculate Penalty</button>
                    </form>

                    <?php if ($penalty !== null): ?>
                        <div class="alert alert-info mt-4 text-center">
                            Penalty for <strong><?= htmlspecialchars(ucfirst($condition)) ?></strong> book: 
                            <strong>$<?= number_format($penalty, 2) ?></strong>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="mt-3 text-center">
                <a href="staff_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
