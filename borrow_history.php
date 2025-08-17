<?php
// admin_borrowing_history.php
session_start();
include 'config/db.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch borrowing history
$sql = "SELECT 
            l.Loan_ID,
            m.Name AS MemberName,
            m.Membership_Type,
            b.Title AS BookTitle,
            l.Loan_Date,
            l.Due_Date,
            l.Return_Date,
            l.Fine_Amount,
            CASE
                WHEN l.Return_Date IS NULL AND CURDATE() > l.Due_Date THEN 'Overdue'
                WHEN l.Return_Date > l.Due_Date THEN 'Returned Late'
                WHEN l.Return_Date <= l.Due_Date THEN 'Returned On Time'
                ELSE 'Borrowed'
            END AS Status
        FROM Loan l
        JOIN Member m ON l.Member_ID = m.Member_ID
        JOIN Book_Copy bc ON l.Book_Copy_ID = bc.Copy_ID
        JOIN Book b ON bc.Book_ID = b.Book_ID
        ORDER BY m.Name, l.Loan_Date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Borrowing History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h2>Borrowing History</h2>

    <div class="card shadow-sm mt-3">
        <div class="table-responsive">
            <table class="table table-striped table-bordered mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Loan ID</th>
                        <th>Member</th>
                        <th>Type</th>
                        <th>Book</th>
                        <th>Loan Date</th>
                        <th>Due Date</th>
                        <th>Return Date</th>
                        <th>Fine</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= (int)$row['Loan_ID'] ?></td>
                            <td><?= htmlspecialchars($row['MemberName']) ?></td>
                            <td><?= htmlspecialchars($row['Membership_Type']) ?></td>
                            <td><?= htmlspecialchars($row['BookTitle']) ?></td>
                            <td><?= htmlspecialchars($row['Loan_Date']) ?></td>
                            <td><?= htmlspecialchars($row['Due_Date']) ?></td>
                            <td><?= htmlspecialchars($row['Return_Date'] ?? '-') ?></td>
                            <td>$<?= number_format($row['Fine_Amount'], 2) ?></td>
                            <td><?= htmlspecialchars($row['Status']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="9" class="text-center py-3">No borrowing records found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>
</body>
</html>
