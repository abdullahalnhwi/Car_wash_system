<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// ضبط التوقيت لمنطقة مسقط
date_default_timezone_set('Asia/Muscat');

$employee_name = isset($_SESSION['employee_name']) ? $_SESSION['employee_name'] : 'Guest';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $qr_code = isset($_POST['qr_result']) ? trim($_POST['qr_result']) : '';

    // البحث عن المستخدم في جدول users بناءً على الـ QR code (username)
    $sql = "SELECT id, employee_name FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $qr_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['id'];
        $employee_name = $user['employee_name'];
        $current_time = date('Y-m-d H:i:s'); // تسجيل الوقت بتوقيت مسقط

        // تسجيل الحضور
        $sql = "INSERT INTO attendance (user_id, check_in_time) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $user_id, $current_time);
        if ($stmt->execute()) {
            $success_message = "Attendance recorded successfully for $employee_name.";
        } else {
            $error_message = "Error recording attendance.";
        }
        $stmt->close();
    } else {
        $error_message = "User not found for the provided QR code.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scan QR Code</title>
    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode/minified/html5-qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.34/moment-timezone-with-data.min.js"></script>
    <style>
        #reader {
            width: 100%;
            height: 400px;
        }
    </style>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($employee_name); ?></h1>
    <div id="reader"></div>
    <form method="POST" action="">
        <input type="hidden" id="qr_result" name="qr_result">
        <input type="hidden" id="current_time" name="current_time">
        <button type="submit">Submit</button>
    </form>
    <?php if (isset($success_message)): ?>
        <p><?php echo $success_message; ?></p>
    <?php endif; ?>
    <?php if (isset($error_message)): ?>
        <p><?php echo $error_message; ?></p>
    <?php endif; ?>

    <script>
        function onScanSuccess(qrCodeMessage) {
            console.log("Scanned QR Code: ", qrCodeMessage); // تحقق من مسح رمز QR
            document.getElementById('qr_result').value = qrCodeMessage;
            var now = moment().tz("Asia/Muscat").format('YYYY-MM-DD HH:mm:ss');
            document.getElementById('current_time').value = now;
            document.forms[0].submit();
        }

        function onScanError(errorMessage) {
            console.error("Scan Error: ", errorMessage); // عرض رسالة خطأ في حالة فشل المسح
        }

        // إعداد الكاميرا لتشغيل الكاميرا الخلفية افتراضياً
        const html5QrCode = new Html5Qrcode("reader");
        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                let backCameraId = devices.find(device => device.label.toLowerCase().includes('back'));
                let cameraId = backCameraId ? backCameraId.id : devices[0].id; // إذا لم يتم العثور على كاميرا خلفية، استخدم الكاميرا الأولى المتاحة

                // بدء المسح باستخدام الكاميرا الخلفية
                html5QrCode.start(
                    cameraId, 
                    {
                        fps: 10,    // عدد الإطارات في الثانية
                        qrbox: { width: 250, height: 250 }  // حجم مربع QR
                    },
                    onScanSuccess,
                    onScanError)
                .catch(err => {
                    console.error("Error starting camera: ", err);
                });
            } else {
                console.error("No cameras found.");
            }
        }).catch(err => {
            console.error("Error getting cameras: ", err);
        });
    </script>
</body>
</html>
