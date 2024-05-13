<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/database/db_connect.php');


$stmt = $db->query("SELECT * FROM site_settings");
$site_settings = $stmt->fetch(PDO::FETCH_ASSOC);

// Kullanıcı adını ve IP adresini al
session_start();
$username = $_SESSION['username'];

// Kullanıcı durumunu kontrol et
$query = "SELECT account_status FROM users WHERE username = ?";
$stmt = $db->prepare($query);
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Eğer kullanıcı "pending" durumunda ise, sayfayı göster
if ($user['account_status'] == 'pending') {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $site_settings['site_name']; ?></title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }

            .container {
                max-width: 600px;
                margin: 100px auto;
                text-align: center;
                background-color: #fff;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            h2 {
                color: #333;
            }

            p {
                color: #666;
            }

            .confirm-btn {
                background-color: #007bff;
                color: #fff;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .confirm-btn:hover {
                background-color: #0056b3;
            }
        </style>
        <div class="container">
            <h2>Onay Aşamasında</h2>
            <p>Hesabınızın durumu değerlendirilme aşamasında, en kısa sürede onaylanır</p>
            <button class="confirm-btn" onclick="window.location.href='login'">Giriş Yap</button>
        </div>
    </body>
    </html>
    <?php
} else {
    // Kullanıcı hesap durumu değişmişse veya başka bir sayfaya yönlendirilmişse, giriş sayfasına yönlendir
    header("Location: login");
    exit;
}
?>
