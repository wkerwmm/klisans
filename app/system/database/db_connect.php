<?php
$host = "localhost"; // Veritabanı sunucusunun adresi
$dbname = "vxtpfztp_licence"; // Veritabanı adı
$username = "root"; // Veritabanı kullanıcı adı
$password = ""; // Veritabanı şifresi

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Veritabanı bağlantısı başarısız: " . $e->getMessage());
}
?>
