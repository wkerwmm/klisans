<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/database/db_connect.php');
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login");
    exit();
}

$stmt = $db->query("SELECT * FROM site_settings");
$site_settings = $stmt->fetch(PDO::FETCH_ASSOC);

// Kullanıcının lisans sayısını al
$stmt = $db->prepare("SELECT COUNT(*) as license_count FROM licenses WHERE auth = :username");
$stmt->bindParam(":username", $_SESSION['username']);
$stmt->execute();
$license_count = $stmt->fetch(PDO::FETCH_ASSOC)['license_count'];

// Kullanıcının lisans oluşturma hakkını al
$stmt = $db->prepare("SELECT license_right FROM users WHERE username = :username");
$stmt->bindParam(":username", $_SESSION['username']);
$stmt->execute();
$license_right = $stmt->fetch(PDO::FETCH_ASSOC)['license_right'];

?>

<!DOCTYPE html>
<html lang="en">
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

    .profile-card {
      background-color: #f8f9fa;
      border-radius: 10px;
      padding: 20px;
      margin-top: 20px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease-in-out;
    }

    .profile-card:hover {
      transform: translateY(-5px);
    }
    
    .profile-img {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      margin: 0 auto 20px;
      display: block;
      border: 4px solid #fff;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: box-shadow 0.3s ease-in-out;
    }

    .profile-img:hover {
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }
    
    .profile-name {
      text-align: center;
      margin-bottom: 10px;
      font-size: 24px;
      color: #333;
    }
    
    .profile-position {
      text-align: center;
      margin-bottom: 20px;
      font-size: 18px;
      color: #666;
    }

    .profile-details {
      padding: 20px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: box-shadow 0.3s ease-in-out;
    }

    .profile-details:hover {
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }
	
	.navbar {
      position: sticky;
      top: 0;
      z-index: 1000;
    }
</style>
</head>
<body>

<header style="position: sticky; top: 0; z-index: 1000; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand font-weight-bold"><?php echo $site_settings['site_name']; ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" onClick="location.reload()">Anasayfa <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Lisans İşlemleri
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="licence/create">Lisans Oluştur</a>
                        <a class="dropdown-item" href="#">Lisans Sil</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Lisanslar</a>
                    </div>
                </li>
				 <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $_SESSION['username'];?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Bilgileri Düzenle</a>
                        <a class="dropdown-item" href="#">Destek Talebi Oluştur</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/logout">Çıkış Yap</a>
                    </div>
            </ul>
        </div>
    </nav>
</header>
  
  <div class="jumbotron">
    <h1 class="display-4"><?php echo "Hoş geldin, " . $_SESSION['username'];?></h1>
    <p class="lead"><?php echo $site_settings['site_name']; ?>, Yenilikçi lisans yönetim yazılımı</p>
    <hr class="my-4">
    <div class="row">
      <div class="col-sm-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Oluşturulan Lisans</h5>
            <p class="card-text"><?php echo $license_count; ?></p>
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Lisans Oluşturma Hakkınız</h5>
            <p class="card-text"><?php echo ($license_right == 0 || empty($license_right)) ? 'Bitmiş' : $license_right; ?></p>
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Kullanılan Lisanslar</h5>
            <p class="card-text">Üçüncü Kutu İçerik</p>
          </div>
        </div>
      </div>
    </div>
    <a class="btn btn-primary btn-lg mt-3" href="hakkimizda" role="button">Daha Fazla Bilgi</a>
  </div>
  
  <footer class="footer mt-auto py-3 bg-light text-dark">
    <div class="row">
      <div class="col-md-12">
        <p class="text-center">© 2024 <?php echo $site_settings['site_name']; ?> tüm hakları saklıdır.</p>
      </div>
    </div>
  </footer>
  
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
