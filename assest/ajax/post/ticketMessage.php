<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/database/db_connect.php');

if (!isset($_SESSION['username'])) {
    // Kullanıcı giriş yapmamışsa işlem yapmadan önce yönlendirme yapabilirsiniz
    echo json_encode(array('status' => 'error', 'message' => 'Kullanıcı girişi yapılmalıdır.'));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form verilerini al
    $message = $_POST['message']; // CKEditor'den metni almak için $_POST kullanacağız
    $ticketId = $_POST['ticket_id'];
    $username = $_SESSION['username'];
    $createdAt = date('Y-m-d H:i:s'); // Şu anki tarih ve saat

    // Veritabanına mesajı ekle
    $stmt = $db->prepare("INSERT INTO ticket_messages (ticket_id, username, message, created_at) VALUES (:ticket_id, :username, :message, :created_at)");
    $stmt->bindParam(':ticket_id', $ticketId);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':message', $message);
    $stmt->bindParam(':created_at', $createdAt);

    if ($stmt->execute()) {
        // Başarılı bir şekilde mesaj eklendiğinde
        echo json_encode(array('status' => 'success'));
    } else {
        // Mesaj eklenemediğinde
        echo json_encode(array('status' => 'error', 'message' => 'Mesaj eklenirken bir hata oluştu.'));
    }
} else {
    // POST isteği alınmadığında
    echo json_encode(array('status' => 'error', 'message' => 'Sadece POST istekleri kabul edilir.'));
}
?>
