<?php
$host = ""; // Veritabanı sunucusunun adresi
$dbname = ""; // Veritabanı adı
$username = ""; // Veritabanı kullanıcı adı
$password = ""; // Veritabanı şifresi

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Veritabanı bağlantısı başarısız: " . $e->getMessage());
}
?>
