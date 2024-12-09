<?php
session_start();
if (!isset($_SESSION['employee_name'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

include('db.php'); // تأكد من تضمين ملف الاتصال بقاعدة البيانات

// إضافة كوبون
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_coupon'])) {
    $coupon_code = $_POST['coupon_code'];
    $discount_amount = (float)$_POST['discount_amount'];
    
    $stmt = $conn->prepare("INSERT INTO coupons (coupon_code, discount_amount) VALUES (?, ?)");
    $stmt->bind_param("sd", $coupon_code, $discount_amount);
    $stmt->execute();
    $stmt->close();
}

// حذف كوبون
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    $stmt = $conn->prepare("DELETE FROM coupons WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
}

// جلب الكوبونات الحالية
$sql = "SELECT * FROM coupons";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Coupons</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            max-width: 600px;
            margin: auto;
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .coupon-list {
            margin-top: 20px;
        }
        .coupon-list table {
            width: 100%;
        }
        .coupon-list th, .coupon-list td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Manage Coupons</h2>
        <form action="manage_coupons.php" method="post">
            <div class="form-group">
                <label for="coupon_code">Coupon Code</label>
                <input type="text" class="form-control" id="coupon_code" name="coupon_code" required>
            </div>
            <div class="form-group">
                <label for="discount_amount">Discount Amount</label>
                <input type="number" class="form-control" id="discount_amount" name="discount_amount" step="0.01" required>
            </div>
            <button type="submit" name="add_coupon" class="btn btn-primary">Add Coupon</button>
        </form>

        <div class="coupon-list">
            <h3>Existing Coupons</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Coupon Code</th>
                        <th>Discount Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['coupon_code']); ?></td>
                            <td><?php echo htmlspecialchars($row['discount_amount']); ?> OMR</td>
                            <td>
                                <a href="manage_coupons.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                <a href="edit_coupon.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
