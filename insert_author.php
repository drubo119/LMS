<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['author_name'];
    $nationality = $_POST['nationality'];

    
    $name = mysqli_real_escape_string($conn, $name);
    $nationality = mysqli_real_escape_string($conn, $nationality);

    $sql = "INSERT INTO Author (Author_Name, Nationality) VALUES ('$name', '$nationality')";
    mysqli_query($conn, $sql);

    header("Location: view_authors.php");
    exit;
}
?>

