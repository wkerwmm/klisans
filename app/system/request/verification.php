<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/database/db_connect.php');

$clientIP = $_SERVER['SERVER_ADDR'];

$sql = "SELECT * FROM licenses WHERE ip_address = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$clientIP]);
$result = $stmt->fetchAll();

if ($result) {
    $row = $result[0];
    $expirationDate = $row["expiration_date"];
    
    if ($expirationDate == "0000-00-00" || strtotime($expirationDate) >= strtotime(date("Y-m-d"))) {
        echo "valid";
    } else {
        echo "invalid";
    }
} else {
    echo "invalid";
}

$db = null;
?>
