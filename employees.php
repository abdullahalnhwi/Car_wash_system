<?php
include('db.php');
session_start();

if (!isset($_SESSION['employee_name']) || $_SESSION['role'] != 'manager') {
    header('Location: login.php');
    exit();
}

$sql = "SELECT id, employee_name, image FROM users WHERE role IN ('employee', 'manager')";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            width: 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .employee {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            cursor: pointer;
        }
        .employee img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 20px;
        }
        .employee-name {
            font-size: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include 'nav.php'?>
    <div class="container">
        <h2>Employees</h2>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="employee" onclick="window.location.href='employee_details.php?id=<?php echo $row['id']; ?>'">
                <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Employee Image">
                <div class="employee-name"><?php echo htmlspecialchars($row['employee_name']); ?></div>
            </div>
        <?php endwhile; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
