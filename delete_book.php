<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $book_id = $_GET['id'];

    // First delete all book copies that reference this book
    $deleteCopies = "DELETE FROM Book_Copy WHERE Book_ID = $book_id";
    mysqli_query($conn, $deleteCopies);

    // Now delete the book
    $deleteBook = "DELETE FROM Book WHERE Book_ID = $book_id";
    mysqli_query($conn, $deleteBook);

    header("Location: view_books.php"); // fallback to main page or dashboard

    exit;
}
?>
