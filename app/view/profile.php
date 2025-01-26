<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/database/db_connect.php');
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login");
    exit();
}

$stmt = $db->query("SELECT * FROM site_settings");
$site_settings = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Kullanıcının mevcut şifresini kontrol etmek için veritabanından çekin
    $stmt = $db->prepare("SELECT password FROM users WHERE username = :username");
    $stmt->bindParam(":username", $_SESSION['username']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Mevcut şifre doğru mu kontrol et
    if (hash('sha256', $current_password) == $user['password']) {
        // Yeni şifrenin doğruluğunu kontrol et
        if ($new_password == $confirm_password) {
            // Yeni şifreyi güncelle
            $hashed_password = hash('sha256', $new_password);
            $update_stmt = $db->prepare("UPDATE users SET password = :password WHERE username = :username");
            $update_stmt->bindParam(":password", $hashed_password);
            $update_stmt->bindParam(":username", $_SESSION['username']);
            $update_stmt->execute();
            
            // Başarılı bir şekilde güncellendiğini bildirin
            $password_message = "Şifre başarıyla güncellendi.";
        } else {
            $password_message = "Yeni şifreler uyuşmuyor.";
        }
    } else {
        $password_message = "Mevcut şifre yanlış.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_email'])) {
    $new_email = $_POST['new_email'];

    // E-postanın benzersiz olduğundan emin olun
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM users WHERE email = :email");
    $stmt->bindParam(":email", $new_email);
    $stmt->execute();
    $email_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    if ($email_count == 0) {
        // E-postayı güncelle
        $update_stmt = $db->prepare("UPDATE users SET email = :email WHERE username = :username");
        $update_stmt->bindParam(":email", $new_email);
        $update_stmt->bindParam(":username", $_SESSION['username']);
        $update_stmt->execute();
        
        // Başarılı bir şekilde güncellendiğini bildirin
        $email_message = "E-posta başarıyla güncellendi.";
    } else {
        $email_message = "Bu e-posta zaten kullanılıyor.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $site_settings['site_name']; ?> - Profil</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
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
    .user-badge {
      padding: 5px 10px;
      border-radius: 10px;
      font-weight: bold;
    }

    .user-badge-admin {
      background-color: #dc3545; /* Kırmızı */
      color: white;
    }

    .user-badge-support {
      background-color: #ffc107; /* Sarı */
      color: black;
    }

    .user-badge-user {
      background-color: #28a745; /* Yeşil */
      color: white;
    }
</style>
</head>
<body>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/assest/inc/adds.php'; ?>

  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="profile-card">
          <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSNkvGHtV-DPj1MtLDlJvej_7iTWb21dYJ1zlvFQ23gl0IA0OYliq4TEjY&s=10" alt="Profil Resmi" class="profile-img">
          <h2 class="profile-name"><?php echo $_SESSION['username']; ?></h2>
          <p class="profile-position">
            <?php
              switch ($user['role']) {
                case 'user':
                  echo '<span class="user-badge user-badge-user"><i class="fas fa-user"></i> Kullanıcı</span>';
                  break;
                case 'support':
                  echo '<span class="user-badge user-badge-support"><i class="fas fa-hands-helping"></i> Destek Üyesi</span>';
                  break;
                case 'admin':
                  echo '<span class="user-badge user-badge-admin"><i class="fas fa-crown"></i> Yönetici</span>';
                  break;
                default:
                  echo 'Rol Belirtilmemiş';
              }
            ?>
          </p>
          <div class="profile-details">
            <div class="row">
              <div class="col-md-6">
                <h4>Şifre Değiştirme</h4>
                <form method="POST">
                  <div class="form-group">
                    <label for="current_password">Mevcut Şifre</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                  </div>
                  <div class="form-group">
                    <label for="new_password">Yeni Şifre</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                  </div>
                  <div class="form-group">
                    <label for="confirm_password">Yeni Şifreyi Onayla</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                  </div>
                  <button type="submit" class="btn btn-primary" name="change_password">Şifre Değiştir</button>
                </form>
                <?php if(isset($password_message)) { ?>
                  <div class="mt-3"><?php echo $password_message; ?></div>
                <?php } ?>
              </div>
              <div class="col-md-6">
                <h4>E-posta Değiştirme</h4>
                <form method="POST">
                  <div class="form-group">
                    <label for="new_email">Yeni E-posta Adresi</label>
                    <input type="email" class="form-control" id="new_email" name="new_email" required>
                  </div>
                  <button type="submit" class="btn btn-primary" name="change_email">E-posta Değiştir</button>
                </form>
                <?php if(isset($email_message)) { ?>
                  <div class="mt-3"><?php echo $email_message; ?></div>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/assest/inc/footer.php'; ?>
  
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
