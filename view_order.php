<?php
include('db.php');
include('phpqrcode/qrlib.php'); // تضمين مكتبة QR Code

session_start();

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $order_id = $_GET['id'];
    $sql = "SELECT orders.*, categories.name as package_name, services.service_level 
            FROM orders 
            JOIN categories ON orders.service_category = categories.id
            JOIN services ON FIND_IN_SET(services.name, orders.services)
            WHERE orders.id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $order = $result->fetch_assoc();
        } else {
            echo "<div style='color: red; font-size: 18px; text-align: center;'>Order not found.</div>";
            exit();
        }
    } else {
        echo "<div style='color: red; font-size: 18px; text-align: center;'>Failed to prepare the statement.</div>";
        exit();
    }
} else {
    echo "<div style='color: red; font-size: 18px; text-align: center;'>Order ID is missing.</div>";
    exit();
}

$is_special_service = ($order['service_level'] == 'special');

// توليد بيانات QR Code مع رابط URL
$invoice_url = "https://toplevelom.com/view_order.php?id={$order['id']}";
$qr_file = 'phpqrcode/order_' . $order['id'] . '.png';

// توليد QR Code وحفظه
QRcode::png($invoice_url, $qr_file, QR_ECLEVEL_L, 10);

// جلب تفاصيل الخدمات
$service_ids = explode(',', $order['services']);
$service_details = [];
foreach ($service_ids as $service_name) {
    $service_sql = "SELECT name, price FROM services WHERE name = ? AND car_type = ?";
    $service_stmt = $conn->prepare($service_sql);
    $service_stmt->bind_param("ss", $service_name, $order['car_type']);
    $service_stmt->execute();
    $service_stmt->bind_result($service_name, $service_price);
    while ($service_stmt->fetch()) {
        $service_details[] = "$service_name ($service_price OMR)";
    }
    $service_stmt->close();
}

// إضافة الخدمات الإضافية إلى تفاصيل الخدمات
$extra_services = [];
if (!empty($order['extra_service_name']) && !empty($order['extra_service_price'])) {
    $extra_service_names = explode(',', $order['extra_service_name']);
    $extra_service_prices = explode(',', $order['extra_service_price']);
    for ($i = 0; $i < count($extra_service_names); $i++) {
        if (!empty($extra_service_names[$i]) && !empty($extra_service_prices[$i])) {
            $extra_services[] = $extra_service_names[$i] . " (" . number_format((float)$extra_service_prices[$i], 3) . " OMR)";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            margin: 0;
        }
        .invoice-container {
            max-width: 800px;
            width: 100%;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .invoice-header img {
            max-width: 100px;
        }
        .invoice-header h1 {
            font-size: 24px;
            margin: 0;
            color: #333;
        }
        .invoice-body {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .invoice-body p {
            margin: 0;
            font-size: 16px;
            color: #555;
        }
        .invoice-body p span {
            font-weight: bold;
            color: #333;
        }
        .invoice-footer {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .invoice-footer button {
            width: 50%; /* يمكنك تعديل العرض بناءً على تفضيلك */
            padding: 10px;
            text-align: center;
            color: #fff;
            background-color: #28A745;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .qr-code {
            text-align: center;
            margin-top: 20px;
        }
        .qr-code img {
            max-width: 200px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <img src="img/log.jpg" alt="Company Logo">
            <h1>Invoice</h1>
        </div>
        <div class="invoice-body">
            <p><span>Order ID:</span> <?php echo htmlspecialchars($order['id']); ?></p> <!-- إضافة Order ID هنا -->
            <p><span>Order Code:</span> <?php echo htmlspecialchars($order['order_code']); ?></p>
            <p><span>Order Date:</span> <?php echo htmlspecialchars($order['arrival_time']); ?></p>
            <p><span>Delivery Time:</span> <?php echo htmlspecialchars($order['delivery_time']); ?></p>
            <p><span>Processed By:</span> <?php echo htmlspecialchars($order['employee_name']); ?></p>
            <p><span>Car Number:</span> <?php echo htmlspecialchars($order['car_number']); ?></p>
            <p><span>Car Type:</span> <?php echo htmlspecialchars($order['car_type']); ?></span></p>
            <p><span>Car Name:</span> <?php echo htmlspecialchars($order['car_name']); ?></p>
            <p><span>Customer Name:</span> <?php echo htmlspecialchars($order['client_name']); ?></p>
            <p><span>Customer Contact:</span> <?php echo htmlspecialchars($order['phone_number']); ?></p>
            <p><span>Package Chosen:</span> <?php echo htmlspecialchars($order['package_name']); ?></p>
            <p><span>Order Details:</span> <?php echo htmlspecialchars(implode(', ', $service_details)); ?></p>
            <p><span>Extra Services:</span> <?php echo htmlspecialchars(implode(', ', $extra_services)); ?></p> <!-- إضافة الخدمات الإضافية -->
            <p><span>Package Price:</span> <?php echo htmlspecialchars($order['total_amount']) . ' OMR'; ?></p>
            <p><span>Total:</span> <?php echo htmlspecialchars($order['total_after_discount']) . ' OMR'; ?></p>
            <p><span>Remaining Amount:</span> <?php echo htmlspecialchars($order['remaining_amount']) . ' OMR'; ?></p>
            <p><span>Notes:</span> <?php echo htmlspecialchars($order['notes']); ?></p>
            <p><span>Order Status:</span> <?php echo htmlspecialchars($order['status']); ?></p>
            <p><span>Number of Services Provided:</span> <?php echo count($service_details) + count($extra_services); ?></p>
            <?php if ($order['car_delivered']): ?>
                <p><span>Car Delivery Time:</span> <?php echo htmlspecialchars($order['car_delivery_time']); ?></p>
            <?php endif; ?>
        </div>
        <div class="qr-code">
            <img src="<?php echo $qr_file; ?>" alt="QR Code">
        </div>
        <div class="invoice-footer">
            <button class="btn btn-success" onclick="printInvoice()">Print Invoice</button>
        </div>
    </div>

    <script>
        function printInvoice() {
            var printContents = document.querySelector('.invoice-container').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
    </script>
</body>
</html>
