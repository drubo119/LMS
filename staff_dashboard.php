<?php
session_start();
include 'config/db.php';

// Ensure only staff can access
if (!isset($_SESSION['staff_id'])) {
    header("Location: staff_login.php");
    exit();
}

// Handle Approve/Cancel actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $reservation_id = intval($_GET['id']);
    $action = $_GET['action'];
    $staff_id = $_SESSION['staff_id'];

    if ($action == "approve") {
        // Get reservation details
        $res_sql = "SELECT * FROM Reservation WHERE Reservation_ID = ?";
        $stmt = $conn->prepare($res_sql);
        $stmt->bind_param("i", $reservation_id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($res && $res['Status'] == 'Pending') {
            $book_id = $res['Book_ID'];
            $member_id = $res['Member_ID'];

            // 1. Update Reservation status
            $update_sql = "UPDATE Reservation SET Status='Reserved', Staff_ID=? WHERE Reservation_ID=?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("ii", $staff_id, $reservation_id);
            $stmt->execute();
            $stmt->close();

            // 2. Add Loan entry
            $loan_date = date("Y-m-d");
            $due_date = date("Y-m-d", strtotime("+14 days"));

            // For now assume Book_ID is same as Book_Copy_ID (you can adjust if you manage multiple copies separately)
            $insert_loan = "INSERT INTO Loan (Book_Copy_ID, Member_ID, Loan_Date, Due_Date, Return_Date, Fine_Amount)
                            VALUES (?, ?, ?, ?, NULL, 0.00)";
            $stmt = $conn->prepare($insert_loan);
            $stmt->bind_param("iiss", $book_id, $member_id, $loan_date, $due_date);
            $stmt->execute();
            $stmt->close();
        }

    } elseif ($action == "cancel") {
        $update_sql = "UPDATE Reservation SET Status='Cancelled', Staff_ID=? WHERE Reservation_ID=?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ii", $staff_id, $reservation_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: staff_manage_reservations.php");
    exit();
}

// Fetch all reservations
$sql = "SELECT r.Reservation_ID, r.Book_ID, r.Member_ID, r.Reservation_Date, r.Status, 
               m.Name AS MemberName, b.Title AS BookTitle
        FROM Reservation r
        JOIN Member m ON r.Member_ID = m.Member_ID
        JOIN Book b ON r.Book_ID = b.Book_ID
        ORDER BY r.Reservation_Date DESC";

$reservations = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Reservations</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h2>Reservation Requests</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Book</th>
                <th>Member</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $reservations->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['Reservation_ID'] ?></td>
                <td><?= htmlspecialchars($row['BookTitle']) ?></td>
                <td><?= htmlspecialchars($row['MemberName']) ?></td>
                <td><?= $row['Reservation_Date'] ?></td>
                <td><?= $row['Status'] ?></td>
                <td>
                    <?php if ($row['Status'] == 'Pending') { ?>
                        <a href="?action=approve&id=<?= $row['Reservation_ID'] ?>" class="btn btn-success btn-sm">Approve</a>
                        <a href="?action=cancel&id=<?= $row['Reservation_ID'] ?>" class="btn btn-danger btn-sm">Cancel</a>
                    <?php } else { ?>
                        <span class="badge bg-secondary">No Action</span>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</body>
</html>
