<?php
session_start();
include 'db_connect.php';

// --- Staff login check ---
if (!isset($_SESSION['staff_id'])) {
    die("Access denied! Staff must be logged in.");
}

// --- Handle Approve/Cancel Action ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'], $_POST['action'])) {
    $reservation_id = intval($_POST['reservation_id']);
    $action = $_POST['action'];
    $staff_id = $_SESSION['staff_id'];

    if ($action === 'approve') {
        $status = "Reserved";
    } elseif ($action === 'cancel') {
        $status = "Cancelled";
    } else {
        $status = "Pending";
    }

    $stmt = $conn->prepare("UPDATE Reservation SET Status = ?, Staff_ID = ? WHERE Reservation_ID = ?");
    $stmt->bind_param("sii", $status, $staff_id, $reservation_id);
    $stmt->execute();
}

// --- Fetch all reservations (Pending, Reserved, Cancelled) ---
$sql = "SELECT r.Reservation_ID, r.Reservation_Date, r.Status,
               m.Name AS MemberName, b.Title AS BookTitle
        FROM Reservation r
        JOIN Member m ON r.Member_ID = m.Member_ID
        JOIN Book b ON r.Book_ID = b.Book_ID
        ORDER BY r.Reservation_Date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Reservations</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
  <h2 class="mb-4">Manage Book Reservations</h2>

  <?php if ($result->num_rows > 0) { ?>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Reservation ID</th>
          <th>Book</th>
          <th>Member</th>
          <th>Date</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
          <tr>
            <td><?= $row['Reservation_ID'] ?></td>
            <td><?= htmlspecialchars($row['BookTitle']) ?></td>
            <td><?= htmlspecialchars($row['MemberName']) ?></td>
            <td><?= $row['Reservation_Date'] ?></td>
            <td>
              <span class="badge 
                <?= $row['Status'] === 'Pending' ? 'bg-warning' : ($row['Status'] === 'Reserved' ? 'bg-success' : 'bg-danger') ?>">
                <?= $row['Status'] ?>
              </span>
            </td>
            <td>
              <?php if ($row['Status'] === 'Pending') { ?>
                <form method="post" style="display:inline;">
                  <input type="hidden" name="reservation_id" value="<?= $row['Reservation_ID'] ?>">
                  <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                  <button type="submit" name="action" value="cancel" class="btn btn-danger btn-sm">Cancel</button>
                </form>
              <?php } else { ?>
                <em>No action</em>
              <?php } ?>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  <?php } else { ?>
    <p>No reservations found.</p>
  <?php } ?>

</body>
</html>
