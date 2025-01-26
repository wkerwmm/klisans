<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/database/db_connect.php');
session_start();

// Oturum kontrolü yapın
if (!isset($_SESSION['username'])) {
    // Oturum açılmamışsa, giriş sayfasına yönlendirin
    header("Location: ../login");
    exit();
}

// POST isteğinin doğrulanması
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Formdan gelen verileri güvenli bir şekilde alın
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
        $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
        $username = $_SESSION['username'];

        // Eğer mesaj boşsa, hata mesajı gönderin
        if (empty($message)) {
            echo "empty_message";
            exit();
        }

        // Veritabanına yeni bir talep eklemek için sorguyu hazırlayın
        $stmt = $db->prepare("INSERT INTO tickets (username, title, subject, status) VALUES (:username, :title, :subject, 'Open')");
        // Parametreleri bağlayın
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":subject", $subject);
        
        // Sorguyu çalıştırın
        $stmt->execute();

        // Eklenen talebin ID'sini alın
        $ticket_id = $db->lastInsertId();

        // Veritabanına yeni bir mesaj eklemek için sorguyu hazırlayın
        $stmt = $db->prepare("INSERT INTO ticket_messages (ticket_id, username, message) VALUES (:ticket_id, :username, :message)");
        // Parametreleri bağlayın
        $stmt->bindParam(":ticket_id", $ticket_id);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":message", $message);
        
        // Sorguyu çalıştırın
        $stmt->execute();

        // Başarılı yanıtı gönderin
        echo "success";
    } catch (PDOException $e) {
        // Veritabanı işlemi sırasında bir hata oluştuğunda hata mesajını yazdırın
        echo "error: " . $e->getMessage();
    }
} else {
    // POST isteği değilse, hata yanıtı gönderin
    echo "error";
}
?>
