<?php
include('db.php');
session_start();

if (!isset($_SESSION['employee_name'])) {
    header('Location: login.php');
    exit();
}

$employee_name = $_SESSION['employee_name'];
$employee_image = $_SESSION['image'];

// التحقق من وجود استعلام بحث
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
    $sql_summary = "SELECT COUNT(*) as transaction_count, SUM(total_amount) as total_amount FROM orders WHERE status = 'pending' AND car_delivered = 0 AND (client_name LIKE ? OR car_number LIKE ? OR phone_number LIKE ? OR order_code LIKE ?)";
    $search_param = '%' . $search_query . '%';
    $stmt_summary = $conn->prepare($sql_summary);
    $stmt_summary->bind_param("ssss", $search_param, $search_param, $search_param, $search_param);
} else {
    $sql_summary = "SELECT COUNT(*) as transaction_count, SUM(total_amount) as total_amount FROM orders WHERE status = 'pending' AND car_delivered = 0";
    $stmt_summary = $conn->prepare($sql_summary);
}

$stmt_summary->execute();
$stmt_summary->bind_result($transaction_count, $total_amount);
$stmt_summary->fetch();
$stmt_summary->close();

// جلب تفاصيل المعاملات وترتيبها تنازلياً حسب تاريخ الوصول
if (isset($search_query) && !empty($search_query)) {
    $sql_transactions = "SELECT id, order_code, delivery_time, total_amount, status, car_delivered FROM orders WHERE status = 'pending' AND car_delivered = 0 AND (client_name LIKE ? OR car_number LIKE ? OR phone_number LIKE ? OR order_code LIKE ?) ORDER BY arrival_time DESC";
    $stmt_transactions = $conn->prepare($sql_transactions);
    $stmt_transactions->bind_param("ssss", $search_param, $search_param, $search_param, $search_param);
} else {
    $sql_transactions = "SELECT id, order_code, delivery_time, total_amount, status, car_delivered FROM orders WHERE status = 'pending' AND car_delivered = 0 ORDER BY arrival_time DESC";
    $stmt_transactions = $conn->prepare($sql_transactions);
}

$stmt_transactions->execute();
$result_transactions = $stmt_transactions->get_result();
$stmt_transactions->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Orders</title>
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
            <input type="text" name="search" class="form-control" placeholder="Search by client name, car number, phone number, or order code" value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit" class="btn btn-primary mt-2">Search</button>
        </form>

        <h3>Pending Orders</h3>
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
                                <a href="update_status.php?id=<?php echo $transaction['id']; ?>&status=completed" class="btn btn-success btn-sm">Complete</a>
                                <a href="update_status.php?id=<?php echo $transaction['id']; ?>&status=canceled" class="btn btn-danger btn-sm">Cancel</a>
                            <?php endif; ?>
                            <?php if ($transaction['car_delivered'] == 0): ?>
                                <a href="deliver_car.php?id=<?php echo $transaction['id']; ?>" class="btn btn-warning btn-sm">Deliver Car</a>
                            <?php else: ?>
                                <span class="text-success">Car Delivered</span>
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
