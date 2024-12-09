<?php
include('db.php');

// ضبط التوقيت لمنطقة مسقط
date_default_timezone_set('Asia/Muscat');

// جلب سجلات الحضور من قاعدة البيانات
$sql = "SELECT u.employee_name, a.check_in_time FROM attendance a JOIN users u ON a.user_id = u.id ORDER BY a.check_in_time DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Attendance</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            margin-top: 50px;
        }
        table {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Employee Attendance</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Employee Name</th>
                    <th>Check-in Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // تحويل الوقت لتنسيق مناسب حسب توقيت مسقط
                        $formatted_time = date('Y-m-d h:i:s A', strtotime($row['check_in_time']));
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['employee_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($formatted_time) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No attendance records found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
