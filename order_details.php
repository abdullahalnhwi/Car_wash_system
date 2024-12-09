<?php
session_start();
if (!isset($_SESSION['employee_name'])) {
    header('Location: login.php');
    exit();
}

include('db.php');

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch order details
$sql = "SELECT o.*, c.car_name 
        FROM orders o 
        LEFT JOIN client_cars c ON o.client_car_id = c.id 
        WHERE o.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    header('Location: index.php');
    exit();
}

// Generate QR code data
$qr_data = json_encode([
    'order_id' => $order['order_code'],
    'client' => $order['client_name'],
    'car_number' => $order['car_number'],
    'amount' => $order['total_amount'],
    'services' => json_decode($order['services'], true)
]);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Car Wash</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator/qrcode.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #00416A, #E4E5E6);
            min-height: 100vh;
            padding-top: 90px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .order-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 0.8s ease;
        }

        .order-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #4CAF50;
        }

        .order-header h1 {
            color: #00416A;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .order-code {
            color: #4CAF50;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .order-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem;
            background: rgba(0, 65, 106, 0.05);
            border-radius: 10px;
        }

        .detail-label {
            color: #666;
            font-weight: 600;
        }

        .detail-value {
            color: #00416A;
            font-weight: 600;
            text-align: right;
        }

        .services-list {
            margin-bottom: 2rem;
        }

        .services-list h2 {
            color: #00416A;
            margin-bottom: 1rem;
            font-size: 1.4rem;
        }

        .service-item {
            padding: 0.8rem;
            margin-bottom: 0.5rem;
            background: rgba(76, 175, 80, 0.1);
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
        }

        .qr-section {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #4CAF50;
        }

        .qr-code {
            margin: 1rem auto;
            max-width: 300px;
        }

        .qr-code img {
width: 100%;
height: auto;
}

        .print-btn {
            display: inline-block;
            padding: 12px 30px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            margin-top: 1rem;
        }

        .print-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }

        .print-btn i {
            margin-right: 8px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media print {
            body {
                background: white;
                padding-top: 0;
            }

            .order-card {
                box-shadow: none;
            }

            .print-btn {
                display: none;
            }

            nav {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .order-details {
                grid-template-columns: 1fr;
            }
        }

        /* RTL Support */
        [dir="rtl"] .detail-value {
            text-align: left;
        }

        [dir="rtl"] .print-btn i {
            margin-right: 0;
            margin-left: 8px;
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <div class="order-card">
            <div class="order-header">
                <h1>تفاصيل الطلبية / Order Details</h1>
                <div class="order-code"><?php echo htmlspecialchars($order['order_code']); ?></div>
            </div>

            <div class="order-details">
                <div class="detail-item">
                    <span class="detail-label">Client Name / اسم العميل</span>
                    <span class="detail-value"><?php echo htmlspecialchars($order['client_name']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Car Number / رقم السيارة</span>
                    <span class="detail-value"><?php echo htmlspecialchars($order['car_number']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Car Type / نوع السيارة</span>
                    <span class="detail-value"><?php echo htmlspecialchars($order['car_name']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Total Amount / المبلغ الإجمالي</span>
                    <span class="detail-value"><?php echo number_format($order['total_amount'], 3); ?> OMR</span>
                </div>
            </div>

            <div class="services-list">
                <h2>Services / الخدمات</h2>
                <?php 
                $services = json_decode($order['services'], true);
                foreach ($services as $service): 
                ?>
                    <div class="service-item">
                        <span><?php echo htmlspecialchars($service); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="qr-section">
                <h2>QR Code</h2>
                <div id="qrcode" class="qr-code"></div>
                <button onclick="window.print()" class="print-btn">
                    <i class="fas fa-print"></i> Print / طباعة
                </button>
            </div>
        </div>
    </div>

    <script>
        // Generate QR Code
        window.onload = function() {
            var qr = qrcode(0, 'M');
            qr.addData(<?php echo json_encode($qr_data); ?>);
            qr.make();
            document.getElementById('qrcode').innerHTML = qr.createImgTag(6);
        };
    </script>
</body>
</html>
