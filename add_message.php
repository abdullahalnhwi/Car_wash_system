<?php
include('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id']; // يجب أن تتأكد من أن معرف المستخدم موجود في الجلسة
    $message = $_POST['message'];

    $sql = "INSERT INTO messages (user_id, message, created_at) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $message);
    $stmt->execute();
}
?>
