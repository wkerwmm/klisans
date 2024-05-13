<?php
// Veritabanı bağlantısı
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/database/db_connect.php');

// Oturumu başlatma
if (!isset($_SESSION)) {
    session_start();
}

// Kullanıcı cezasını kontrol etme fonksiyonu
function checkUserPunishment($username, $ip_address, $db) {
    // Kullanıcı adını ve IP adresini kullanarak cezaları sorgula
    $query = "SELECT * FROM punishments WHERE username = ? OR ip_address = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$username, $ip_address]);

    // Sorgunun başarılı olup olmadığını kontrol et
    if ($stmt->rowCount() > 0) {
        // Kullanıcıya uygulanan cezaları kontrol et
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $punishment_type = $row['punishment_type'];
            $start_date = strtotime($row['start_date']);
            $end_date = strtotime($row['end_date']);
            $current_time = time();

            // Cezanın bitiş tarihini kontrol et (varsa)
            if ($end_date == 0 || ($end_date != 0 && $current_time >= $start_date && $current_time <= $end_date)) {
                // Cezanın bitiş tarihi 0000-00-00 veya henüz gelmemişse ceza devam ediyor
                header("Location: banned");
                return true;
            }
        }
    }

    // Cezası bitmişse veya hiç cezası yoksa devam et
    return false;
}

// Kullanıcı adını ve IP adresini al
if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // Kullanıcı cezasını kontrol et
    if (checkUserPunishment($username, $ip_address, $db)) {
        // Kullanıcı cezalı olduğu için işlemi durdur
        exit;
    }
} else {
    // Oturum başlatılmamışsa, bir hata veya uyarı mesajı gösterebilirsiniz.
}
?>
