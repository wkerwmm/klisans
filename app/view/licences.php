<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/database/db_connect.php');
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login");
    exit();
}

$stmt = $db->query("SELECT * FROM site_settings");
$site_settings = $stmt->fetch(PDO::FETCH_ASSOC);

// Kullanıcının lisanslarını al
$stmt = $db->prepare("SELECT * FROM licenses WHERE auth = :auth");
$stmt->bindParam(":auth", $_SESSION['username']);
$stmt->execute();
$user_licenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $site_settings['site_name']; ?> - Lisanslar</title>
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

<!-- Düzenleme Modalı -->
<div class="modal fade" id="editLicenseModal" tabindex="-1" role="dialog" aria-labelledby="editLicenseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLicenseModalLabel">Lisansı Düzenle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="form-group">
                        <label for="editDescription">Lisans İsmi:</label>
                        <input type="text" class="form-control" id="editDescription" name="description" required>
                    </div>
                    <div class="form-group">
                        <label for="editIpAddress">IP Adresi:</label>
                        <input type="tel" class="form-control" id="editIpAddress" name="ip_address" required>
                    </div>
                    <div class="form-group">
                        <label for="editExpirationDate">Son Kullanma Tarihi:</label>
                        <input type="date" class="form-control" id="editExpirationDate" name="expiration_date">
                    </div>
                    <input type="hidden" id="licenseId" name="licenseId">
                    <button class="btn btn-primary" onclick="submitEditForm()">Kaydet</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="jumbotron">
    <h1 class="display-4"><?php echo "Hoş geldin, " . $_SESSION['username'];?></h1>
    <p class="lead"><?php echo $site_settings['site_name']; ?>, Yenilikçi lisans yönetim yazılımı</p>
    <hr class="my-4">
    <h2>Kullanıcı Lisansları</h2>
    <div class="container mt-4">
        <input type="text" class="form-control mb-3" placeholder="Tabloda ara..." id="searchInput" onkeyup="searchTable()">
    </div>
    <div class="container mt-4">
        <a href="licence/create" class="btn btn-success">Lisans Oluştur</a> <!-- Lisans Oluştur butonu -->
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Lisans İsmi</th>
                        <th>IP Adresi</th>
                        <th>Son Kullanma Tarihi</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($user_licenses as $license): ?>
                        <tr>
                            <td><?php echo $license['description']; ?></td>
                            <td><?php echo $license['ip_address']; ?></td>
                            <td>
    <?php 
        if ($license['expiration_date'] == '0000-00-00') {
            echo 'Sınırsız';
        } else {
            echo $license['expiration_date'];
        }
    ?>
</td>

                            <td>
                                <button class="btn btn-primary btn-sm" onclick="editLicense(<?php echo $license['id']; ?>)">Düzenle</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteLicense(<?php echo $license['id']; ?>)">Sil</button>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assest/ajax/licenseTableScript.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
function searchTable() {
    // Aranacak metni al
    var input = document.getElementById('searchInput').value.toUpperCase();
    // Tablo satırlarını al
    var rows = document.getElementsByTagName('tr');
    
    // Her satırı kontrol et
    for (var i = 0; i < rows.length; i++) {
        // Satırın hücrelerini al
        var cells = rows[i].getElementsByTagName('td');
        var found = false;
        // Her hücreyi kontrol et
        for (var j = 0; j < cells.length; j++) {
            var cellText = cells[j].textContent.toUpperCase();
            // Eğer hücrede aranan metin bulunursa
            if (cellText.indexOf(input) > -1) {
                found = true;
                break;
            }
        }
        // Eğer satırda aranan metin bulunursa, satırı göster, yoksa gizle
        if (found) {
            rows[i].style.display = '';
        } else {
            rows[i].style.display = 'none';
        }
    }
}
</script>
</body>
</html>
