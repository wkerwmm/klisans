<?php
// Veritabanı bağlantı dosyasını çekme
require_once '..app/system/database/db_connect.php';

// Başlık ve meta bilgilerini veritabanından çekme
$sql = "SELECT * FROM site_settings";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $pageTitle = $row["page_title"];
    $metaDescription = $row["meta_description"];
    $metaKeywords = $row["meta_keywords"];
} else {
    // Eğer site ayarları bulunamazsa varsayılan değerler kullanılabilir
    $pageTitle = "Varsayılan Başlık";
    $metaDescription = "Varsayılan meta açıklaması";
    $metaKeywords = "anahtar kelime, varsayılan";
}

// Veritabanı bağlantısını kapat
$conn->close();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="<?php echo $metaDescription; ?>">
    <meta name="keywords" content="<?php echo $metaKeywords; ?>">
</head>
<body>
