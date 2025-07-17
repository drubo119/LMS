<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['category_name'];

    $stmt = $conn->prepare("INSERT INTO Category (Category_Name) VALUES (?)");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    header("Location: view_categories.php");
}
?>
