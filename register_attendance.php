<?php
date_default_timezone_set('Asia/Muscat'); // ضبط التوقيت لمنطقة مسقط
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];

    // الحصول على معرف المستخدم باستخدام اسم المستخدم
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();

    if ($user_id) {
        // تسجيل الحضور بالتاريخ والوقت الحاليين
        $current_time = date('Y-m-d H:i:s');
        $insertSql = "INSERT INTO attendance (user_id, check_in_time) VALUES (?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("is", $user_id, $current_time);

        if ($insertStmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Attendance recorded successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error recording attendance.']);
        }
        $insertStmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found.']);
    }
    $stmt->close();
}

$conn->close();
?>
