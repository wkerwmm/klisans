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
    if(isset($_POST["login"]) && isset($_POST["password"]) && isset($_POST["customer_code"])) {
        $login = $_POST["login"];
        $password = $_POST["password"];
        $customer_code = $_POST["customer_code"];
        $user_ip = $_SERVER['REMOTE_ADDR']; // Get user IP address

        // Check login (username or email) and password
        $hashed_password = hash('sha256', $password);
        $stmt = $db->prepare("SELECT * FROM users WHERE (username = :login OR email = :login) AND password = :password");
        $stmt->bindParam(":login", $login);
        $stmt->bindParam(":password", $hashed_password); 
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Login and password are correct, check customer code
            $stmt = $db->prepare("SELECT * FROM users WHERE (username = :login OR email = :login) AND customer_code = :customer_code");
            $stmt->bindParam(":login", $login);
            $stmt->bindParam(":customer_code", $customer_code); 
            $stmt->execute();
            $user_with_code = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user_with_code) {
                // Customer code matches, check enum status
                $enum_status = $user_with_code['account_status'];

                if ($enum_status == 'pending') {
                    header("Location: /pending");
                    exit();
                } elseif ($enum_status == 'approved') {
                    $_SESSION["username"] = $user["username"];
                    header("Location: /dashboard");
                    exit();
                } elseif ($enum_status == 'banned') {
                    header("Location: /banned");
                    exit();
                }
            } else {
                $error_message = "Müşteri kodu yanlış.";
            }
        } else {
            $error_message = "Kullanıcı adı, email veya şifre yanlış.";
        }

        // Log unsuccessful login attempt
        $log_message = "Hesabınıza izinsiz giriş yapılıyor, giriş yapmaya çalıştığı bilgiler: $login, Müşteri Kodu: $customer_code from İP: $user_ip";
        $stmt = $db->prepare("INSERT INTO login_logs (username, customer_code, ip_address, log_message) VALUES (:login, :customer_code, :ip_address, :log_message)");
        $stmt->bindParam(":login", $login);
        $stmt->bindParam(":customer_code", $customer_code);
        $stmt->bindParam(":ip_address", $user_ip);
        $stmt->bindParam(":log_message", $log_message);
        $stmt->execute();
    } else {
        $error_message = "Kullanıcı adı, email, şifre veya müşteri kodu eksik.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../assest/css/pages/login.css"> 
    <title><?php echo $site_settings['site_name']; ?> - Giriş Yap</title>
</head>
<body>
    <div class="container">
        <h2><?php echo $site_settings['site_name']; ?></h2>
        <form id="loginForm" method="post">
            <p>Bu sayfaya sadece kullanıcılar erişebilir.</p>
            <label for="customer_code">Müşteri Kodu:</label>
            <input type="text" id="customer_code" name="customer_code" placeholder="Müşteri Kodu(Müşteri Girişi)">
            
            <label for="login">Kullanıcı Adı veya E-mail:</label>
            <input type="text" id="login" name="login" placeholder="Kullanıcı Adı veya E-mail" required>
            
            <label for="password">Şifre:</label>
            <input type="password" id="password" name="password" placeholder="Şifre" required>

            <input type="submit" id="loginButton" value="Giriş Yap">
            <p>Eğer kayıtlı değilseniz, <a href="register" style="text-decoration: none;">buraya</a> basınız</p>
        </form>
        <?php 
        if(isset($error_message)) { 
            echo '<p class="error-message">' . $error_message . '</p>';
        } 
        ?>
    </div>

</body>
</html>
