<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/database/db_connect.php');
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login");
    exit();
}

// AJAX isteğini kontrol et
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    // Lisansı sil
    $licenseId = $_POST['id'];
    $stmt = $db->prepare("DELETE FROM licenses WHERE id = :id AND auth = :auth");
    $stmt->bindParam(":id", $licenseId);
    $stmt->bindParam(":auth", $_SESSION['username']);
    $stmt->execute();

    // Silme işlemi başarılı ise başarı durumunu döndür
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
        $stmt = $db->prepare("UPDATE users SET license_right = license_right + 1 WHERE username = :username");
        $stmt->bindParam(":username", $_SESSION['username']);
        $stmt->execute();
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    // POST isteği gelmediyse veya lisans ID'si belirtilmediyse hata döndür
    echo json_encode(['success' => false]);
}
?>
