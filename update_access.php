<?php
include('db.php');

if (isset($_POST['category_id']) && isset($_POST['access'])) {
    $category_id = $_POST['category_id'];
    $access = $_POST['access'];

    $sql = "UPDATE categories SET access = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $access, $category_id);

    if ($stmt->execute()) {
        header('Location: manage_categories_services.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
