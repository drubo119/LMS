<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['author_name'];
    $nationality = $_POST['nationality'];

    $stmt = $conn->prepare("INSERT INTO Author (Author_Name, Nationality) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $nationality);
    $stmt->execute();
    header("Location: view_authors.php");
}
?>
