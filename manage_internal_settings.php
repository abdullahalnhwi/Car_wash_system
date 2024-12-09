<?php
include('db.php');
session_start();

if (!isset($_SESSION['employee_name'])) {
    header('Location: login.php');
    exit();
}

$external_id = $_GET['external_id'];

// جلب جميع الإعدادات الداخلية المرتبطة بالإعداد الخارجي
$sql = "SELECT * FROM internal_settings WHERE external_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $external_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Internal Settings</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            margin-top: 50px;
        }
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .card {
            flex: 1 1 200px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }
        .card img {
            width: 100%;
            height: 240px;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 0;
            opacity: 1;
        }
        .card-body {
            position: relative;
            z-index: 1;
        }
        .card-title {
            margin-top: 150px;
            background: rgba(255, 255, 255, 0.8);
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <h3>Manage Internal Settings</h3>
        <a href="add_internal_setting.php?external_id=<?php echo $external_id; ?>" class="btn btn-primary mb-3">Add Internal Setting</a>
        <div class="card-container">
            <?php while ($setting = $result->fetch_assoc()): ?>
                <div class="card">
                    <img src="<?php echo htmlspecialchars($setting['image']); ?>" alt="<?php echo htmlspecialchars($setting['name']); ?>">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?php echo htmlspecialchars($setting['name']); ?></h5>
                        <a href="<?php echo htmlspecialchars($setting['link']); ?>" class="btn btn-primary"><?php echo htmlspecialchars($setting['name']); ?></a>
                        <a href="edit_internal_setting.php?id=<?php echo $setting['id']; ?>&external_id=<?php echo $external_id; ?>" class="btn btn-warning">Edit</a>
                        <a href="delete_internal_setting.php?id=<?php echo $setting['id']; ?>&external_id=<?php echo $external_id; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this setting?')">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
