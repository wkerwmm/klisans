<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/database/db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen verileri al
    $ip_address = $_POST['ip_address'] ?? '';
    $expiration_date = $_POST['expiration_date'] ?? '';

    // IP adresinin veritabanında var olup olmadığını kontrol et
    $check_sql = "SELECT * FROM licenses WHERE ip_address = ?";
    $check_stmt = $db->prepare($check_sql);
    $check_stmt->execute([$ip_address]);
    $check_result = $check_stmt->fetch(PDO::FETCH_ASSOC);

    if ($check_result) {
        // IP adresi zaten veritabanında varsa hata mesajı göster
        echo "Hata: Bu IP adresi zaten lisanslı!";
    } else {
        // IP adresi veritabanında yoksa lisansı ekleyin
        $insert_sql = "INSERT INTO licenses (ip_address, expiration_date) VALUES (?, ?)";
        $insert_stmt = $db->prepare($insert_sql);

        if ($insert_stmt->execute([$ip_address, $expiration_date])) {
            // Başarılı ekleme mesajı göster
            echo "Başarılı: Lisans başarıyla oluşturuldu.";
        } else {
            // Hata mesajı göster
            echo "Hata: Lisans eklenirken bir sorun oluştu.";
        }
    }
} else {
    // Post yapılmadığı durumda işlem yapılmasını engellemek için herhangi bir çıktı vermeyin
    // veya isteği reddedin
    // Örneğin:
    // header("HTTP/1.1 403 Forbidden");
     die("Bu sayfaya doğrudan erişim yasaktır.");
}

// Veritabanı bağlantısını kapat
$db = null;
?>
