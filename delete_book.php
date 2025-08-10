<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $book_id = $_GET['id'];

    $deleteCopies = "DELETE FROM Book_Copy WHERE Book_ID = $book_id";
    mysqli_query($conn, $deleteCopies);

    
    $deleteBook = "DELETE FROM Book WHERE Book_ID = $book_id";
    mysqli_query($conn, $deleteBook);

    header("Location: view_books.php"); 
    exit;
}
?>
