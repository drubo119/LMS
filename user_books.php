<?php
include 'db_connect.php';

// Fetch only books that have at least one available copy
$sql = "SELECT DISTINCT b.Title, b.Image_URL
        FROM Book b
        JOIN Book_Copy bc ON bc.Book_ID = b.Book_ID
        WHERE bc.Availability_Status = 'Available'";

$result = mysqli_query($conn, $sql);

$books = [];
while ($row = mysqli_fetch_assoc($result)) {
    $books[] = [
        'title' => $row['Title'],
        'image' => $row['Image_URL'] ?: 'photos/no-image.jpg',
        'available' => true   // Since filtered by availability, it's always true
    ];
}
?>

