<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_name = $_POST['employee_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    
    // Handle image upload
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $image_path = $upload_dir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }
    
    // Update user information
    $update_sql = "UPDATE users SET 
                   employee_name = ?, 
                   email = ?, 
                   phone = ?";
    $params = [$employee_name, $email, $phone];
    $types = "sss";
    
    if ($image_path) {
        $update_sql .= ", image = ?";
        $params[] = $image_path;
        $types .= "s";
    }
    
    $update_sql .= " WHERE id = ?";
    $params[] = $user_id;
    $types .= "i";
    
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        $_SESSION['employee_name'] = $employee_name;
        if ($image_path) {
            $_SESSION['image'] = $image_path;
        }
        header('Location: customer_profile.php?msg=profile_updated');
    } else {
        header('Location: customer_profile.php?error=update_failed');
    }
    exit();
}
?> 