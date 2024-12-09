<?php
include('db.php'); // تأكد من تضمين ملف الاتصال بقاعدة البيانات

if (isset($_GET['id'])) {
    $category_id = intval($_GET['id']); // تأكد من أن معرف الفئة هو عدد صحيح

    // حذف الخدمات المرتبطة بالفئة أولاً
    $sql_delete_services = "DELETE FROM services WHERE category_id = ?";
    $stmt_services = $conn->prepare($sql_delete_services);
    if ($stmt_services) {
        $stmt_services->bind_param("i", $category_id);
        $stmt_services->execute();
    }

    // ثم حذف الفئة
    $sql_delete_category = "DELETE FROM categories WHERE id = ?";
    $stmt_category = $conn->prepare($sql_delete_category);
    if ($stmt_category) {
        $stmt_category->bind_param("i", $category_id);
        $stmt_category->execute();
    }

    // إعادة التوجيه إلى صفحة الإدارة بعد الحذف
    header('Location: manage_categories_services.php');
} else {
    echo "No category ID provided.";
}
?>
