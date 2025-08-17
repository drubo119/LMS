<?php
// calculate_fines_page.php
include 'db_connect.php';

// Define fixed penalties
define('LOST_PENALTY_AMOUNT', 50.00);
define('DAMAGED_PENALTY_AMOUNT', 20.00);

/**
 * Get penalty based on return condition
 */
function getPenalty($condition) {
    switch (strtolower($condition)) {
        case 'lost': return LOST_PENALTY_AMOUNT;
        case 'damaged': return DAMAGED_PENALTY_AMOUNT;
        case 'good':
        default: return 0.0;
    }
}

// Function to calculate fine for a loan
function calculateFine($loan_id, $conn) {
    $sql = "SELECT l.Loan_ID, l.Member_ID, l.Due_Date, l.Return_Date, l.Condition_Status,
                   m.Name, m.Membership_Type, f.Fine_Per_Day, f.Max_Fine
            FROM Loan l
            JOIN Member m ON l.Member_ID = m.Member_ID
            JOIN Fine_Policy f ON m.Membership_Type = f.Membership_Type
            WHERE l.Loan_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $loan_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $due_date = new DateTime($row['Due_Date']);
        $return_date = $row['Return_Date'] ? new DateTime($row['Return_Date']) : new DateTime();

        // 1. Calculate late fine
        $fine = 0;
        if ($return_date > $due_date) {
            $overdue_days = $due_date->diff($return_date)->days;
            $fine = $overdue_days * $row['Fine_Per_Day'];
            if ($fine > $row['Max_Fine']) $fine = $row['Max_Fine'];
        }

        // 2. Add lost/damaged penalty
        $condition = $row['Condition_Status'] ?? 'good';
        $fine += getPenalty($condition);

        // Update Loan table
        $update = $conn->prepare("UPDATE Loan SET Fine_Amount = ? WHERE Loan_ID = ?");
        $update->bind_param("di", $fine, $loan_id);
        $update->execute();

        return $fine;
    }
    return false;
}

// Auto update fines for all loans
$loans = $conn->query("SELECT Loan_ID FROM Loan");
while ($loan = $loans->fetch_assoc()) {
    calculateFine($loan['Loan_ID'], $conn);
}

// Fetch loans with member info to display
$sql = "SELECT l.Loan_ID, l.Book_Copy_ID, l.Return_Date, l.Fine_Amount, l.Condition_Status,
               m.Name, m.Membership_Type, b.Title AS BookTitle
        FROM Loan l
        JOIN Member m ON l.Member_ID = m.Member_ID
        JOIN Book_Copy bc ON l.Book_Copy_ID = bc.Copy_ID
        JOIN Book b ON bc.Book_ID = b.Book_ID
        ORDER BY l.Loan_ID DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Fines</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4 text-center">📚 Manage Fines</h2>

    <table class="table table-bordered table-striped table-hover shadow">
        <thead class="table-dark">
            <tr>
                <th>Loan ID</th>
                <th>Member</th>
                <th>Type</th>
                <th>Book</th>
                <th>Copy ID</th>
                <th>Condition</th>
                <th>Return Date</th>
                <th>Fine Amount</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['Loan_ID'] ?></td>
                <td><?= htmlspecialchars($row['Name']) ?></td>
                <td><?= $row['Membership_Type'] ?></td>
                <td><?= htmlspecialchars($row['BookTitle']) ?></td>
                <td><?= $row['Book_Copy_ID'] ?></td>
                <td><?= ucfirst($row['Condition_Status'] ?? 'good') ?></td>
                <td><?= $row['Return_Date'] ?? '<span class="badge bg-warning">Not Returned</span>' ?></td>
                <td>
                    <?php if ($row['Fine_Amount'] > 0): ?>
                        <span class="badge bg-danger">৳ <?= number_format($row['Fine_Amount'], 2) ?></span>
                    <?php else: ?>
                        <span class="badge bg-success">No Fine</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <div class="mt-3">
        <a href="staff_dashboards.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>
</body>
</html>
