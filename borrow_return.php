<?php
include 'db_connect.php';

// Borrow Book
if (isset($_POST['borrow'])) {
    $book_id = $_POST['book_id'];
    $member_id = $_POST['member_id'];
    $borrow_date = date('Y-m-d');
    $due_date = date('Y-m-d', strtotime($borrow_date . " +14 days"));

    $stmt = $conn->prepare("INSERT INTO Loan (Book_ID, Member_ID, Loan_Date, Due_Date, Status) VALUES (?, ?, ?, ?, 'Borrowed')");
    $stmt->bind_param("iiss", $book_id, $member_id, $borrow_date, $due_date);
    if ($stmt->execute()) {
        $conn->query("UPDATE Book SET Status='Borrowed' WHERE Book_ID=$book_id");
        echo "<script>alert('Book borrowed successfully!');</script>";
    }
}

// Return Book
if (isset($_POST['return'])) {
    $loan_id = $_POST['loan_id'];
    $return_date = date('Y-m-d');

    // Update Loan
    $stmt = $conn->prepare("UPDATE Loan SET Return_Date=?, Status='Returned' WHERE Loan_ID=?");
    $stmt->bind_param("si", $return_date, $loan_id);
    if ($stmt->execute()) {
        // Get Book ID and Update Status
        $bookResult = $conn->query("SELECT Book_ID FROM Loan WHERE Loan_ID=$loan_id");
        $bookRow = $bookResult->fetch_assoc();
        $conn->query("UPDATE Book SET Status='Available' WHERE Book_ID=" . $bookRow['Book_ID']);
        echo "<script>alert('Book returned successfully!');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Borrow & Return Books</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
<div class="container">
<h2>Borrow Book</h2>
<form method="POST" class="mb-4">
    <div class="mb-3">
        <label>Book ID</label>
        <input type="number" name="book_id" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Member ID</label>
        <input type="number" name="member_id" class="form-control" required>
    </div>
    <button type="submit" name="borrow" class="btn btn-primary">Borrow</button>
</form>

<h2>Return Book</h2>
<form method="POST">
    <div class="mb-3">
        <label>Loan ID</label>
        <input type="number" name="loan_id" class="form-control" required>
    </div>
    <button type="submit" name="return" class="btn btn-success">Return</button>
</form>
</div>
</body>
</html>
