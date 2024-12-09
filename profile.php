<?php
include('db.php');
session_start();

if (!isset($_SESSION['employee_name'])) {
    header('Location: login.php');
    exit();
}

$employee_id = $_SESSION['user_id'];
$employee_name = $_SESSION['employee_name'];
$employee_image = $_SESSION['image'];

// التحقق من وجود استعلام بحث
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
    $sql_summary = "SELECT COUNT(*) as transaction_count, SUM(total_amount) as total_amount FROM orders WHERE employee_name = ? AND (client_name LIKE ? OR car_number LIKE ? OR phone_number LIKE ? OR order_code LIKE ? OR id = ?)";
    $search_param = '%' . $search_query . '%';
    $stmt_summary = $conn->prepare($sql_summary);
    $stmt_summary->bind_param("sssssi", $employee_name, $search_param, $search_param, $search_param, $search_param, $search_query);
} else {
    $sql_summary = "SELECT COUNT(*) as transaction_count, SUM(total_amount) as total_amount FROM orders WHERE employee_name = ?";
    $stmt_summary = $conn->prepare($sql_summary);
    $stmt_summary->bind_param("s", $employee_name);
}

$stmt_summary->execute();
$stmt_summary->bind_result($transaction_count, $total_amount);
$stmt_summary->fetch();
$stmt_summary->close();

// جلب تفاصيل المعاملات وترتيبها تنازلياً حسب تاريخ الوصول
if (isset($search_query) && !empty($search_query)) {
    $sql_transactions = "SELECT id, order_code, delivery_time, total_amount, status, car_delivered FROM orders WHERE employee_name = ? AND (client_name LIKE ? OR car_number LIKE ? OR phone_number LIKE ? OR order_code LIKE ? OR id = ?) ORDER BY arrival_time DESC";
    $stmt_transactions = $conn->prepare($sql_transactions);
    $stmt_transactions->bind_param("sssssi", $employee_name, $search_param, $search_param, $search_param, $search_param, $search_query);
} else {
    $sql_transactions = "SELECT id, order_code, delivery_time, total_amount, status, car_delivered FROM orders WHERE employee_name = ? ORDER BY arrival_time DESC";
    $stmt_transactions = $conn->prepare($sql_transactions);
    $stmt_transactions->bind_param("s", $employee_name);
}

$stmt_transactions->execute();
$result_transactions = $stmt_transactions->get_result();
$stmt_transactions->close();

// دالة لإرسال رسالة واتساب باستخدام Core Code API
function sendWhatsAppMessage($numbers, $message) {
    $api_key = 'a32b8ed9f9c24aceab6e9265935bfdeb22c4274bb26d4c6797'; // تأكد من استخدام مفتاح API الصحيح

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
        'file' => 'no' // إذا كنت لا ترسل ملفات، فاضبط القيمة على 'no'
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
    $dayName = $daysOfWeek[$date->format('l')]; // الحصول على اليوم من الأسبوع بالعربية
    return $dayName . ', ' . $date->format('Y-m-d h:i:s A');
}

// تحديث حالة تسليم السيارة أو الطلب
if (isset($_GET['action']) && isset($_GET['id'])) {
    $order_id = $_GET['id'];
    $action = $_GET['action'];

    // جلب تفاصيل الطلب
    $sql_get_details = "SELECT client_name, phone_number, car_number, car_type, arrival_time, delivery_time, total_amount FROM orders WHERE id = ?";
    $stmt_get_details = $conn->prepare($sql_get_details);
    $stmt_get_details->bind_param('i', $order_id);
    $stmt_get_details->execute();
    $stmt_get_details->bind_result($client_name, $client_phone, $car_number, $car_type, $arrival_time, $delivery_time, $total_amount);
    $stmt_get_details->fetch();
    $stmt_get_details->close();

    // أرقام هواتف المدراء
    $manager_phones = ['96896685996', '96897176116', '96896686003']; // أضف أرقام الهواتف هنا

    if ($action == 'deliver') {
        // ضبط التوقيت لمنطقة سلطنة عمان
        $timezone = new DateTimeZone('Asia/Muscat');
        $current_time = new DateTime('now', $timezone);
        $formatted_time = $current_time->format('Y-m-d H:i:s');

        $sql_update = "UPDATE orders SET car_delivered = 1, car_delivery_time = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param('si', $formatted_time, $order_id);

        if ($stmt_update->execute()) {
            echo "Order status updated to delivered"; // رسالة تصحيح
            $customer_message = "شكرا لك على اختيارك TOP LEVEL CAR CARE CENTER\n";
            $customer_message .= "تم تسليم سيارتك بنجاح.\n";
            $customer_message .= "رقم الطلب: $order_id\n";
            $customer_message .= "اسم العميل: $client_name\n";
            $customer_message .= "تاريخ الاستلام: " . formatDateTime($formatted_time) . "\n";
            $customer_message .= "نوع السيارة: $car_type\n";
            $customer_message .= "سعر الباقة: " . number_format($total_amount, 3) . " OMR\n";

            $manager_message = "تم تغيير حالة الطلب\n";
            $manager_message .= "رقم الطلب: $order_id\n";
            $manager_message .= "اسم العميل: $client_name\n";
            $manager_message .= "رقم الهاتف: +968" . $client_phone . "\n";
            $manager_message .= "تاريخ الاستلام: " . formatDateTime($formatted_time) . "\n";
            $manager_message .= "نوع السيارة: $car_type\n";
            $manager_message .= "سعر الباقة: " . number_format($total_amount, 3) . " OMR\n";
            $manager_message .= "الحالة الجديدة: تم تسليم السيارة\n";

            sendWhatsAppMessage($client_phone, $customer_message);
            echo "Customer message sent"; // رسالة تصحيح
            foreach ($manager_phones as $manager_phone) {
                sendWhatsAppMessage($manager_phone, $manager_message);
            }
            echo "Manager message sent"; // رسالة تصحيح
        } else {
            echo "Failed to update order status"; // رسالة تصحيح
        }
    } elseif ($action == 'complete') {
        $sql_update = "UPDATE orders SET status = 'completed' WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param('i', $order_id);

        if ($stmt_update->execute()) {
            echo "Order status updated to completed"; // رسالة تصحيح
            $customer_message = "شكرا لك على اختيارك TOP LEVEL CAR CARE CENTER\n";
            $customer_message .= "تم الانتهاء من العمل على سيارتك. يرجى الحضور لاستلامها.\n";
            $customer_message .= "رقم الطلب: $order_id\n";
            $customer_message .= "اسم العميل: $client_name\n";
            $customer_message .= "تاريخ الاستلام: " . formatDateTime($delivery_time) . "\n";
            $customer_message .= "نوع السيارة: $car_type\n";
            $customer_message .= "سعر الباقة: " . number_format($total_amount, 3) . " OMR\n";

            $manager_message = "تم تغيير حالة الطلب\n";
            $manager_message .= "رقم الطلب: $order_id\n";
            $manager_message .= "اسم العميل: $client_name\n";
            $manager_message .= "رقم الهاتف: +968" . $client_phone . "\n";
            $manager_message .= "تاريخ الاستلام: " . formatDateTime($delivery_time) . "\n";
            $manager_message .= "نوع السيارة: $car_type\n";
            $manager_message .= "سعر الباقة: " . number_format($total_amount, 3) . " OMR\n";
            $manager_message .= "الحالة الجديدة: تم الانتهاء من العمل على السيارة\n";

            sendWhatsAppMessage($client_phone, $customer_message);
            echo "Customer message sent"; // رسالة تصحيح
            foreach ($manager_phones as $manager_phone) {
                sendWhatsAppMessage($manager_phone, $manager_message);
            }
            echo "Manager message sent"; // رسالة تصحيح
        } else {
            echo "Failed to update order status"; // رسالة تصحيح
        }
    } elseif ($action == 'cancel') {
        $sql_update = "UPDATE orders SET status = 'canceled' WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param('i', $order_id);

        if ($stmt_update->execute()) {
            echo "Order status updated to canceled"; // رسالة تصحيح
            $customer_message = "شكرا لك على اختيارك TOP LEVEL CAR CARE CENTER\n";
            $customer_message .= "تم إلغاء العمل على سيارتك. يرجى الاتصال بنا لمزيد من التفاصيل.\n";
            $customer_message .= "رقم الطلب: $order_id\n";
            $customer_message .= "اسم العميل: $client_name\n";
            $customer_message .= "تاريخ الاستلام: " . formatDateTime($delivery_time) . "\n";
            $customer_message .= "نوع السيارة: $car_type\n";
            $customer_message .= "سعر الباقة: " . number_format($total_amount, 3) . " OMR\n";

            $manager_message = "تم تغيير حالة الطلب\n";
            $manager_message .= "رقم الطلب: $order_id\n";
            $manager_message .= "اسم العميل: $client_name\n";
            $manager_message .= "رقم الهاتف: +968" . $client_phone . "\n";
            $manager_message .= "تاريخ الاستلام: " . formatDateTime($delivery_time) . "\n";
            $manager_message .= "نوع السيارة: $car_type\n";
            $manager_message .= "سعر الباقة: " . number_format($total_amount, 3) . " OMR\n";
            $manager_message .= "الحالة الجديدة: تم إلغاء الطلب\n";

            sendWhatsAppMessage($client_phone, $customer_message);
            echo "Customer message sent"; // رسالة تصحيح
            foreach ($manager_phones as $manager_phone) {
                sendWhatsAppMessage($manager_phone, $manager_message);
            }
            echo "Manager message sent"; // رسالة تصحيح
        } else {
            echo "Failed to update order status"; // رسالة تصحيح
        }
    }

    header('Location: profile.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            margin-top: 50px;
        }
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile-header img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 20px;
        }
        .profile-header h2 {
            margin: 0;
        }
        .transactions-table {
            margin-top: 20px;
        }
        .search-box {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <div class="profile-header">
            <img src="<?php echo htmlspecialchars($employee_image); ?>" alt="Employee Image">
            <div>
                <h2><?php echo htmlspecialchars($employee_name); ?></h2>
                <p>Number of Transactions: <?php echo $transaction_count; ?></p>
                <p>Total Amount Collected: <?php echo number_format($total_amount, 2); ?> OMR </p>
            </div>
        </div>

        <form method="get" class="search-box">
            <input type="text" name="search" class="form-control" placeholder="Search by client name, car number, phone number, order code, or order id" value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit" class="btn btn-primary mt-2">Search</button>
        </form>

        <h3>Transaction Details</h3>
        <table class="table table-striped transactions-table">
            <thead>
                <tr>
                    <th>Order Code</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Invoice</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($transaction = $result_transactions->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($transaction['order_code']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['delivery_time']); ?></td>
                        <td><?php echo number_format($transaction['total_amount'], 2); ?> OMR </td>
                        <td><a href="view_order.php?id=<?php echo $transaction['id']; ?>" class="btn btn-info btn-sm">View Invoice</a></td>
                        <td><?php echo htmlspecialchars($transaction['status']); ?></td>
                        <td>
                            <?php if ($transaction['status'] == 'pending'): ?>
                                <a href="profile.php?action=complete&id=<?php echo $transaction['id']; ?>" class="btn btn-success btn-sm">Complete</a>
                                <a href="profile.php?action=cancel&id=<?php echo $transaction['id']; ?>" class="btn btn-danger btn-sm">Cancel</a>
                            <?php endif; ?>
                            <?php if ($transaction['car_delivered'] == 0): ?>
                                <a href="profile.php?action=deliver&id=<?php echo $transaction['id']; ?>" class="btn btn-warning btn-sm">Deliver Car</a>
                            <?php else: ?>
                                <span class="text-success">تم تسليم السيارة</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
