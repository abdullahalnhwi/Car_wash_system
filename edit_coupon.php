<?php
session_start();
if (!isset($_SESSION['employee_name'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

include('db.php'); // تأكد من تضمين ملف الاتصال بقاعدة البيانات

// جلب بيانات الكوبون
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $coupon_id = $_GET['id'];
    
    $sql = "SELECT * FROM coupons WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $coupon_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $coupon = $result->fetch_assoc();
        } else {
            echo "<div style='color: red; font-size: 18px; text-align: center;'>Coupon not found.</div>";
            exit();
        }
    } else {
        echo "<div style='color: red; font-size: 18px; text-align: center;'>Failed to prepare the statement.</div>";
        exit();
    }
} else {
    echo "<div style='color: red; font-size: 18px; text-align: center;'>Coupon ID is missing.</div>";
    exit();
}

// تحديث بيانات الكوبون
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_coupon'])) {
    $coupon_code = $_POST['coupon_code'];
    $discount_amount = (float)$_POST['discount_amount'];
    
    $stmt = $conn->prepare("UPDATE coupons SET coupon_code = ?, discount_amount = ? WHERE id = ?");
    $stmt->bind_param("sdi", $coupon_code, $discount_amount, $coupon_id);
    $stmt->execute();
    $stmt->close();
    
    header('Location: manage_coupons.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Coupon</title>
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
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Coupon</h2>
        <form action="edit_coupon.php?id=<?php echo $coupon_id; ?>" method="post">
            <div class="form-group">
                <label for="coupon_code">Coupon Code</label>
                <input type="text" class="form-control" id="coupon_code" name="coupon_code" value="<?php echo htmlspecialchars($coupon['coupon_code']); ?>" required>
            </div>
            <div class="form-group">
                <label for="discount_amount">Discount Amount</label>
                <input type="number" class="form-control" id="discount_amount" name="discount_amount" step="0.01" value="<?php echo htmlspecialchars($coupon['discount_amount']); ?>" required>
            </div>
            <button type="submit" name="update_coupon" class="btn btn-primary">Update Coupon</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
