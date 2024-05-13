<?php
// Sayfaya doğrudan erişimi kontrol et
if (!isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] == "") {
    // Eğer referer headerı yoksa veya boşsa, erişimi reddet
    header("HTTP/1.1 403 Forbidden");
    die("Bu sayfaya doğrudan erişim yasaktır.");
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/database/db_connect.php');

// Gelen IP adresini al
if(isset($_POST['ip_address'])){
    $ipAddress = $_POST['ip_address'];

    // Lisansı sil
    $sql = "DELETE FROM licenses WHERE ip_address = ?";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(1, $ipAddress);
    
    if ($stmt->execute()) {
        echo "Lisans başarıyla silindi.";
    } else {
        echo "Lisans silinirken hata oluştu: " . $stmt->errorInfo()[2];
    }
} else {
    echo "IP adresi post edilmedi.";
}

// Veritabanı bağlantısını kapat
$db = null;
?>
