<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/database/db_connect.php');

// AJAX isteğinden gelen talep kimliğini alın
$ticket_id = isset($_POST['ticket_id']) ? $_POST['ticket_id'] : null;

if ($ticket_id) {
    // Talebi kapatma sorgusu
    $stmt = $db->prepare("UPDATE tickets SET status = 'Closed' WHERE id = :ticket_id");
    $stmt->bindParam(":ticket_id", $ticket_id);
    
    // Talebi kapatma işlemi
    if ($stmt->execute()) {
        // Başarılı bir şekilde kapatıldıysa
        echo json_encode(array("status" => "success"));
        exit();
    } else {
        // Kapatma işlemi başarısız olduysa
        echo json_encode(array("status" => "error"));
        exit();
    }
} else {
    // Hatalı istek durumunda
    echo json_encode(array("status" => "error"));
    exit();
}
?>
