<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/database/db_connect.php');
session_start();

if(isset($_SESSION["username"])) {
    header("Location: dashboard");
    exit();
}

$stmt = $db->query("SELECT * FROM site_settings");
$site_settings = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["email"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        $user_ip = $_SERVER['REMOTE_ADDR']; // Kullanıcının IP adresini al

        // Rastgele müşteri kodu oluştur
        $customer_code = uniqid();

        // Şifreyi hashle
        $hashed_password = hash('sha256', $password);

        // Kullanıcıyı veritabanına ekle
        try {
            $stmt = $db->prepare("INSERT INTO users (username, password, email, customer_code, ip_address, account_status) VALUES (:username, :password, :email, :customer_code, :ip_address, 'pending')");
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":password", $hashed_password);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":customer_code", $customer_code);
            $stmt->bindParam(":ip_address", $user_ip);
            $stmt->execute();

            // Kayıt başarılı, oturumu başlat ve dashboard'a yönlendir
            header("Location: /login");
            exit();
        } catch(PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                // Kullanıcı adı veya e-posta adresi zaten kullanılıyor
                if(strpos($e->getMessage(), 'username') !== false) {
                    $error_message = "Bu kullanıcı adı zaten kullanılıyor. Lütfen başka bir kullanıcı adı deneyin.";
                } elseif(strpos($e->getMessage(), 'email') !== false) {
                    $error_message = "Bu e-posta adresi zaten kullanılıyor. Lütfen başka bir e-posta adresi deneyin.";
                } else {
                    $error_message = "Bir hata oluştu, lütfen daha sonra tekrar deneyin.";
                }
            } else {
                // Diğer hata durumları
                $error_message = "Bir hata oluştu, lütfen daha sonra tekrar deneyin.";
            }
        }
    } else {
        // POST verisi eksik, hata mesajı ver
        $error_message = "Kullanıcı adı, şifre veya e-posta eksik.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../assest/css/pages/login.css"> 
    <title><?php echo $site_settings['site_name']; ?></title>
</head>
<body>
    <div class="container">
        <h2><?php echo $site_settings['site_name']; ?></h2>
        <form id="registerForm" method="post">
            <label for="username">Kullanıcı Adı:</label>
            <input type="text" id="username" name="username" placeholder="Kullanıcı Adı" required>
            
            <label for="email">E-posta Adresi:</label>
            <input type="email" id="email" name="email" placeholder="E-posta Adresi" required>
            
            <label for="password">Şifre:</label>
            <input type="password" id="password" name="password" placeholder="Şifre" required>

            <label for="confirm_password">Şifreyi Onayla:</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Şifreyi Onayla" required>

            <input type="submit" id="registerButton" value="Kayıt Ol">
            <p>Zaten bir hesabınız var mı? <a href="login.php" style="text-decoration: none;">Giriş yapın</a></p>
        </form>
        <?php 
        if(isset($error_message)) { 
            echo '<p class="error-message">' . $error_message . '</p>';
        } 
        ?>
    </div>
</body>
</html>
