<?php
include('db.php');

$sql = "SELECT id, employee_name, username FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View QR Codes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        .qr-code {
            display: inline-block;
            margin: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Employee QR Codes</h2>
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $username = htmlspecialchars($row['username']);
                    echo '<div class="col-md-4 text-center">';
                    echo '<h3>' . htmlspecialchars($row['employee_name']) . '</h3>';
                    echo '<div id="qrcode-' . $row['id'] . '" class="qr-code"></div>';
                    echo '<script>
                            new QRCode(document.getElementById("qrcode-' . $row['id'] . '"), "' . $username . '");
                          </script>';
                    echo '</div>';
                }
            } else {
                echo '<p>No employees found.</p>';
            }
            ?>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
