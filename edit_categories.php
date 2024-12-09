<?php
include('db.php'); // تأكد من تضمين ملف الاتصال بقاعدة البيانات

$category = null; // تعريف المتغير $category

// جلب بيانات الفئة المطلوبة
if (isset($_GET['id'])) {
    $category_id = intval($_GET['id']); // تأكد من أن معرف الفئة هو عدد صحيح
    $sql = "SELECT * FROM categories WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $category = $result->fetch_assoc();
    } else {
        echo "Failed to prepare the SQL statement.";
    }
} else {
    echo "No category ID provided.";
}

// تحديث بيانات الفئة
if (isset($_POST['update_category'])) {
    $category_id = intval($_POST['category_id']);
    $category_name = $_POST['category_name'];
    $service_level = $_POST['service_level'];
    $sql = "UPDATE categories SET name = ?, service_level = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $category_name, $service_level, $category_id);
    $stmt->execute();
    header('Location: manage.php'); // إعادة التوجيه إلى صفحة الإدارة بعد التحديث
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
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
        <h2>Edit Category</h2>
        <?php if ($category): ?>
        <form action="" method="POST">
            <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category_name" value="<?php echo $category['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="service_level">Service Level</label>
                <select class="form-control" id="service_level" name="service_level" required>
                    <option value="simple" <?php if($category['service_level'] == 'simple') echo 'selected'; ?>>Simple</option>
                    <option value="special" <?php if($category['service_level'] == 'special') echo 'selected'; ?>>Special</option>
                </select>
            </div>
            <button type="submit" name="update_category" class="btn btn-primary">Update Category</button>
        </form>
        <?php else: ?>
        <p>Category not found. Please go back and try again.</p>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
