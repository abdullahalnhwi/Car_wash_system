<?php
include('db.php'); // تأكد من تضمين ملف الاتصال بقاعدة البيانات

if (isset($_GET['coupon_code'])) {
    $coupon_code = $_GET['coupon_code'];
    
    $stmt = $conn->prepare("SELECT discount_amount FROM coupons WHERE coupon_code = ?");
    $stmt->bind_param("s", $coupon_code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $coupon = $result->fetch_assoc();
        echo json_encode(['discount_amount' => $coupon['discount_amount']]);
    } else {
        echo json_encode(['discount_amount' => 0]);
    }
} else {
    echo json_encode(['discount_amount' => 0]);
}
?>
