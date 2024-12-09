<?php
include('db.php');
session_start();

if (!isset($_SESSION['employee_name'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $order_id = $_GET['id'];
    $new_status = $_GET['status'];

    // تحديث حالة الطلب في قاعدة البيانات
    $sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $stmt->bind_param("si", $new_status, $order_id);
    if (!$stmt->execute()) {
        die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }
    $stmt->close();

    // جلب معلومات الطلب
    $sql_order = "SELECT client_name, phone_number, car_type, total_amount, delivery_time FROM orders WHERE id = ?";
    $stmt_order = $conn->prepare($sql_order);
    if (!$stmt_order) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $stmt_order->bind_param("i", $order_id);
    if (!$stmt_order->execute()) {
        die("Execute failed: (" . $stmt_order->errno . ") " . $stmt_order->error);
    }
    $stmt_order->bind_result($client_name, $phone_number, $car_type, $total_amount, $delivery_time);
    $stmt_order->fetch();
    $stmt_order->close();

    // تحويل رقم الهاتف إلى صيغة دولية إذا لم يكن يحتوي على المفتاح الدولي
    if (strpos($phone_number, '+968') !== 0) {
        $phone_number = '+968' . ltrim($phone_number, '0');
    }

    // إعداد محتوى الرسالة بناءً على الحالة الجديدة
    if ($new_status == 'completed') {
        $status_message = 'تم الانتهاء من العمل على سيارتك. يرجى الحضور لاستلامها.';
    } else if ($new_status == 'canceled') {
        $status_message = 'تم إلغاء طلبك. يرجى الاتصال بنا لمزيد من المعلومات.';
    }

    // رسالة العميل
    $customer_message_body = "
شكرا لك على اختيارك TOP LEVEL CAR CARE CENTER
$status_message
رقم الطلب: $order_id
اسم العميل: $client_name
تاريخ الاستلام: $delivery_time
نوع السيارة: $car_type
سعر الباقة: $total_amount OMR
";

    // إعدادات واجهة برمجة التطبيقات
    $api_url = 'https://www.2whats.com/api/send';
    $mobile = '96871122755';
    $password = 'Nn71122755';
    $instanceid = '234642';
    $json = '1';
    $type = '1';

    // إرسال الرسالة إلى العميل
    $numbers = $phone_number;
    $message = $customer_message_body;
    $url = "$api_url?mobile=$mobile&password=$password&instanceid=$instanceid&message=" . urlencode($message) . "&numbers=$numbers&json=$json&type=$type";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Customer Error:' . curl_error($ch);
    } else {
        echo 'Customer Response:' . $response;
    }
    curl_close($ch);

    // إرسال الرسالة إلى المدير
    $manager_phone_number = '+96871122755'; // استبدل برقم المدير الصحيح
    $manager_message_body = "
تم تغيير حالة الطلب
رقم الطلب: $order_id
اسم العميل: $client_name
رقم الهاتف: $phone_number
تاريخ الاستلام: $delivery_time
نوع السيارة: $car_type
سعر الباقة: $total_amount OMR
الحالة الجديدة: $new_status
";
    $numbers = $manager_phone_number;
    $message = $manager_message_body;
    $url = "$api_url?mobile=$mobile&password=$password&instanceid=$instanceid&message=" . urlencode($message) . "&numbers=$numbers&json=$json&type=$type";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Manager Error:' . curl_error($ch);
    } else {
        echo 'Manager Response:' . $response;
    }
    curl_close($ch);

    // إعادة التوجيه إلى صفحة إدارة الطلبات بعد التحديث
    header('Location: manage_order.php');
    exit();
}
?>
