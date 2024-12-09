<?php
include('db.php');
session_start();

if (!isset($_SESSION['employee_name'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $setting_id = $_GET['id'];

    $sql = "DELETE FROM settings WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $setting_id);
    $stmt->execute();
    
    header('Location: settings.php');
    exit();
} else {
    header('Location: settings.php');
    exit();
}
?>
