<?php
session_start();
include 'db_connect.php';

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must log in first.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Get Book ID from POST
if (!isset($_POST['book_id'])) {
    echo "Invalid request.";
    exit;
}
$book_id = intval($_POST['book_id']);

// Check if user is a member
$member_query = $conn->prepare("SELECT Member_ID FROM Member WHERE Email = (SELECT email FROM users WHERE id = ?)");
$member_query->bind_param("i", $user_id);
$member_query->execute();
$member_result = $member_query->get_result();

if ($member_result->num_rows === 0) {
    echo "Only members can reserve books. Please complete your profile.";
    exit;
}

$member = $member_result->fetch_assoc();
$member_id = $member['Member_ID'];

// Check if already reserved
$check = $conn->prepare("SELECT * FROM Reservation WHERE Book_ID = ? AND Member_ID = ?");
$check->bind_param("ii", $book_id, $member_id);
$check->execute();
$check_result = $check->get_result();

if ($check_result->num_rows > 0) {
    echo "You already reserved this book.";
    exit;
}

// Insert with date + pending status
$reserve_query = $conn->prepare(
    "INSERT INTO Reservation (Book_ID, Member_ID, Reservation_Date, Status) 
     VALUES (?, ?, CURDATE(), 'pending')"
);
$reserve_query->bind_param("ii", $book_id, $member_id);

if ($reserve_query->execute()) {
    echo "Book reserved successfully on " . date("Y-m-d");
} else {
    echo "Error: " . $conn->error;
}
?>


