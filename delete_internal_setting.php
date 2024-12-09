<?php
include('db.php');
session_start();

if (!isset($_SESSION['employee_name'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Internal setting ID is missing.");
}

$internal_id = $_GET['id'];
$external_id = $_GET['external_id'];

$sql = "DELETE FROM internal_settings WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $internal_id);
$stmt->execute();

header("Location: manage_internal_settings.php?external_id=$external_id");
exit();
?>
