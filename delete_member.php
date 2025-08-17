<?php
include 'db_connect.php';

if (!isset($_GET['id'])) {
    header("Location: manage_members.php");
    exit;
}

$member_id = intval($_GET['id']);
$stmt = $conn->prepare("DELETE FROM Member WHERE Member_ID = ?");
$stmt->bind_param("i", $member_id);
$stmt->execute();

header("Location: manage_members.php");
exit;
?>
