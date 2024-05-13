<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/system/database/db_connect.php');

// Kullanıcının oturum açıp açmadığını kontrol et
if(!isset($_SESSION["username"])) {
    header("Location: ../login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lisans Sil</title>
  <title>Lisans Oluşturma Formu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Lisans Sil</h2>
    <form action="licence/repost" method="post">
        <label for="ip_address">Silinecek Lisans IP Adresi:</label><br>
        <input type="text" id="ip_address" name="ip_address" required><br><br>
        <input type="submit" value="Lisansı Sil">
    </form>
</body>
</html>
