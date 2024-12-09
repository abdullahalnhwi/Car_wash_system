<?php
include('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $employee_name = $_POST['employee_name'];
    $client_name = $_POST['client_name'];
    $service_category = $_POST['service_category'];
    $services = $_POST['services'];
    $car_number = $_POST['car_number'];
    $phone_number = $_POST['phone_number'];
    $arrival_time = $_POST['arrival_time'];
    $delivery_time = $_POST['delivery_time'];
    $total_amount = $_POST['total_amount'];
    $deposit_amount = $_POST['deposit_amount'];
    $coupon_code = $_POST['coupon_code'];
    $coupon_discount = $_POST['coupon_discount'];
    $total_after_discount = $_POST['total_after_discount'];
    $remaining_amount = $_POST['remaining_amount'];
    $installments = $_POST['installments'];
    $notes = $_POST['notes'];

    $sql = "UPDATE orders SET 
        employee_name = ?, 
        client_name = ?, 
        service_category = ?, 
        services = ?, 
        car_number = ?, 
        phone_number = ?, 
        arrival_time = ?, 
        delivery_time = ?, 
        total_amount = ?, 
        deposit_amount = ?, 
        coupon_code = ?, 
        coupon_discount = ?, 
        total_after_discount = ?, 
        remaining_amount = ?, 
        installments = ?, 
        notes = ?
        WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssssssi", 
        $employee_name, 
        $client_name, 
        $service_category, 
        $services, 
        $car_number, 
        $phone_number, 
        $arrival_time, 
        $delivery_time, 
        $total_amount, 
        $deposit_amount, 
        $coupon_code, 
        $coupon_discount, 
        $total_after_discount, 
        $remaining_amount, 
        $installments, 
        $notes, 
        $order_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Invoice updated successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to update invoice.";
    }

    header('Location: view_order.php?id=' . $order_id);
    exit();
} else {
    $_SESSION['error_message'] = "Invalid request.";
    header('Location: index.php');
    exit();
}
?>
