<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['category_name'];

    // Escape input to prevent SQL injection
    $name = mysqli_real_escape_string($conn, $name);

    $sql = "INSERT INTO Category (Category_Name) VALUES ('$name')";
    mysqli_query($conn, $sql);

    header("Location: view_categories.php");
    exit;
}
?>

