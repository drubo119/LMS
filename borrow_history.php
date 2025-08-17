<?php
include 'db_connect.php';

$message = "";
$history = [];

// Fetch members for dropdown
$members = $conn->query("SELECT Member_ID, Name FROM Member ORDER BY Name");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $member_id = $_POST['member_id'];

    if (!empty($member_id)) {
        // Fetch borrowing history
        $stmt = $conn->prepare("
            SELECT 
                Loan.Loan_ID,
                Book.Title,
                Loan.Loan_Date,
                Loan.Due_Date,
                Loan.Return_Date,
                CASE 
                    WHEN Loan.Return_Date IS NULL THEN 'Not Returned'
                    ELSE 'Returned'
                END AS Status,
                -- Example fine calculation
                CASE 
                    WHEN Loan.Return_Date IS NULL AND CURDATE() > Loan.Due_Date 
                        THEN DATEDIFF(CURDATE(), Loan.Due_Date) * fp.Fine_Per_Day
                    WHEN Loan.Return_Date IS NOT NULL AND Loan.Return_Date > Loan.Due_Date 
                        THEN DATEDIFF(Loan.Return_Date, Loan.Due_Date) * fp.Fine_Per_Day
                    ELSE 0
                END AS Fine_Amount
            FROM Loan
            JOIN Book_Copy bc ON Loan.Copy_ID = bc.Copy_ID
            JOIN Book ON bc.Book_ID = Book.Book_ID
            JOIN Member m ON Loan.Member_ID = m.Member_ID
            LEFT JOIN Fine_Policy fp ON fp.Membership_Type = m.Membership_Type
            WHERE Loan.Member_ID = ?
            ORDER BY Loan.Loan_Date DESC
        ");
        $stmt->bind_param("i", $member_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $history = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $message = "<div class='alert alert-warning'>Please select a member.</div>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Borrowing History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h2 class="mb-4">Borrowing History</h2>
    <?= $message ?>

    <!-- Member Selection Form -->
    <form method="POST" class="card p-4 mb-4">
        <div class="mb-3">
            <label class="form-label">Select Member</label>
            <select name="member_id" class="form-control" required>
                <option value="">-- Choose Member --</option>
                <?php while ($m = $members->fetch_assoc()): ?>
                    <option value="<?= $m['Member_ID'] ?>" <?= isset($_POST['member_id']) && $_POST['member_id'] == $m['Member_ID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($m['Name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">View History</button>
    </form>

    <!-- Borrowing History Table -->
    <?php if (!empty($history)): ?>
        <div class="card p-4">
            <h4>Borrowing Records</h4>
            <table class="table table-bordered table-striped mt-3">
                <thead>
                    <tr>
                        <th>Loan ID</th>
                        <th>Book Title</th>
                        <th>Loan Date</th>
                        <th>Due Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Fine Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $row): ?>
                        <tr>
                            <td><?= $row['Loan_ID'] ?></td>
                            <td><?= htmlspecialchars($row['Title']) ?></td>
                            <td><?= $row['Loan_Date'] ?></td>
                            <td><?= $row['Due_Date'] ?></td>
                            <td><?= $row['Return_Date'] ?? '-' ?></td>
                            <td><?= $row['Status'] ?></td>
                            <td><?= number_format($row['Fine_Amount'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
        <div class="alert alert-info">No borrowing history found for this member.</div>
    <?php endif; ?>
</div>
</body>
</html>




