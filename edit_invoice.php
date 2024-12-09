<?php
include('db.php');
session_start();

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $order_id = $_GET['id'];
    $sql = "SELECT * FROM orders WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $order = $result->fetch_assoc();
        } else {
            echo "<div style='color: red; font-size: 18px; text-align: center;'>Order not found.</div>";
            exit();
        }
    } else {
        echo "<div style='color: red; font-size: 18px; text-align: center;'>Failed to prepare the statement.</div>";
        exit();
    }
} else {
    echo "<div style='color: red; font-size: 18px; text-align: center;'>Order ID is missing.</div>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Invoice</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            margin: 0;
        }
        .form-container {
            max-width: 800px;
            width: 100%;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Invoice</h2>
        <form action="update_invoice.php" method="post">
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id']); ?>">
            <div class="form-group">
                <label for="employee_name">Employee Name</label>
                <input type="text" class="form-control" id="employee_name" name="employee_name" value="<?php echo htmlspecialchars($order['employee_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="client_name">Client Name</label>
                <input type="text" class="form-control" id="client_name" name="client_name" value="<?php echo htmlspecialchars($order['client_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="service_category">Service Category</label>
                <input type="text" class="form-control" id="service_category" name="service_category" value="<?php echo htmlspecialchars($order['service_category']); ?>" required>
            </div>
            <div class="form-group">
                <label for="services">Services</label>
                <input type="text" class="form-control" id="services" name="services" value="<?php echo htmlspecialchars($order['services']); ?>" required>
            </div>
            <div class="form-group">
                <label for="car_number">Car Number</label>
                <input type="text" class="form-control" id="car_number" name="car_number" value="<?php echo htmlspecialchars($order['car_number']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($order['phone_number']); ?>" required>
            </div>
            <div class="form-group">
                <label for="arrival_time">Arrival Time</label>
                <input type="datetime-local" class="form-control" id="arrival_time" name="arrival_time" value="<?php echo htmlspecialchars($order['arrival_time']); ?>" required>
            </div>
            <div class="form-group">
                <label for="delivery_time">Delivery Time</label>
                <input type="datetime-local" class="form-control" id="delivery_time" name="delivery_time" value="<?php echo htmlspecialchars($order['delivery_time']); ?>" required>
            </div>
            <div class="form-group">
                <label for="total_amount">Total Amount</label>
                <input type="number" class="form-control" id="total_amount" name="total_amount" step="0.01" value="<?php echo htmlspecialchars($order['total_amount']); ?>" required>
            </div>
            <div class="form-group">
                <label for="deposit_amount">Deposit Amount</label>
                <input type="number" class="form-control" id="deposit_amount" name="deposit_amount" step="0.01" value="<?php echo htmlspecialchars($order['deposit_amount']); ?>" required>
            </div>
            <div class="form-group">
                <label for="coupon_code">Coupon Code</label>
                <input type="text" class="form-control" id="coupon_code" name="coupon_code" value="<?php echo htmlspecialchars($order['coupon_code']); ?>">
            </div>
            <div class="form-group">
                <label for="coupon_discount">Coupon Discount</label>
                <input type="number" class="form-control" id="coupon_discount" name="coupon_discount" step="0.01" value="<?php echo htmlspecialchars($order['coupon_discount']); ?>">
            </div>
            <div class="form-group">
                <label for="total_after_discount">Total After Discount</label>
                <input type="number" class="form-control" id="total_after_discount" name="total_after_discount" step="0.01" value="<?php echo htmlspecialchars($order['total_after_discount']); ?>">
            </div>
            <div class="form-group">
                <label for="remaining_amount">Remaining Amount</label>
                <input type="number" class="form-control" id="remaining_amount" name="remaining_amount" step="0.01" value="<?php echo htmlspecialchars($order['remaining_amount']); ?>" required>
            </div>
            <div class="form-group">
                <label for="installments">Installments</label>
                <input type="number" class="form-control" id="installments" name="installments" value="<?php echo htmlspecialchars($order['installments']); ?>" required>
            </div>
            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea class="form-control" id="notes" name="notes" rows="4"><?php echo htmlspecialchars($order['notes']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>
