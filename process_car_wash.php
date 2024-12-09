<?php
include('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // جمع بيانات النموذج
    $car_type = $_POST['car_type'];
    $employee_name = $_POST['employee_name'];
    $client_name = $_POST['client_name'];
    $service_category_id = $_POST['service_category'];
    $services = isset($_POST['service_type']) ? implode(',', $_POST['service_type']) : '';
    $car_number = $_POST['car_number'];
    $phone_number = $_POST['phone_number'];
    $arrival_time = $_POST['arrival_time'];
    $delivery_time = $_POST['delivery_time'];
    $total_amount = floatval($_POST['total_amount']);
    $coupon_code = $_POST['coupon_code'];
    $total_after_discount = floatval($_POST['total_after_discount']);
    $deposit_amount = isset($_POST['deposit_amount']) ? floatval($_POST['deposit_amount']) : 0;
    $remaining_amount = floatval($_POST['remaining_amount']);
    $notes = $_POST['notes'];
    $extra_service_name = isset($_POST['extra_service_name']) ? $_POST['extra_service_name'] : '';
    $extra_service_price = isset($_POST['extra_service_price']) ? $_POST['extra_service_price'] : '';
    $car_name = $_POST['car_name'];

    // إنشاء رمز طلب فريد
    $order_code = uniqid('order_');

    // التحقق من رمز القسيمة وحساب الخصم
    $coupon_discount = 0;
    if (!empty($coupon_code)) {
        $sql = "SELECT discount_amount FROM coupons WHERE coupon_code = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $coupon_code);
        $stmt->execute();
        $stmt->bind_result($coupon_discount);
        $stmt->fetch();
        $stmt->close();
    }

    // إدخال بيانات الطلب في قاعدة البيانات
    $sql = "INSERT INTO orders (car_type, employee_name, client_name, service_category, services, car_number, phone_number, arrival_time, delivery_time, total_amount, coupon_code, total_after_discount, coupon_discount, deposit_amount, remaining_amount, notes, order_code, car_name, extra_service_name, extra_service_price) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('prepare() failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param('ssssssssssssddssssss', $car_type, $employee_name, $client_name, $service_category_id, $services, $car_number, $phone_number, $arrival_time, $delivery_time, $total_amount, $coupon_code, $total_after_discount, $coupon_discount, $deposit_amount, $remaining_amount, $notes, $order_code, $car_name, $extra_service_name, $extra_service_price);

    if ($stmt->execute()) {
        // إنشاء رابط الفاتورة
        $invoice_url = "https://toplevelom.com/view_order.php?id=" . $stmt->insert_id;

        // جلب تفاصيل الخدمة واسم الباقة
        $service_details = getServiceDetails($services);
        $package_name = getPackageName($service_category_id);

        // إنشاء رسالة العميل
        $customer_message = "شكرا لك على اختيارك TOP LEVEL CAR CARE CENTER\n";
        $customer_message .= "سوف نهتم بسيارتك و نجعلها تبدو لامعة. ثق بنا\n";
        $customer_message .= "سوف نتواصل معك عندما ننتهي من العمل عليها\n";
        $customer_message .= "رقم الطلب: $order_code\n";
        $customer_message .= "اسم العميل: $client_name\n";
        $customer_message .= "تاريخ الوصول: " . formatDateTime($arrival_time) . "\n";
        $customer_message .= "تاريخ الاستلام: " . formatDateTime($delivery_time) . "\n";
        $customer_message .= "نوع السيارة: $car_type\n";
        $customer_message .= "ما الباقة التي تم اختيارها للخدمة: $package_name\n";
        $customer_message .= "الخدمات المقدمة: $service_details\n";
        $customer_message .= "سعر الباقة: " . number_format($total_amount, 3) . " OMR\n";
        $customer_message .= "اسم السيارة: $car_name\n";

        if (!empty($extra_service_name) && !empty($extra_service_price)) {
            $customer_message .= "الخدمات الإضافية: ";
            foreach ($extra_service_name as $index => $service_name) {
                $customer_message .= "$service_name (" . number_format($extra_service_price[$index], 3) . " OMR), ";
            }
            $customer_message = rtrim($customer_message, ', ') . "\n";
        }

        if (!empty($coupon_code)) {
            $customer_message .= "تم استخدام كوبون الخصم: $coupon_code\n";
            $customer_message .= "قيمة الخصم: " . number_format($coupon_discount, 3) . " OMR\n";
        }

        $customer_message .= "السعر الإجمالي: " . number_format($total_after_discount, 3) . " OMR\n";
        $customer_message .= "عدد الخدمات المقدمة لك: " . count(explode(',', $services)) . "\n";
        $customer_message .= "رابط الفاتورة: $invoice_url\n";

        // إرسال رسالة WhatsApp للعميل
        $response = sendWhatsAppMessage($phone_number, $customer_message);

        if ($response['success']) {
            error_log("WhatsApp message sent to customer: $phone_number");
        } else {
            error_log("Failed to send WhatsApp message to customer: $phone_number. Error: " . $response['message']);
        }

        // إرسال الرسالة إلى المديرين
        $manager_numbers = ['96896686003', '96897176116','96685996']; // إضافة أرقام المديرين هنا
        foreach ($manager_numbers as $manager_number) {
            $response = sendWhatsAppMessage($manager_number, $customer_message);
            if ($response['success']) {
                error_log("WhatsApp message sent to manager: $manager_number");
            } else {
                error_log("Failed to send WhatsApp message to manager: $manager_number. Error: " . $response['message']);
            }
        }

        $_SESSION['success_message'] = "تم إنشاء الطلب بنجاح!";
        header('Location: index.php');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
}

$conn->close();

// دالة لتنسيق التاريخ والوقت
function formatDateTime($datetime) {
    $daysOfWeek = [
        'Sunday' => 'الأحد',
        'Monday' => 'الإثنين',
        'Tuesday' => 'الثلاثاء',
        'Wednesday' => 'الأربعاء',
        'Thursday' => 'الخميس',
        'Friday' => 'الجمعة',
        'Saturday' => 'السبت'
    ];

    $date = new DateTime($datetime);
    $dayName = $daysOfWeek[$date->format('l')];
    return $dayName . ', ' . $date->format('Y-m-d h:i:s A');
}

// دالة لجلب تفاصيل الخدمة
function getServiceDetails($services) {
    global $conn;
    $service_names = explode(',', $services);
    $details = [];

    foreach ($service_names as $service_name) {
        $sql = "SELECT name, price FROM services WHERE name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $service_name);
        $stmt->execute();
        $stmt->bind_result($name, $price);
        if ($stmt->fetch()) {
            $details[] = "$name (" . number_format($price, 3) . " OMR)";
        }
        $stmt->close();
    }

    return implode(', ', $details);
}

// دالة لجلب اسم الباقة
function getPackageName($package_id) {
    global $conn;
    $sql = "SELECT name FROM categories WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $package_id);
    $stmt->execute();
    $stmt->bind_result($name);
    $stmt->fetch();
    $stmt->close();
    return $name;
}

// دالة لإرسال رسالة WhatsApp باستخدام Core Code API
function sendWhatsAppMessage($numbers, $message) {
    $api_key = 'a32b8ed9f9c24aceab6e9265935bfdeb22c4274bb26d4c6797';

    // التأكد من أن رقم الهاتف يبدأ برمز الدولة (968)
    if (strpos($numbers, '968') !== 0) {
        $numbers = '968' . $numbers;
    }

    // رابط API
    $url = 'https://corecoode.com/wapi/sendMessageAPI.php';

    // بيانات POST
    $data = [
        'api_key' => $api_key,
        'to' => $numbers,
        'msg' => $message,
        'file' => 'no'
    ];

    // تهيئة cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}
?>
