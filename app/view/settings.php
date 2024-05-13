<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/database/db_connect.php');

// Kullanıcının oturum açıp açmadığını kontrol et
if(!isset($_SESSION["username"])) {
    header("Location: login");
    exit();
}

// Oturum açmış kullanıcının kullanıcı adını al
$username = $_SESSION["username"];

// Site ayarlarını veritabanından al
$stmt = $db->query("SELECT * FROM site_settings");
$site_settings = $stmt->fetch(PDO::FETCH_ASSOC);

// Değişiklik kontrolü için mevcut site ayarlarını bir değişkende saklayalım
$current_site_name = $site_settings['site_name'];
$current_site_description = $site_settings['site_description'];

// Form gönderilmiş mi kontrol edelim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen verileri al
    $site_name = $_POST["site_name"];
    $site_description = $_POST["site_description"];

    // Kullanıcının yaptığı değişiklikleri kontrol edelim
    if ($site_name != $current_site_name || $site_description != $current_site_description) {
        // Değişiklik yapılmış, veritabanında güncelleme yapalım
        $stmt = $db->prepare("UPDATE site_settings SET site_name = :site_name, site_description = :site_description WHERE id = 1");
        $stmt->bindParam(":site_name", $site_name);
        $stmt->bindParam(":site_description", $site_description);
        $stmt->execute();

        // Başarılı bir şekilde güncelleme yapıldıktan sonra kullanıcıyı yönlendir
        $success_message = "Ayarlar başarıyla kaydedildi.";

        // SweetAlert2 ile başarılı kayıt mesajını göster
        echo '<script>
                Swal.fire({
                    title: "Başarılı!",
                    text: "' . $success_message . '",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 3000
                }).then(() => {
                    window.location.href = "settings.php";
                });
              </script>';
    } else {
        // Değişiklik yapılmamış, kullanıcıya uyarı verelim
        echo '<script>
                Swal.fire({
                    title: "Değişiklik yok",
                    text: "Herhangi bir değişiklik yapmadınız.",
                    icon: "info",
                    showConfirmButton: true
                });
              </script>';
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Ayarları</title>
    <!-- SweetAlert2 CSS dosyası -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Site Ayarları</h2>

    <form id="myForm" method="post">
        <label for="site_name">Site Adı:</label>
        <input type="text" id="site_name" name="site_name" value="<?php echo $site_settings['site_name']; ?>" required>

        <label for="site_description">Site Açıklaması:</label>
        <textarea id="site_description" name="site_description" rows="4" cols="50"><?php echo $site_settings['site_description']; ?></textarea>

        <input type="submit" value="Kaydet">
    </form>
</div>

<!-- SweetAlert2 JavaScript dosyası -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("myForm").addEventListener("submit", function(event) {
            event.preventDefault();
            var form = this;

            Swal.fire({
                title: "Değişiklikleri kaydetmek istiyor musunuz?",
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "Kaydet",
                denyButtonText: "Kaydetme",
                cancelButtonText: "İptal"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                } else if (result.isDenied || result.isDismissed) {
                    Swal.fire("Değişiklikler kaydedilmedi", "", "info");
                }
            });
        });
    });
</script>

</body>
</html>
