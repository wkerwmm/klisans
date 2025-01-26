<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/database/db_connect.php');
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login");
    exit();
}

$stmt = $db->query("SELECT * FROM site_settings");
$site_settings = $stmt->fetch(PDO::FETCH_ASSOC);

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $license_name = $_POST['license_name'];
    $ip_address = $_POST['ip_address'];
    $expiration_date = $_POST['expiration_date'];
    
    // Lisans hakkını kontrol et
    $stmt = $db->prepare("SELECT license_right FROM users WHERE username = :username");
    $stmt->bindParam(":username", $_SESSION['username']);
    $stmt->execute();
    $license_right = $stmt->fetch(PDO::FETCH_ASSOC)['license_right'];
    
    if ($license_right <= 0) {
        $message = "<div class='alert alert-danger' role='alert'>Lisans hakkınız bulunmamaktadır.</div>";
    } else {
        // Lisans isminin benzersiz olup olmadığını kontrol et
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM licenses WHERE description = :description");
        $stmt->bindParam(":description", $license_name);
        $stmt->execute();
        $license_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        if ($license_count > 0) {
            $message = "<div class='alert alert-warning' role='alert'>Bu lisans ismi zaten kullanımda. Lütfen farklı bir isim girin.</div>";
        } else {
            // Lisans hakkını güncelle
            $stmt = $db->prepare("UPDATE users SET license_right = license_right - 1 WHERE username = :username");
            $stmt->bindParam(":username", $_SESSION['username']);
            $stmt->execute();

            // Lisans açıklaması ve diğer bilgileri licenses tablosuna ekle
            $stmt = $db->prepare("INSERT INTO licenses (auth, ip_address, expiration_date, description) VALUES (:auth, :ip_address, :expiration_date, :description)");
            $stmt->bindParam(":auth", $_SESSION['username']);
            $stmt->bindParam(":ip_address", $ip_address);
            $stmt->bindParam(":expiration_date", $expiration_date);
            $stmt->bindParam(":description", $license_name);
            $stmt->execute();

            $message = "<div class='alert alert-success' role='alert'>Lisans başarıyla oluşturuldu.</div>";
            header("Location: ../licenses");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $site_settings['site_name']; ?> - Panel</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>        
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
    }

    body {
        margin-bottom: 0;
    }

    .navbar {
      position: sticky;
      top: 0;
      z-index: 1000;
    }
  </style>
</head>
<body>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/assest/inc/adds.php'; ?>
  
  <div class="jumbotron">
    <h1 class="display-4"><?php echo "Hoş geldin, " . $_SESSION['username'];?></h1>
    <p class="lead"><?php echo $site_settings['site_name']; ?>, Yenilikçi lisans yönetim yazılımı</p>
    <hr class="my-4">
    <p>Eğer sürenin sınırsız olmasını istiyorsanız, SKT yerini boş bırakınız.</p>
    <form action="../licence/create" method="post">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="license_name">Lisans İsmi</label>
                <input type="text" class="form-control" id="license_name" name="license_name" placeholder="Lisans ismini girin" required>
            </div>
            <div class="form-group col-md-6">
                <label for="ip_address">IP Adresi</label>
                <input type="text" class="form-control" id="ip_address" name="ip_address" placeholder="IP Adresi" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="expiration_date">Son Kullanma Tarihi</label>
                <input type="date" class="form-control" id="expiration_date" name="expiration_date">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Lisans Oluştur</button>
    </form>

    <?php echo $message; ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/assest/inc/footer.php'; ?>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
