<?php
session_start();
require_once 'db.php';

// Ensure user is logged in and is a customer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's cars
$cars_sql = "SELECT * FROM client_cars WHERE user_id = ?";
$cars_stmt = $conn->prepare($cars_sql);
$cars_stmt->bind_param("i", $user_id);
$cars_stmt->execute();
$cars_result = $cars_stmt->get_result();

// Fetch user's orders
$orders_sql = "SELECT o.*, c.car_name, c.car_number 
               FROM orders o 
               JOIN client_cars c ON o.client_car_id = c.id 
               WHERE c.user_id = ? 
               ORDER BY o.arrival_time DESC";
$orders_stmt = $conn->prepare($orders_sql);
$orders_stmt->bind_param("i", $user_id);
$orders_stmt->execute();
$orders_result = $orders_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الملف الشخصي</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #00416A, #E4E5E6);
            min-height: 100vh;
            padding-top: 90px; /* Added to account for fixed navbar */
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            background: rgba(255, 255, 255, 0.95);
        }

        .card-header {
            background: #00416A;
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 15px 20px;
        }

        .btn-group {
            gap: 5px;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 20px;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            border-top: none;
            color: #00416A;
        }

        @media (max-width: 768px) {
            .container {
                padding-top: 20px;
            }
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <!-- Cars Section -->
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">سياراتي</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($cars_result->num_rows > 0): ?>
                            <div class="row">
                                <?php while ($car = $cars_result->fetch_assoc()): ?>
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6 class="card-title"><?php echo htmlspecialchars($car['car_name']); ?></h6>
                                                <p class="card-text">
                                                    <strong>رقم السيارة:</strong> <?php echo htmlspecialchars($car['car_number']); ?><br>
                                                    <strong>النوع:</strong> <?php echo $car['car_type'] === 'saloon' ? 'صالون' : 'دفع رباعي'; ?>
                                                </p>
                                                <div class="btn-group">
                                                    <a href="select_services.php?car_id=<?php echo $car['id']; ?>" 
                                                       class="btn btn-primary btn-sm">حجز خدمة</a>
                                                    <button type="button" 
                                                            class="btn btn-danger btn-sm"
                                                            onclick="deleteCar(<?php echo $car['id']; ?>)">
                                                        حذف السيارة
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-center">لا توجد سيارات مسجلة</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Orders Section -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">الطلبات السابقة</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($orders_result->num_rows > 0): ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>رقم الطلب</th>
                                            <th>السيارة</th>
                                            <th>الخدمات</th>
                                            <th>التكلفة</th>
                                            <th>الحالة</th>
                                            <th>موعد الوصول</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($order = $orders_result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $order['order_code']; ?></td>
                                                <td><?php echo htmlspecialchars($order['car_name']) . ' (' . htmlspecialchars($order['car_number']) . ')'; ?></td>
                                                <td><?php echo htmlspecialchars($order['services']); ?></td>
                                                <td><?php echo number_format($order['total_amount'], 3) . ' ريال'; ?></td>
                                                <td><?php echo getStatusInArabic($order['status']); ?></td>
                                                <td><?php echo date('Y-m-d h:i A', strtotime($order['arrival_time'])); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-center">لا توجد طلبات سابقة</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
    function deleteCar(carId) {
        if (confirm('هل أنت متأكد من حذف هذه السيارة؟')) {
            fetch('delete_car.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'car_id=' + carId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('حدث خطأ أثناء حذف السيارة');
                }
            });
        }
    }
    </script>
</body>
</html>

<?php
function getStatusInArabic($status) {
    switch($status) {
        case 'pending':
            return 'قيد الانتظار';
        case 'completed':
            return 'مكتمل';
        case 'cancelled':
            return 'ملغي';
        default:
            return $status;
    }
}
?> 