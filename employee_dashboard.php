<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'employee') {
    header("Location: login.php");
    exit;
}
include('db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include('nav.php'); ?>

    <div class="container">
        <div class="welcome">
            <h2>Welcome, <?php echo $_SESSION['employee_name']; ?>!</h2>
            <p>This is your employee dashboard.</p>
        </div>
    </div>
</body>
</html>
