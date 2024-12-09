<?php
include('db.php'); // تأكد من تضمين ملف الاتصال بقاعدة البيانات

if (isset($_GET['id'])) {
    $service_id = intval($_GET['id']); // تأكد من أن معرف الخدمة هو عدد صحيح

    // حذف الخدمة
    $sql_delete_service = "DELETE FROM services WHERE id = ?";
    $stmt_service = $conn->prepare($sql_delete_service);
    if ($stmt_service) {
        $stmt_service->bind_param("i", $service_id);
        $stmt_service->execute();
    }

    // إعادة التوجيه إلى صفحة الإدارة بعد الحذف
    header('Location: manage_categories_services.php');
} else {
    echo "No service ID provided.";
}
?>
