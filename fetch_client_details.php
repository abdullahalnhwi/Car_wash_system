<?php
include('db.php');

$car_number = $_GET['car_number'];

$response = ['success' => false];

$sql = "SELECT client_name, phone_number, car_name FROM orders WHERE car_number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $car_number);
$stmt->execute();
$stmt->bind_result($client_name, $phone_number, $car_name);

if ($stmt->fetch()) {
    $response['success'] = true;
    $response['client_name'] = $client_name;
    $response['phone_number'] = $phone_number;
    $response['car_name'] = $car_name;
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
