<?php
include('db.php');

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    
    // حذف المستخدم
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: users_list.php?message=User deleted successfully!");
    exit();
} else {
    die("User ID is missing.");
}
?>
