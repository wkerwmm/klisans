<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/database/db_connect.php');
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login");
    exit();
}

// Türkçe lokalini ayarla
setlocale(LC_TIME, 'tr_TR.UTF-8');

error_reporting(E_ALL); 
ini_set('display_errors', 1);

$stmt = $db->query("SELECT * FROM site_settings");
$site_settings = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_settings['site_name']; ?> - Destek Talebi Oluştur</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
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

<div class="container">
    <h2 class="my-4">Destek Talebi Oluştur</h2>
    <form id="message-form">
        <div class="form-group">
            <label for="title">Başlık:</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="subject">Konu:</label>
            <input type="text" class="form-control" id="subject" name="subject" required>
        </div>
        <div class="form-group">
            <label for="message">Mesaj:</label>
            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Gönder</button>
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/assest/inc/footer.php'; ?>

<script>
$(document).ready(function() {
    // Form gönderildiğinde AJAX kullanarak işlemleri yap
    $('#message-form').submit(function(event) {
        event.preventDefault();

        // Form verilerini al
        var formData = {
            title: $('#title').val(),
            subject: $('#subject').val(),
            message: $('#message').val()
        };

        // Eğer başlık veya konu boşsa hata mesajı göster
        if (!formData.title.trim() || !formData.subject.trim() || !formData.message.trim()) {
            Swal.fire({
                icon: 'error',
                title: 'Hata!',
                text: 'Başlık, konu ve mesaj alanları boş bırakılamaz!'
            });
            return;
        }

        // AJAX isteği gönder
        $.ajax({
            type: 'POST',
            url: '../assest/ajax/post/createTicket.php', // İsteği işleyecek PHP dosyasının adı ve adresi
            data: formData,
            success: function(response) {
                // Başarılı gönderim durumunda yapılacak işlemler
                Swal.fire({
                    icon: 'success',
                    title: 'Başarılı!',
                    text: 'Talep başarıyla oluşturuldu!'
                }).then((result) => {
                    // İsteği işledikten sonra belirli bir URL'ye yönlendirme yap
                    window.location.href = '/tickets'; // Yönlendirme yapılacak URL
                });
            },
            error: function(xhr, status, error) {
                // Hata durumunda yapılacak işlemler
                console.log(xhr); // Hatanın detaylarını konsola yaz
                alert('Talep oluşturulurken bir hata oluştu: ' + error); // Tarayıcıda bir uyarı göster
            }
        });
    });

    // CKEditor ayarla
    CKEDITOR.replace('message');
});
</script>

</body>
</html>
