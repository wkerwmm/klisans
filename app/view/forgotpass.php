<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/database/db_connect.php');
session_start();

$stmt = $db->query("SELECT * FROM site_settings");
$site_settings = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_settings['site_name']; ?> - Şifremi Unuttum</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Şifremi Unuttum</h2>
                    </div>
                    <div class="card-body">
                        <form id="forgotPasswordForm" method="post">
                            <div class="form-group">
                                <label for="email">E-mail Adresiniz:</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="E-mail" required>
                            </div>
                            <button type="submit" id="forgotPasswordButton" class="btn btn-primary btn-block">Şifremi Sıfırla</button>
                        </form>
                        <?php if(isset($success_message)) { ?>
                            <p class="text-success mt-3"><?php echo $success_message; ?></p>
                        <?php } ?>
                        <?php if(isset($error_message)) { ?>
                            <p class="text-danger mt-3"><?php echo $error_message; ?></p>
                        <?php } ?>
                        <div class="text-center mt-3">
                            <p><a href="login.php">Giriş Yap</a></p> <!-- Giriş Yap bağlantısı -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="../assest/ajax/forgotPass.js"></script> 
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
