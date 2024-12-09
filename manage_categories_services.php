<?php
include('db.php'); // تأكد من تضمين ملف الاتصال بقاعدة البيانات

// جلب جميع الفئات والخدمات
$sql_categories = "SELECT * FROM categories";
$result_categories = $conn->query($sql_categories);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories and Services</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            margin-top: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <h2 class="text-center">Manage Categories and Services</h2>
        <?php while($category = $result_categories->fetch_assoc()): ?>
            <div class="card">
                <div class="card-header">
                    <h3><?php echo $category['name']; ?></h3>
                    <a href="edit_categories.php?id=<?php echo $category['id']; ?>" class="btn btn-primary btn-sm">Edit Category</a>
                    <a href="delete_category.php?id=<?php echo $category['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category?')">Delete Category</a>
                    <form action="update_access.php" method="post" style="display: inline;">
                        <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                        <select name="access" onchange="this.form.submit()">
                            <option value="manager" <?php if($category['access'] == 'manager') echo 'selected'; ?>>Manager Only</option>
                            <option value="employee" <?php if($category['access'] == 'employee') echo 'selected'; ?>>Employee</option>
                        </select>
                    </form>
                </div>
                <div class="card-body">
                    <h5>Services</h5>
                    <?php
                    $sql_services = "SELECT * FROM services WHERE category_id = " . $category['id'];
                    $result_services = $conn->query($sql_services);
                    ?>
                    <?php if ($result_services->num_rows > 0): ?>
                        <ul class="list-group">
                            <?php while($service = $result_services->fetch_assoc()): ?>
                                <li class="list-group-item">
                                    <?php echo $service['name']; ?> - <?php echo $service['price']; ?> - <?php echo $service['car_type']; ?>
                                    <a href="edit_service.php?id=<?php echo $service['id']; ?>" class="btn btn-primary btn-sm float-right">Edit Service</a>
                                    <a href="delete_service.php?id=<?php echo $service['id']; ?>" class="btn btn-danger btn-sm float-right mr-2" onclick="return confirm('Are you sure you want to delete this service?')">Delete Service</a>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p>No services available for this category.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
