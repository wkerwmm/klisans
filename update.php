<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veritabanı Güncelleme Formu</title>
</head>
<body>
    <h2>Veritabanı Güncelleme Formu</h2>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="table">Tablo Adı:</label>
        <input type="text" id="table" name="table" required><br><br>
        
        <label for="column">Sütun Adı:</label>
        <input type="text" id="column" name="column" required><br><br>
        
        <label for="new_value">Güncellenecek Yeni Değer:</label>
        <input type="text" id="new_value" name="new_value" required><br><br>
        
        <button type="submit">Güncelle</button>
    </form>

    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $host = "localhost"; // Veritabanı sunucusunun adresi
    $dbname = "vxtpfztp_licence"; // Veritabanı adı
    $username = "vxtpfztp_licence"; // Veritabanı kullanıcı adı
    $password = "4oWoTkCa1CQW"; // Veritabanı şifresi

    try {
        // Veritabanına bağlan
        $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Form gönderildiğinde
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Güncelleme sorgusunu hazırla
            $table = isset($_POST['table']) ? $_POST['table'] : "";
            $column = isset($_POST['column']) ? $_POST['column'] : "";
            $new_value = isset($_POST['new_value']) ? $_POST['new_value'] : "";

            if (!empty($table) && !empty($column) && !empty($new_value)) {
                $sql = "UPDATE $table SET $column = :new_value";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':new_value', $new_value);
                $stmt->execute();

                if($stmt->rowCount() > 0) {
                    echo "Veri başarıyla güncellendi.";
                } else {
                    echo "Güncelleme yapılamadı: Hedef sütun değeri bulunamadı.";
                }
            } else {
                echo "Lütfen tablo adı, sütun adı ve yeni değeri giriniz.";
            }
        }
    } catch(PDOException $e) {
        die("Hata oluştu: " . $e->getMessage());
    }
    ?>
</body>
</html>
