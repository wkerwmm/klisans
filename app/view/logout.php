<?php
session_start();

// Oturumu sonlandır
session_unset();
session_destroy();

// Kullanıcıyı giriş sayfasına yönlendir
header("Location: login");
exit();
?>
