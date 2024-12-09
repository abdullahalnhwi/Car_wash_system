<?php
session_start();
var_dump($_GET);
if (!isset($_SESSION['employee_name'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    echo "No order ID provided.";
    exit();
}

$order_id = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            padding-top: 70px;
        }
        .container {
            margin-top: 20px;
        }
        .qr-code {
            margin: auto;
            width: 200px;
            height: 200px;
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <h2 class="text-center">Order Confirmation</h2>
        <p>Thank you for your order. Here are the details:</p>
        <ul>
            <li>Order ID: <?php echo $order_id; ?></li>
            <li>Employee: <?php echo $_SESSION['employee_name']; ?></li>
        </ul>
        <div id="qrcode" class="qr-code"></div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        // توليد QR code
        var qrUrl = "https://yourwebsite.com/order_details.php?id=<?php echo $order_id; ?>";
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: qrUrl,
            width: 200,
            height: 200
        });
    </script>
</body>
</html>
