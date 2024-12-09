<?php
session_start();
if (!isset($_SESSION['employee_name'])) {
    header('Location: login.php');
    exit();
}

include('db.php');

// بيانات ثابتة للاختبار
$employee_name = "Test Employee";
$car_type = "Test Car Type";
$client_name = "Test Client";
$service_category = "Test Category";
$service_type = "Test Service";
$car_number = "12345";
$phone_number = "1234567890";
$arrival_time = date('Y-m-d H:i:s');
$delivery_time = date('Y-m-d H:i:s', strtotime('+1 day'));
$total_amount = 100.0;
$coupon_code = "TESTCOUPON";
$deposit_amount = 10.0;
$installments = 3;
$notes = "This is a test note";
$order_code = uniqid('ORD-');
$coupon_discount = 5.0;
$total_after_discount = $total_amount - $coupon_discount;
$remaining_amount = $total_after_discount - $deposit_amount;

$stmt = $conn->prepare("INSERT INTO orders (employee_name, client_name, service_category, services, car_number, phone_number, arrival_time, delivery_time, total_amount, coupon_code, coupon_discount, total_after_discount, deposit_amount, remaining_amount, installments, notes, car_type, order_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
if ($stmt === false) {
    die('Error in preparing statement: ' . $conn->error);
}

$stmt->bind_param("ssssssssssddsdsiss", $employee_name, $client_name, $service_category, $service_type, $car_number, $phone_number, $arrival_time, $delivery_time, $total_amount, $coupon_code, $coupon_discount, $total_after_discount, $deposit_amount, $remaining_amount, $installments, $notes, $car_type, $order_code);

if ($stmt->execute() === false) {
    die('Error in executing statement: ' . $stmt->error);
}
$stmt->close();

echo "Test order inserted successfully.";
?>
