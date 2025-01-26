<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/database/db_connect.php');
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login");
    exit();
}

$stmt = $db->query("SELECT * FROM site_settings");
$site_settings = $stmt->fetch(PDO::FETCH_ASSOC);

// Türkçe lokalini ayarla
setlocale(LC_TIME, 'tr_TR.UTF-8');

// Talep bilgilerini al
if(isset($_GET['id'])) {
    // Oturumda saklanan kullanıcı adını al
    $username = $_SESSION['username'];

    $stmt = $db->prepare("SELECT * FROM tickets WHERE id = :id AND username = :username");
    $stmt->bindParam(":id", $_GET['id']);
    $stmt->bindParam(":username", $username);
    $stmt->execute();
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

    // Talep ID'si mevcut değilse hata sayfasına yönlendir
    if(!$ticket) {
        header("Location: hata_sayfasi.php");
        exit();
    }

    // Mesajları al
    $stmt = $db->prepare("SELECT tm.*, u.role FROM ticket_messages tm INNER JOIN users u ON tm.username = u.username WHERE tm.ticket_id = :ticket_id ORDER BY tm.created_at ASC");
    $stmt->bindParam(":ticket_id", $_GET['id']);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Talep ID'si mevcut değil, hata sayfasına yönlendir
    header("Location: hata_sayfasi.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Talep Görüntüleme</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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

    .message-container {
      margin-top: 20px;
    }

    .message-box {
      margin-bottom: 20px;
      padding: 10px;
      border-radius: 10px;
    }

    .sender-info {
      font-weight: bold;
    }

    .sender-role {
      font-style: italic;
      margin-top: 5px; /* Altında biraz boşluk bırakmak için */
    }

    .message-body {
      margin-top: 10px;
    }

    .user-profile {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
    }

    .user-avatar {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      margin-right: 10px;
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

    .btn-primary {
      background-color: #007bff; /* Bootstrap ana renk */
      border-color: #007bff; /* Bootstrap ana renk */
    }

    .btn-primary:hover {
      background-color: #0056b3; /* Bootstrap ana renk hafif koyultma */
      border-color: #00;
    }
</style>
</head>
<body>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/assest/inc/adds.php'; ?>

<div class="jumbotron">
  <h1 class="display-4">
    <?php echo $ticket['title']; ?>
    <?php if ($ticket['status'] == 'Open'): ?>
      <span class="badge badge-success">Açık</span>
    <?php elseif ($ticket['status'] == 'In Progress'): ?>
      <span class="badge badge-warning">İşlemde</span>
    <?php else: ?>
      <span class="badge badge-danger">Kapalı</span>
    <?php endif; ?>
  </h1>
  <p class="lead"><?php echo $ticket['subject']; ?></p>
  <hr class="my-4">
  <h2>Mesajlar</h2>
  <?php foreach ($messages as $message): ?>
    <div class="message-container">
      <div class="user-profile">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSNkvGHtV-DPj1MtLDlJvej_7iTWb21dYJ1zlvFQ23gl0IA0OYliq4TEjY&s=10" alt="Profile Picture" class="user-avatar">
        <div>
          <div class="sender-info"><?php echo $message['username']; ?></div>
          <div class="sender-role">
            <?php
              switch ($message['role']) {
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
          </div>
        </div>
      </div>
      <div class="message-box <?php echo ($message['role'] == 'admin') ? 'text-right' : ''; ?>">
        <div class="message-body">
            <?php 
            // Mesajın HTML etiketlerini temizleyin ve özel karakterleri çözün
            echo htmlspecialchars(html_entity_decode(strip_tags($message['message']))); 
            ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>

  <!-- Mesaj gönderme alanı -->
  <?php if ($ticket['status'] !== 'closed'): ?>
    <div id="message-form-container">
      <form id="message-form">
        <textarea name="message" id="editor" required></textarea>
        <input type="hidden" name="ticket_id" id="ticket_id" value="<?php echo $_GET['id']; ?>">
        <button type="submit" class="btn btn-primary mt-3">Mesaj Gönder</button>
      </form>
    </div>
  <?php endif; ?>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/assest/inc/footer.php'; ?>

<script>
$(document).ready(function() {
    $('#message-form').submit(function(event) {
        event.preventDefault();

        // CKEditor'den metin al
        var message = CKEDITOR.instances.editor.getData();

        // Eğer metin boşsa hata mesajı göster
        if (!message.trim()) {
            Swal.fire({
                icon: 'error',
                title: 'Hata!',
                text: 'Mesaj alanı boş bırakılamaz!'
            });
            return;
        }

        // Form verilerini oluştur
        var formData = new FormData();
        formData.append('message', message);
        formData.append('ticket_id', $('#ticket_id').val());

        $.ajax({
            type: 'POST',
            url: '../assest/ajax/post/ticketMessage.php',
            data: formData,
            processData: false,  // FormData'nın otomatik işlenmesini devre dışı bırak
            contentType: false,  // FormData'nın içeriğinin türünü otomatik ayarlamayı devre dışı bırak
            success: function(response) {
                // Başarılı gönderim durumunda yapılacak işlemler
                Swal.fire({
                    icon: 'success',
                    title: 'Başarılı!',
                    text: 'Mesaj başarıyla gönderildi!'
                }).then(() => {
                    // Yeni mesajı ekrana eklemek için sayfayı yenile
                    location.reload();
                });
            },
            error: function(xhr, status, error) {
                // Hata durumunda yapılacak işlemler
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: 'Mesaj gönderilirken bir hata oluştu: ' + error
                });
            }
        });
    });

    // CKEditor ayarla
    CKEDITOR.replace('editor');
});
</script>
</body>
</html>
