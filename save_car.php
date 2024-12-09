<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get employee_id from session
    $employee_id = $_SESSION['user_id'];
    $car_number = $_POST['car_number'];
    $car_name = $_POST['car_name'];
    $car_type = $_POST['car_type'];
    $client_name = $_POST['client_name'];
    $phone_number = $_POST['phone_number'];

    // Check if car already exists
    $check_sql = "SELECT id FROM client_cars WHERE car_number = ?";
    $check_stmt = $conn->prepare($check_sql);
    if (!$check_stmt) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
        exit;
    }
    
    $check_stmt->bind_param("s", $car_number);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Car number already exists']);
        exit;
    }

    // Insert new car with all fields
    $sql = "INSERT INTO client_cars (employee_id, car_number, car_name, car_type, client_name, phone) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("isssss", $employee_id, $car_number, $car_name, $car_type, $client_name, $phone_number);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Car saved successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error saving car: ' . $stmt->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
} 