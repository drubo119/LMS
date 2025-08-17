<?php
session_start();
include 'db_connect.php';

// Detect logged-in member ID
if (isset($_SESSION['member_id'])) {
    $member_id = $_SESSION['member_id'];
} elseif (isset($_SESSION['user_id'])) {
    $member_id = $_SESSION['user_id'];  // fallback if login stored as user_id
} else {
    header("Location: user_login.php");
    exit;
}

// Fetch borrowing history for the member
$sql = "
    SELECT 
        l.Loan_ID,
        bc.Copy_ID,
        b.Title AS Book_Title,
        a.Author_Name,
        l.Loan_Date,
        l.Due_Date,
        l.Return_Date,
        bc.Condition_Status,
        l.Fine_Amount
    FROM Loan l
    JOIN Book_Copy bc ON l.Book_Copy_ID = bc.Copy_ID
    JOIN Book b ON bc.Book_ID = b.Book_ID
    LEFT JOIN Author a ON b.Author_ID = a.Author_ID
    WHERE l.Member_ID = ?
    ORDER BY l.Loan_Date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowing History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">📚 Borrowing History</h3>
        </div>
        <div class="card-body">
            <?php if ($result->num_rows > 0): ?>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Loan ID</th>
                            <th>Copy ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Loan Date</th>
                            <th>Due Date</th>
                            <th>Return Date</th>
                            <th>Condition</th>
                            <th>Fine (৳)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['Loan_ID'] ?></td>
                                <td><?= $row['Copy_ID'] ?></td>
                                <td><?= htmlspecialchars($row['Book_Title']) ?></td>
                                <td><?= htmlspecialchars($row['Author_Name'] ?? 'Unknown') ?></td>
                                <td><?= htmlspecialchars($row['Loan_Date']) ?></td>
                                <td><?= htmlspecialchars($row['Due_Date']) ?></td>
                                <td><?= $row['Return_Date'] ? htmlspecialchars($row['Return_Date']) : '<span class="badge bg-warning">Not Returned</span>' ?></td>
                                <td>
                                    <?php 
                                        $condition = $row['Condition_Status'];
                                        if (!$row['Return_Date']) {
                                            echo '<span class="badge bg-info">Borrowed</span>';
                                        } elseif ($condition == 'Damaged') {
                                            echo '<span class="badge bg-danger">Damaged</span>';
                                        } elseif ($condition == 'Lost') {
                                            echo '<span class="badge bg-dark">Lost</span>';
                                        } else {
                                            echo '<span class="badge bg-success">Good</span>';
                                        }
                                    ?>
                                </td>
                                <td><?= number_format($row['Fine_Amount'], 2) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info">No borrowing history found.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
