<?php
include('db.php'); // تأكد من تضمين ملف الاتصال بقاعدة البيانات

// جلب جميع الطلبات من قاعدة البيانات
$sql = "SELECT * FROM orders";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Manage Orders</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order Code</th>
                    <th>Customer Name</th>
                    <th>Order Date</th>
                    <th>Total Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_code']); ?></td>
                        <td><?php echo htmlspecialchars($order['client_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['arrival_time']); ?></td>
                        <td><?php echo htmlspecialchars($order['total_amount']); ?> OMR</td>
                        <td><a href="view_order.php?id=<?php echo $order['id']; ?>" class="btn btn-success">View Order</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
