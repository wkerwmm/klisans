<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/database/db_connect.php');
session_start();

if(isset($_SESSION["username"])) {
    header("Location: ../dashboard");
    exit();
}

$stmt = $db->query("SELECT * FROM site_settings");
$site_settings = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["username"]) && isset($_POST["password"])) {
        $login = $_POST["username"]; // Kullanıcı adını aldık
        $password = $_POST["password"];
        $user_ip = $_SERVER['REMOTE_ADDR']; // Kullanıcının IP adresini aldık

        // Check login (username or email) and password
        $hashed_password = hash('sha256', $password); // SHA-256 ile şifreyi hashle
        $stmt = $db->prepare("SELECT * FROM users WHERE (username = :login OR email = :login) AND password = :password");
        $stmt->bindParam(":login", $login);
        $stmt->bindParam(":password", $hashed_password); 
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Giriş başarılı, oturum oluştur
            $_SESSION["username"] = $user["username"];
            header("Location: /dashboard");
            exit();
        } else {
            $error_message = "Kullanıcı adı, email veya şifre yanlış.";
        }

        // Başarısız giriş denemesini kaydet
        $log_message = "Hesabınıza izinsiz giriş yapılıyor, giriş yapmaya çalıştığı bilgiler: $login from İP: $user_ip";
        $stmt = $db->prepare("INSERT INTO login_logs (username, ip_address, log_message) VALUES (:login, :ip_address, :log_message)");
        $stmt->bindParam(":login", $login);
        $stmt->bindParam(":ip_address", $user_ip);
        $stmt->bindParam(":log_message", $log_message);
        $stmt->execute();
    } else {
        $error_message = "Kullanıcı adı veya şifre eksik.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_settings['site_name']; ?> - Giriş Yap</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center"><?php echo $site_settings['site_name']; ?></h2>
                    </div>
                    <div class="card-body">
                        <form id="loginForm" method="post">
                            <div class="form-group">
                                <label for="username">Kullanıcı Adı veya E-mail:</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Kullanıcı Adı veya E-mail" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Şifre:</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Şifre" required>
                            </div>
                            <div class="form-group form-check d-flex justify-content-between">
                                <div>
                                    <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
                                    <label class="form-check-label" for="rememberMe">Beni Hatırla</label>
                                </div>
                                <div>
                                    <a href="forgot_password.php">Şifremi Unuttum</a> <!-- Şifremi Unuttum bağlantısı -->
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block" id="loginButton">Giriş Yap</button>
                        </form>
                        <?php if(isset($error_message)) { ?>
                            <p class="text-danger mt-3"><?php echo $error_message; ?></p>
                        <?php } ?>
                        <div class="text-center mt-3">
                            <p>Eğer kayıtlı değilseniz, <a href="register" class="btn btn-outline-primary">Kayıt Ol</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
