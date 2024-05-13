<?php
require_once('../test_server/licence_urlcontrol.php');
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KLisans</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        p {
            color: #666;
        }
    </style>
</head>
<body>

<script>
        // Lisans kontrolü için AJAX isteği
        function checkLicenseValidity() {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/licence/verification', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.isValid) {
                            // Lisans geçerli ise, sayfa içeriğini güncelle
                            document.getElementById('content').innerHTML = `
                                <h1>Lisans Geçerli</h1>
                                <p>Sayfanızın içeriğini buraya ekleyin.</p>
                            `;
                        } else {
                            // Lisans geçersiz ise, hata sayfasını göster
                            document.getElementById('content').innerHTML = `
                                <h1>Lisans Geçersiz</h1>
                                <p>Lütfen lisansınızı kontrol edin.</p>
                            `;
                        }
                    } else {
                        console.error('Lisans kontrolü hatası:', xhr.statusText);
                    }
                }
            };
            xhr.send('ip_address=<?php echo $clientIP; ?>');
        }

        // Sayfa yüklendiğinde lisans kontrolünü başlat
        checkLicenseValidity();
    </script>

    <div class="container">
        <h2>KLisans</h2>
        <p>Başarıyla çalışıyorum.</p>
    </div>
</body>
</html>
