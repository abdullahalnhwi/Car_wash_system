<?php
include('db.php'); // تأكد من تضمين ملف الاتصال بقاعدة البيانات

$service = null; // تعريف المتغير $service

// جلب بيانات الخدمة المطلوبة
if (isset($_GET['id'])) {
    $service_id = intval($_GET['id']); // تأكد من أن معرف الخدمة هو عدد صحيح
    // طباعة معرف الخدمة للتأكد من أنه يتم تمريره بشكل صحيح
    echo "Service ID: " . htmlspecialchars($service_id) . "<br>";

    $sql = "SELECT * FROM services WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $service_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $service = $result->fetch_assoc();

        // طباعة بيانات الخدمة للتأكد من جلبها بشكل صحيح
        if ($service) {
            echo "Service Data: ";
            print_r($service);
        } else {
            echo "Service not found in the database.";
        }
    } else {
        echo "Failed to prepare the SQL statement.";
    }
} else {
    echo "No service ID provided.";
}

// جلب الفئات الحالية
$sql = "SELECT * FROM categories";
$categories_result = $conn->query($sql);

// تحديث بيانات الخدمة
if (isset($_POST['update_service'])) {
    $service_id = intval($_POST['service_id']);
    $category_id = intval($_POST['category_id']);
    $service_name = $_POST['service_name'];
    $service_price = floatval($_POST['service_price']);
    $car_type = $_POST['car_type'];
    $sql = "UPDATE services SET category_id = ?, name = ?, price = ?, car_type = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isdis", $category_id, $service_name, $service_price, $car_type, $service_id);
    $stmt->execute();
    header('Location: manage.php'); // إعادة التوجيه إلى صفحة الإدارة بعد التحديث
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service</title>
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
            max-width: 500px;
            margin: 20px auto;
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="form-container">
        <h2>Edit Service</h2>
        <?php if ($service): ?>
        <form action="" method="POST">
            <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
            <div class="form-group">
                <label for="category_id">Select Category</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <option value="">Select Category</option>
                    <?php while($row = $categories_result->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $service['category_id']) ? 'selected' : ''; ?>><?php echo $row['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="service_name">Service Name</label>
                <input type="text" class="form-control" id="service_name" name="service_name" value="<?php echo $service['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="service_price">Service Price</label>
                <input type="number" class="form-control" id="service_price" name="service_price" value="<?php echo $service['price']; ?>" step="0.001" required>
            </div>
            <div class="form-group">
                <label for="car_type">Car Type</label>
                <select class="form-control" id="car_type" name="car_type" required>
                    <option value="">Select Car Type</option>
                    <option value="saloon" <?php echo ($service['car_type'] == 'saloon') ? 'selected' : ''; ?>>Saloon</option>
                    <option value="4wheel" <?php echo ($service['car_type'] == '4wheel') ? 'selected' : ''; ?>>4 Wheel</option>
                </select>
            </div>
            <button type="submit" name="update_service" class="btn btn-primary">Update Service</button>
        </form>
        <?php else: ?>
        <p>Service not found. Please go back and try again.</p>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
