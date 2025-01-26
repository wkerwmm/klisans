<?php
// İsteğin AJAX ile mi geldiğini kontrol et
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    // PHPMailer sınıf dosyasını dahil et
    require_once $_SERVER['DOCUMENT_ROOT'] . '/app/vendor/PHPMailer/src/PHPMailer.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/app/vendor/PHPMailer/src/SMTP.php';

    // Veritabanı bağlantısı gibi gerekli işlemleri yap
    require_once $_SERVER['DOCUMENT_ROOT'] . '/app/system/database/db_connect.php';

    // Gelen verileri temizle
    $email = trim($_POST['email']);

    // Veritabanında bu e-postaya sahip kullanıcıyı ara
    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Kullanıcı bulundu, şifre sıfırlama talimatlarını mail olarak gönder
        $mail = new PHPMailer\PHPMailer\PHPMailer();

        // SMTP ayarları
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // SMTP sunucu adresi
        $mail->SMTPAuth = true;
        $mail->Username = 'your@example.com'; // SMTP kullanıcı adı
        $mail->Password = 'your_password'; // SMTP şifre
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Mail gönderen bilgileri
        $mail->setFrom('your@example.com', 'Your Name');
        $mail->addAddress($email, $user['username']); // Kullanıcının e-posta adresi ve adı

        // Mail içeriği
        $mail->isHTML(true);
        $mail->Subject = 'Şifre Sıfırlama Talimatları';
        $mail->Body = 'Merhaba, şifrenizi sıfırlamak için aşağıdaki linke tıklayın: <a href="http://siteniz.com/reset_password.php">Şifremi Sıfırla</a>';

        // Mail gönder
        if ($mail->send()) {
            // Başarılı durumda başarı mesajını döndür
            echo json_encode(array("status" => "success", "message" => "Şifre sıfırlama talimatları e-posta adresinize gönderildi."));
        } else {
            // Hata durumunda hata mesajını döndür
            echo json_encode(array("status" => "error", "message" => "Mail gönderilirken bir hata oluştu. Lütfen daha sonra tekrar deneyin."));
        }
    } else {
        // Kullanıcı bulunamadıysa hata mesajını döndür
        echo json_encode(array("status" => "error", "message" => "Bu e-posta adresine kayıtlı bir kullanıcı bulunamadı."));
    }

    // Veritabanı bağlantısını kapat
    $db = null;
} else {
    // Doğrudan erişimi engelle
    http_response_code(403);
    exit();
}
?>
