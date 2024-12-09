<?php
include('db.php');

$success_message = "";

// جلب بيانات المستخدم بناءً على المعرف في الرابط
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
} else {
    die("User ID is missing.");
}

// تحديث بيانات المستخدم
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $employee_name = mysqli_real_escape_string($conn, $_POST['employee_name']);
    
    // معالجة تحميل الصورة
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    if (!empty($_FILES["image"]["name"])) {
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    } else {
        $target_file = $user['image'];
    }

    $stmt = $conn->prepare("UPDATE users SET username = ?, password = ?, role = ?, employee_name = ?, image = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $username, $password, $role, $employee_name, $target_file, $user_id);
    $stmt->execute();
    $stmt->close();

  header("Location: users_list.php?message=User updated successfully!");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
         body {
            font-family: 'Arial', sans-serif;
            background: #f7f9fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .logo {
            width: 150px;
            margin-bottom: 1rem;
        }

        h2 {
            color: #333;
            margin-bottom: 1rem;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="text"],
        input[type="password"],
        input[type="file"],
        select {
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        input[type="submit"] {
            padding: 0.75rem;
            background: #007bff;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        input[type="submit"]:hover {
            background: #0056b3;
        }

        @media (max-width: 600px) {
            .container {
                padding: 1rem;
                margin: 0 10px; /* Add margin to avoid touching the screen edges */
            }
            .logo {
                width: 100px;
                margin-bottom: 0.5rem;
            }
            h2 {
                font-size: 20px;
                margin-bottom: 0.5rem;
            }
            input[type="text"],
            input[type="password"],
            input[type="file"],
            select {
                padding: 0.5rem;
                font-size: 0.9rem;
            }
            input[type="submit"] {
                padding: 0.5rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <?php if ($success_message): ?>
            <div id="success-alert" class="alert alert-success" role="alert">
                <?php echo $success_message; ?>
            </div>
            <script>
                setTimeout(function() {
                    var alert = document.getElementById('success-alert');
                    if (alert) {
                        alert.style.display = 'none';
                    }
                }, 3000);
            </script>
        <?php endif; ?>
        <img src="img/log.jpg" alt="Company Logo" class="logo">
        <h2>Edit User</h2>
        <form action="edit_user.php?id=<?php echo $user_id; ?>" method="post" enctype="multipart/form-data">
            <input type="text" name="employee_name" value="<?php echo htmlspecialchars($user['employee_name']); ?>" placeholder="Employee Name" required>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" placeholder="Username" required>
            <input type="password" name="password" placeholder="New Password" required>
            <select name="role" required>
                <option value="">Select Role</option>
                <option value="manager" <?php if ($user['role'] == 'manager') echo 'selected'; ?>>Manager</option>
                <option value="employee" <?php if ($user['role'] == 'employee') echo 'selected'; ?>>Employee</option>
            </select>
            <input type="file" name="image">
            <input type="submit" value="Update User">
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrap.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
