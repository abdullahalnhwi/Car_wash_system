<?php
require 'vendor/autoload.php';
include('db.php');

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

function generateQRCode($text) {
    $qrCode = QrCode::create($text)
        ->setEncoding(new \Endroid\QrCode\Encoding\Encoding('UTF-8'))
        ->setErrorCorrectionLevel(new \Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow())
        ->setSize(300);
        
    $writer = new PngWriter();
    $result = $writer->write($qrCode);

    // حفظ QR Code كصورة
    $filename = 'qrcodes/' . uniqid() . '.png';
    $result->saveToFile($filename);

    return $filename;
}

// توليد QR لكل موظف
$sql = "SELECT id, username FROM users WHERE qr_code IS NULL";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $userId = $row['id'];
        $username = $row['username'];

        // توليد QR Code
        $qrCodePath = generateQRCode($username);

        // تحديث قاعدة البيانات برمز QR
        $updateSql = "UPDATE users SET qr_code = ? WHERE id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("si", $qrCodePath, $userId);
        $stmt->execute();
    }
    echo "QR codes generated successfully.";
} else {
    echo "No users found without QR codes.";
}

$conn->close();
?>
