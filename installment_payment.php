<?php
include('db.php'); // تأكد من تضمين ملف الاتصال بقاعدة البيانات

// تضمين شريط التنقل
include('nav.php');

$order_selected = false;
$remaining_amount = 0;
$amount = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['select_order'])) {
        $order_id = $_POST['order_id'];
        $order_selected = true;

        // جلب تفاصيل الطلب
        $sql = "SELECT id, total_amount, deposit_amount, remaining_amount FROM orders WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
    } elseif (isset($_POST['add_installment'])) {
        $order_id = $_POST['order_id'];
        $amount = (float)$_POST['amount'];

        // جلب تفاصيل الطلب
        $sql = "SELECT id, total_amount, deposit_amount, remaining_amount FROM orders WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();

        // إضافة دفعة القسط إلى قاعدة البيانات
        $insert_sql = "INSERT INTO installments (order_id, amount, payment_date) VALUES (?, ?, NOW())";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("id", $order_id, $amount);
        $insert_stmt->execute();

        // حساب المبالغ المتبقية
        $installments_sql = "SELECT SUM(amount) as total_installments FROM installments WHERE order_id = ?";
        $installments_stmt = $conn->prepare($installments_sql);
        $installments_stmt->bind_param("i", $order_id);
        $installments_stmt->execute();
        $installments_result = $installments_stmt->get_result();
        $installments = $installments_result->fetch_assoc();
        $total_paid = $order['deposit_amount'] + $installments['total_installments'];
        $remaining_amount = $order['total_amount'] - $total_paid;

        // تحديث المبلغ المتبقي في جدول الطلبات
        $update_sql = "UPDATE orders SET remaining_amount = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("di", $remaining_amount, $order_id);
        $update_stmt->execute();

        echo "<div style='color: green; font-size: 18px; text-align: center;'>Installment added successfully. Remaining amount: $remaining_amount OMR.</div>";
    }
}

// البحث عن الفاتورة برقم الطلب أو رقم السيارة أو اسم العميل أو رقم الهاتف
$search_results = [];
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search'])) {
    $identifier = $_GET['search'];
    $sql = "SELECT id, order_code, client_name, car_number, phone_number, total_amount, remaining_amount 
            FROM orders 
            WHERE car_number = ? 
               OR id = ? 
               OR client_name LIKE ? 
               OR phone_number = ?";
    $stmt = $conn->prepare($sql);
    $like_identifier = "%" . $identifier . "%";
    $stmt->bind_param("siss", $identifier, $identifier, $like_identifier, $identifier);
    $stmt->execute();
    $search_results = $stmt->get_result(); // تأكد من أن هذا هو كائن mysqli_result
}

// جلب جميع المعاملات التي تحتوي على أقساط
$installment_orders_sql = "
    SELECT o.id, o.order_code, o.client_name, o.car_number, o.phone_number, o.total_amount, o.remaining_amount, 
           SUM(i.amount) as total_installments
    FROM orders o
    LEFT JOIN installments i ON o.id = i.order_id
    WHERE o.remaining_amount > 0
    GROUP BY o.id";
$installment_orders_result = $conn->query($installment_orders_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Installments</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
        }
        .form-container {
            max-width: 900px;
            width: 100%;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- تضمين شريط التنقل -->
    <?php include('nav.php'); ?>

    <div class="form-container">
        <h2>Manage Installments</h2>
        <form method="GET" action="">
            <div class="form-group">
                <label for="search">Order ID / Car Number / Client Name / Phone Number</label>
                <input type="text" class="form-control" id="search" name="search" required>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <?php if ($order_selected): ?>
            <form method="POST" action="">
                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id']); ?>">
                <div class="form-group">
                    <label for="amount">Installment Amount</label>
                    <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
                </div>
                <button type="submit" class="btn btn-primary" name="add_installment">Submit</button>
            </form>
            <h3>Order Details</h3>
            <p>Order ID: <?php echo htmlspecialchars($order['id']); ?></p>
            <p>Total Amount: <?php echo number_format($order['total_amount'], 2); ?> OMR</p>
            <p>Deposit Amount: <?php echo number_format($order['deposit_amount'], 2); ?> OMR</p>
            <p>Remaining Amount: <?php echo number_format($order['remaining_amount'], 2); ?> OMR</p>
        <?php elseif (isset($search_results) && $search_results instanceof mysqli_result && $search_results->num_rows > 0): ?>
            <h3>Search Results</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order Code</th>
                        <th>Client Name</th>
                        <th>Car Number</th>
                        <th>Phone Number</th>
                        <th>Total Amount</th>
                        <th>Remaining Amount</th>
                        <th>Select</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $search_results->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['order_code']); ?></td>
                            <td><?php echo htmlspecialchars($row['client_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['car_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                            <td><?php echo number_format($row['total_amount'], 2); ?> OMR</td>
                            <td><?php echo number_format($row['remaining_amount'], 2); ?> OMR</td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="select_order" class="btn btn-success btn-sm">Select</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <h3>Orders with Installments</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order Code</th>
                    <th>Client Name</th>
                    <th>Car Number</th>
                    <th>Phone Number</th>
                    <th>Total Amount</th>
                    <th>Total Installments</th>
                    <th>Remaining Amount</th>
                    <th>Select</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $installment_orders_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['order_code']); ?></td>
                        <td><?php echo htmlspecialchars($row['client_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['car_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                        <td><?php echo number_format($row['total_amount'], 2); ?> OMR</td>
                        <td><?php echo number_format($row['total_installments'], 2); ?> OMR</td>
                        <td><?php echo number_format($row['remaining_amount'], 2); ?> OMR</td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="select_order" class="btn btn-success btn-sm">Select</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
