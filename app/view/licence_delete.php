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

// Kullanıcının lisanslarını al
$stmt = $db->prepare("SELECT * FROM licenses WHERE auth = :auth");
$stmt->bindParam(":auth", $_SESSION['username']);
$stmt->execute();
$user_licenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ip_address = $_POST['ip_address'];
    
    // Lisansı bul
    $stmt = $db->prepare("SELECT * FROM licenses WHERE ip_address = :ip_address AND auth = :auth");
    $stmt->bindParam(":ip_address", $ip_address);
    $stmt->bindParam(":auth", $_SESSION['username']);
    $stmt->execute();
    $license = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($license) {
        // Lisansı sil
        $stmt = $db->prepare("DELETE FROM licenses WHERE ip_address = :ip_address");
        $stmt->bindParam(":ip_address", $ip_address);
        $stmt->execute();

        // Kullanıcının lisans hakkını artır
        $stmt = $db->prepare("UPDATE users SET license_right = license_right + 1 WHERE username = :username");
        $stmt->bindParam(":username", $_SESSION['username']);
        $stmt->execute();

        $message = "<div class='alert alert-success' role='alert'>Lisans başarıyla silindi ve lisans hakkınız artırıldı.</div>";
    } else {
        $message = "<div class='alert alert-danger' role='alert'>Belirtilen IP adresine sahip lisans bulunamadı veya bu lisansı silme izniniz yok.</div>";
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
    <!-- Lisans silme formu -->
    <form action="../licence/delete" method="post">
        <div class="form-group">
            <label for="ip_address">Silinecek Lisansın IP Adresi</label>
            <select class="form-control" id="ip_address" name="ip_address">
                <?php foreach ($user_licenses as $license): ?>
                    <option value="<?php echo $license['ip_address']; ?>"><?php echo $license['ip_address']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-danger">Lisansı Sil</button>
    </form>
    <?php echo $message; ?>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/assest/inc/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
