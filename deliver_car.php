<?php
include('db.php');
session_start();

if (!isset($_SESSION['employee_name'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
    $sql = "UPDATE orders SET car_delivered = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->close();
}

header('Location: pending.php');
exit();
?>
