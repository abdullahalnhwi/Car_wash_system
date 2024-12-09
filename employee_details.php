<?php
include('db.php');
session_start();

if (!isset($_SESSION['employee_name']) || $_SESSION['role'] != 'manager') {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    echo "Employee ID is missing.";
    exit();
}

$employee_id = $_GET['id'];

// جلب تفاصيل الموظف
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

// التحقق من وجود استعلام بحث
$search_query = '';
$filter_query = '';
$search_param = '';
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
    $filter_query = "AND (client_name LIKE ? OR car_number LIKE ? OR phone_number LIKE ? OR order_code LIKE ?)";
    $search_param = '%' . $search_query . '%';
}

// جلب تفاصيل المعاملات وترتيبها تنازلياً حسب تاريخ التسليم
$transactions_sql = "SELECT id, order_code, total_amount, status FROM orders WHERE employee_name = ? $filter_query ORDER BY delivery_time DESC";
$stmt_transactions = $conn->prepare($transactions_sql);

if (!empty($filter_query)) {
    $stmt_transactions->bind_param("sssss", $employee['employee_name'], $search_param, $search_param, $search_param, $search_param);
} else {
    $stmt_transactions->bind_param("s", $employee['employee_name']);
}

$stmt_transactions->execute();
$result_transactions = $stmt_transactions->get_result();

// جلب تفاصيل المعاملات حسب الفترات الزمنية
function getTransactionsByPeriod($conn, $employee_name, $interval) {
    $sql = "SELECT COUNT(*) as transaction_count, SUM(total_amount) as total_amount FROM orders WHERE employee_name = ? AND arrival_time >= DATE_SUB(NOW(), INTERVAL $interval)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $employee_name);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

$yearly = getTransactionsByPeriod($conn, $employee['employee_name'], '1 YEAR');
$monthly = getTransactionsByPeriod($conn, $employee['employee_name'], '1 MONTH');
$weekly = getTransactionsByPeriod($conn, $employee['employee_name'], '1 WEEK');
$daily = getTransactionsByPeriod($conn, $employee['employee_name'], '1 DAY');

// حساب عدد الطلبات المكتملة والملغاة
$sql_status_count = "SELECT status, COUNT(*) as count FROM orders WHERE employee_name = ? GROUP BY status";
$stmt_status_count = $conn->prepare($sql_status_count);
$stmt_status_count->bind_param("s", $employee['employee_name']);
$stmt_status_count->execute();
$result_status_count = $stmt_status_count->get_result();

$completed_orders = 0;
$canceled_orders = 0;

while ($row = $result_status_count->fetch_assoc()) {
    if ($row['status'] == 'completed') {
        $completed_orders = $row['count'];
    } elseif ($row['status'] == 'canceled') {
        $canceled_orders = $row['count'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            width: 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        h3, h4 {
            margin-bottom: 20px;
        }
        p {
            margin: 0 0 10px;
        }
        .transactions-table {
            margin-top: 20px;
        }
        .search-box {
            margin-bottom: 20px;
        }
        .employee-image {
            max-width: 150px;
            border-radius: 50%;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'nav.php'?>
    <div class="container">
        <h3>Employee Details</h3>
        <?php if ($employee['image']): ?>
            <img src="<?php echo htmlspecialchars($employee['image']); ?>" alt="Employee Image" class="employee-image">
        <?php endif; ?>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($employee['employee_name']); ?></p>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($employee['username']); ?></p>
        <h4>Transactions Summary</h4>
        <p><strong>Total Transactions:</strong> <?php echo $result_transactions->num_rows; ?></p>
        <p><strong>Total Amount Collected:</strong> 
            <?php 
            $result_transactions->data_seek(0); // إعادة مؤشر النتائج إلى البداية
            echo number_format(array_sum(array_column($result_transactions->fetch_all(MYSQLI_ASSOC), 'total_amount')), 2); 
            ?> OMR
        </p>
        <p><strong>Completed Orders:</strong> <?php echo $completed_orders; ?></p>
        <p><strong>Canceled Orders:</strong> <?php echo $canceled_orders; ?></p>
        <h4>Transactions by Period</h4>
        <p><strong>Yearly Transactions:</strong> <?php echo $yearly['transaction_count']; ?></p>
        <p><strong>Yearly Amount:</strong> <?php echo number_format($yearly['total_amount'], 2); ?> OMR</p>
        <p><strong>Monthly Transactions:</strong> <?php echo $monthly['transaction_count']; ?></p>
        <p><strong>Monthly Amount:</strong> <?php echo number_format($monthly['total_amount'], 2); ?> OMR</p>
        <p><strong>Weekly Transactions:</strong> <?php echo $weekly['transaction_count']; ?></p>
        <p><strong>Weekly Amount:</strong> <?php echo number_format($weekly['total_amount'], 2); ?> OMR</p>
        <p><strong>Daily Transactions:</strong> <?php echo $daily['transaction_count']; ?></p>
        <p><strong>Daily Amount:</strong> <?php echo number_format($daily['total_amount'], 2); ?> OMR</p>

        <h4>Transaction Details</h4>
        <form method="get" class="search-box">
            <input type="hidden" name="id" value="<?php echo $employee_id; ?>">
            <input type="text" name="search" class="form-control" placeholder="Search by client name, car number, phone number, or order code" value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit" class="btn btn-primary mt-2">Search</button>
        </form>

        <table class="table table-striped transactions-table">
            <thead>
                <tr>
                    <th>Order Code</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Invoice</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // إعادة تنفيذ الاستعلام للحصول على المعاملات من جديد
                $stmt_transactions->execute();
                $result_transactions = $stmt_transactions->get_result();

                while ($transaction = $result_transactions->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($transaction['order_code']); ?></td>
                        <td><?php echo number_format($transaction['total_amount'], 2); ?> OMR</td>
                        <td><?php echo htmlspecialchars($transaction['status']); ?></td>
                        <td><a href="view_order.php?id=<?php echo $transaction['id']; ?>" class="btn btn-info btn-sm">View Invoice</a></td>
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
