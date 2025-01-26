<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/database/db_connect.php');
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login");
    exit();
}

// Türkçe lokalini ayarla
setlocale(LC_TIME, 'tr_TR.UTF-8');

$stmt = $db->query("SELECT * FROM site_settings");
$site_settings = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT * FROM tickets WHERE username = :username");
$stmt->bindParam(":username", $_SESSION['username']);
$stmt->execute();
$user_tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $site_settings['site_name']; ?> - Destek Talepleri</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
    <h2>Kullanıcı Talepleri</h2>
    <div class="container mt-4">
        <input type="text" class="form-control mb-3" placeholder="Tabloda ara..." id="searchInput" onkeyup="searchTable()">
    </div>
    <div class="container mt-4">
        <a href="../ticket/create" class="btn btn-success">Talep Oluştur</a> <!-- Lisans Oluştur butonu -->
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Başlık</th>
                        <th>Konu</th>
                        <th>Durum</th>
                        <th>Oluşturulma Tarihi</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($user_tickets as $ticket): ?>
                        <tr>
                            <td><?php echo $ticket['title']; ?></td>
                            <td><?php echo $ticket['subject']; ?></td>
                            <td>
                                <?php if ($ticket['status'] == 'Open'): ?>
                                    <span class="badge badge-success">Açık</span>
                                <?php elseif ($ticket['status'] == 'In Progress'): ?>
                                    <span class="badge badge-warning">İşlemde</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Kapalı</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo strftime('%d %B %A', strtotime($ticket['created_at'])); ?></td>
                            <td>
                                <a href="../tview?id=<?php echo $ticket['id']; ?>" class="btn btn-primary btn-sm">Görüntüle</a>
                                <?php if ($ticket['status'] == 'Open'): ?>
                                    <button class="btn btn-danger btn-sm" onclick="deleteTicket(<?php echo $ticket['id']; ?>)">Kapat</button>
                                <?php else: ?>
                                    
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/assest/inc/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="../assest/ajax/closedTickets.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
</script>
</body>
</html>
