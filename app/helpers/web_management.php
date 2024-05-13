<?php
// Veritabanı bağlantısı
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/database/db_connect.php');

// Oturumu başlatma
if (!isset($_SESSION)) {
    session_start();
}

// Oturumdan kullanıcı adını ve IP adresini al
if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // Veritabanında kullanıcı adını ve IP adresini sorgula
    $query = "SELECT account_status FROM users WHERE username = ? OR ip_address = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$username, $ip_address]);

    // Sorgunun başarısız olup olmadığını kontrol et
    if (!$stmt) {
        exit; // veya die();
    }

    // Sonucu al
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $status = $row['account_status'];

    // ENUM değeri pending ise /pending sayfasına yönlendir
    if ($status == 'pending') {
        header("Location: /pending");
        exit;
    }
} else {
    // Oturum başlatılmamışsa, bir hata veya uyarı mesajı gösterebilirsiniz.
}
?>
