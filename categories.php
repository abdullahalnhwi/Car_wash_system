<?php
include('db.php'); // تأكد من تضمين ملف الاتصال بقاعدة البيانات

// التحقق مما إذا كان العمود service_level موجودًا في جدول categories
$check_column_sql = "SHOW COLUMNS FROM categories LIKE 'service_level'";
$check_column_result = $conn->query($check_column_sql);

if ($check_column_result->num_rows == 0) {
    // إضافة عمود service_level إلى جدول categories إذا لم يكن موجودًا
    $alter_table_sql = "ALTER TABLE categories ADD COLUMN service_level ENUM('simple', 'special') NOT NULL DEFAULT 'simple'";
    $conn->query($alter_table_sql);
}

// إضافة فئة جديدة
if (isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    $service_level = $_POST['service_level']; // إضافة مستوى الخدمة هنا
    $access = $_POST['access']; // إضافة صلاحية الوصول هنا
    $sql = "INSERT INTO categories (name, service_level, access) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $category_name, $service_level, $access);
    $stmt->execute();
}

// إضافة خدمة جديدة
if (isset($_POST['add_service'])) {
    $category_id = $_POST['category_id'];
    $service_name = $_POST['service_name'];
    $service_price = $_POST['service_price'];
    $car_type = $_POST['car_type'];
    $sql = "INSERT INTO services (category_id, name, price, car_type) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isds", $category_id, $service_name, $service_price, $car_type);
    $stmt->execute();
}

// جلب الفئات الحالية
$sql = "SELECT * FROM categories";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories and Services</title>
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
        <h2>Add Category</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category_name" required>
            </div>
            <div class="form-group">
                <label for="service_level">Service Level</label>
                <select class="form-control" id="service_level" name="service_level" required>
                    <option value="simple">Simple</option>
                    <option value="special">Special</option>
                </select>
            </div>
            <div class="form-group">
                <label for="access">Access Level</label>
                <select class="form-control" id="access" name="access" required>
                    <option value="manager">Manager Only</option>
                    <option value="employee">Employee</option>
                </select>
            </div>
            <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Add Service</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="category_id">Select Category</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <option value="">Select Category</option>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="service_name">Service Name</label>
                <input type="text" class="form-control" id="service_name" name="service_name" required>
            </div>
            <div class="form-group">
                <label for="service_price">Service Price</label>
                <input type="number" class="form-control" id="service_price" name="service_price" step="0.001" required>
            </div>
            <div class="form-group">
                <label for="car_type">Car Type</label>
                <select class="form-control" id="car_type" name="car_type" required>
                    <option value="">Select Car Type</option>
                    <option value="saloon">Saloon</option>
                    <option value="4wheel">4 Wheel</option>
                </select>
            </div>
            <button type="submit" name="add_service" class="btn btn-primary">Add Service</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
