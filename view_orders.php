<?php
session_start();
if (!isset($_SESSION['employee_name'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

require 'db.php'; // تأكد من أن ملف db.php يحتوي على اتصال بقاعدة البيانات

$search_query = "";
$filter_query = "";
$total_orders = 0;
$total_amount = 0.0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_query = $_POST['search_query'];
    $filter_query = "WHERE client_name LIKE '%$search_query%' OR car_number LIKE '%$search_query%' OR id LIKE '%$search_query%' OR arrival_time LIKE '%$search_query%'";
} elseif (isset($_GET['report_type'])) {
    $report_type = $_GET['report_type'];
    if ($report_type == 'monthly' && isset($_GET['month']) && isset($_GET['year'])) {
        $month = $_GET['month'];
        $year = $_GET['year'];
        $filter_query = "WHERE MONTH(arrival_time) = $month AND YEAR(arrival_time) = $year";
    } elseif ($report_type == 'yearly' && isset($_GET['year'])) {
        $year = $_GET['year'];
        $filter_query = "WHERE YEAR(arrival_time) = $year";
    }
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
    <title>View Orders</title>
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
        .view-order, .edit-order {
            color: #007BFF;
            text-decoration: none;
        }
        .view-order:hover, .edit-order:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>
   
    <div class="container">
        <h2 class="text-center">Orders</h2>
        <form method="POST" action="">
            <div class="form-group">
                <input type="text" name="search_query" class="form-control" placeholder="Search by name, car number, ID or time" value="<?php echo htmlspecialchars($search_query); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        
        <h3 class="text-center">Monthly and Yearly Reports</h3>
        <div class="text-center">
            <form method="GET" action="">
                <input type="hidden" name="report_type" value="monthly">
                <div class="form-group">
                    <label for="month">Month:</label>
                    <select name="month" class="form-control" required>
                        <option value="" disabled selected>Select Month</option>
                        <?php
                        for ($i = 1; $i <= 12; $i++) {
                            echo "<option value='$i'>$i</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="year">Year:</label>
                    <select name="year" class="form-control" required>
                        <option value="" disabled selected>Select Year</option>
                        <?php
                        $current_year = date('Y');
                        for ($i = $current_year; $i >= $current_year - 10; $i--) {
                            echo "<option value='$i'>$i</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Generate Monthly Report</button>
            </form>

            <form method="GET" action="">
                <input type="hidden" name="report_type" value="yearly">
                <div class="form-group">
                    <label for="year">Year:</label>
                    <select name="year" class="form-control" required>
                        <option value="" disabled selected>Select Year</option>
                        <?php
                        $current_year = date('Y');
                        for ($i = $current_year; $i >= $current_year - 10; $i--) {
                            echo "<option value='$i'>$i</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Generate Yearly Report</button>
            </form>
        </div>

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
                    <th>Notes</th>
                    <th>View</th>
                    <th>Edit</th>
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
                        echo "<td>" . htmlspecialchars($row['notes']) . "</td>";
                        echo "<td><a class='view-order' href='view_order.php?id=" . htmlspecialchars($row['id']) . "'>View Order</a></td>";
                        echo "<td><a class='edit-order' href='edit_invoice.php?id=" . htmlspecialchars($row['id']) . "'>Edit</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='12' class='text-center'>No orders found</td></tr>";
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
