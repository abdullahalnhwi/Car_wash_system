<?php
include('db.php');
session_start();

if (!isset($_SESSION['employee_name'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("External setting ID is missing.");
}

$external_id = $_GET['id'];

$sql = "DELETE FROM external_settings WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $external_id);
$stmt->execute();

header('Location: dashboard.php');
exit();
?>
