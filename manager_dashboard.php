<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'manager') {
    header("Location: login.php");
    exit;
}
include('db.php');

if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manager_dashboard.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $employee_name = mysqli_real_escape_string($conn, $_POST['employee_name']);
    
    // معالجة تحميل الصورة
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET username = ?, password = ?, role = ?, employee_name = ?, image = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $username, $password, $role, $employee_name, $target_file, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET username = ?, role = ?, employee_name = ?, image = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $username, $role, $employee_name, $target_file, $user_id);
    }
    
    $stmt->execute();
    $stmt->close();
    header("Location: manager_dashboard.php");
}

$users = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manager Dashboard</title>
</head>
<body>
    <h2>Welcome, Manager!</h2>
    <p>This is the manager dashboard.</p>
    <a href="logout.php">Logout</a>
    <h3>Manage Users</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Employee Name</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php while ($user = $users->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['role']; ?></td>
            <td><?php echo $user['employee_name']; ?></td>
            <td><img src="<?php echo $user['image']; ?>" alt="User Image" width="50" height="50"></td>
            <td>
                <a href="manager_dashboard.php?delete=<?php echo $user['id']; ?>">Delete</a>
                <button onclick="document.getElementById('editForm<?php echo $user['id']; ?>').style.display='block'">Edit</button>
                <div id="editForm<?php echo $user['id']; ?>" style="display:none;">
                    <form action="manager_dashboard.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <input type="text" name="username" value="<?php echo $user['username']; ?>" required><br>
                        <input type="text" name="employee_name" value="<?php echo $user['employee_name']; ?>" required><br>
                        <select name="role" required>
                            <option value="manager" <?php if($user['role'] == 'manager') echo 'selected'; ?>>Manager</option>
                            <option value="employee" <?php if($user['role'] == 'employee') echo 'selected'; ?>>Employee</option>
                        </select><br>
                        <input type="file" name="image"><br>
                        <input type="password" name="password" placeholder="New Password"><br>
                        <input type="submit" name="update_user" value="Update">
                    </form>
                </div>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
