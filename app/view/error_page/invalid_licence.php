<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/database/db_connect.php');

$stmt = $db->query("SELECT * FROM site_settings");
$site_settings = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_settings['site_name']; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
        }
        .container {
            margin-top: 50px;
        }
        .jumbotron {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="jumbotron text-center">
            <h1 class="display-4"><?php echo $site_settings['site_name']; ?></h1>
            <p class="lead">Üzgünüz, lisans kaydı bulunamadı. Lütfen lisans yapılandırmasını doğru yaptığınızdan emin olun.</p>
            <hr class="my-4">
            <p>Sistemde hata olduğunu düşünüyorsunuz veya başka bir sorunla karşılaşıyorsanız, lütfen destek ekibimizle iletişime geçin.</p>
            <a class="btn btn-primary btn-lg" href="mailto:destek@example.com" role="button">Destek Alın</a>
        </div>
    </div>

    <!-- Bootstrap JS (jQuery gereklidir) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
