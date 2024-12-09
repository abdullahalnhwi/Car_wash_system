<?php
session_start();
if (!isset($_SESSION['employee_name'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

require 'db.php'; // تأكد من أن ملف db.php يحتوي على اتصال بقاعدة البيانات

$search_query = "";
$filter_query = "WHERE status = 'pending'";
$total_orders = 0;
$total_amount = 0.0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_query = $_POST['search_query'];
    $filter_query = "WHERE client_name LIKE '%$search_query%' OR car_number LIKE '%$search_query%' OR id LIKE '%$search_query%' OR arrival_time LIKE '%$search_query%' OR status = 'pending'";
}

$sql = "SELECT * FROM orders $filter_query";
$result = $conn->query($sql);

// حساب مجموع الطلبات والمبلغ الإجمالي
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $total_orders++;
        $total_amount += $row['total_amount'];
    }
    // إعادة تعيين مؤشر النتائج إلى البداية
    $result->data_seek(0);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            padding-top: 70px; /* Space for the fixed navbar */
        }
        .container {
            margin-top: 20px;
        }
        table {
            width: 100%;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #f8f8f8;
        }
        table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .action-link {
            color: #007BFF;
            text-decoration: none;
        }
        .action-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>
   
    <div class="container">
        <h2 class="text-center">Manage Orders</h2>
        <form method="POST" action="">
            <div class="form-group">
                <input type="text" name="search_query" class="form-control" placeholder="Search by name, car number, ID or time" value="<?php echo htmlspecialchars($search_query); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        
        <h4 class="text-center">Total Orders: <?php echo $total_orders; ?></h4>
        <h4 class="text-center">Total Amount: <?php echo number_format($total_amount, 3); ?> OMR </h4>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Employee Name</th>
                    <th>Client Name</th>
                    <th>Car Number</th>
                    <th>Phone Number</th>
                    <th>Arrival Time</th>
                    <th>Delivery Time</th>
                    <th>Service Type</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['employee_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['client_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['car_number']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['arrival_time']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['delivery_time']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['service_category']) . "</td>";
                        echo "<td>" . number_format($row['total_amount'], 3) . " OMR</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "<td>
                                 <a class='action-link' href='view_order.php?id=" . htmlspecialchars($row['id']) . "'>View</a> |
                                 <a class='action-link' href='edit_order.php?id=" . htmlspecialchars($row['id']) . "'>Edit</a> |
                                 <a class='action-link' href='update_status.php?id=" . htmlspecialchars($row['id']) . "&status=completed'>Complete</a> |
                                 <a class='action-link' href='update_status.php?id=" . htmlspecialchars($row['id']) . "&status=canceled'>Cancel</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11' class='text-center'>No orders found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
