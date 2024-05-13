-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 13 May 2024, 16:50:03
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `vxtpfztp_licence`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `licenses`
--

CREATE TABLE `licenses` (
  `id` int(11) NOT NULL,
  `auth` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `expiration_date` date NOT NULL,
  `description` varchar(300) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Tablo döküm verisi `licenses`
--

INSERT INTO `licenses` (`id`, `auth`, `ip_address`, `expiration_date`, `description`) VALUES
(10, 'keremaris', '893949', '2024-05-13', 'KLisans'),
(9, 'keremari', '78.90.293.2', '2024-05-13', 'Deneme');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `login_logs`
--

CREATE TABLE `login_logs` (
  `log_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `customer_code` varchar(20) NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `log_message` text NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Tablo döküm verisi `login_logs`
--

INSERT INTO `login_logs` (`log_id`, `username`, `customer_code`, `ip_address`, `log_message`, `login_time`) VALUES
(21, 'keremari', '', '176.88.122.231', 'HesabÄ±nÄ±za izinsiz giriÅŸ yapÄ±lÄ±yor, giriÅŸ yapmaya Ã§alÄ±ÅŸtÄ±ÄŸÄ± bilgiler: keremari, MÃ¼ÅŸteri Kodu:  from Ä°P: 176.88.122.231', '2024-03-05 18:07:30'),
(20, 'keremari', '', '176.88.122.231', 'HesabÄ±nÄ±za izinsiz giriÅŸ yapÄ±lÄ±yor, giriÅŸ yapmaya Ã§alÄ±ÅŸtÄ±ÄŸÄ± bilgiler: keremari, MÃ¼ÅŸteri Kodu:  from Ä°P: 176.88.122.231', '2024-03-05 18:07:10'),
(15, 'keremari', 'keremari', '176.232.180.158', 'HesabÄ±nÄ±za izinsiz giriÅŸ yapÄ±lÄ±yor, giriÅŸ yapmaya Ã§alÄ±ÅŸtÄ±ÄŸÄ± bilgiler: keremari, MÃ¼ÅŸteri Kodu: keremari from Ä°P: 176.232.180.158', '2024-02-28 07:42:19'),
(16, 'keremari', '0', '176.232.180.158', 'HesabÄ±nÄ±za izinsiz giriÅŸ yapÄ±lÄ±yor, giriÅŸ yapmaya Ã§alÄ±ÅŸtÄ±ÄŸÄ± bilgiler: keremari, MÃ¼ÅŸteri Kodu: 0 from Ä°P: 176.232.180.158', '2024-02-28 09:34:48'),
(17, 'keremari', '', '176.232.180.158', 'HesabÄ±nÄ±za izinsiz giriÅŸ yapÄ±lÄ±yor, giriÅŸ yapmaya Ã§alÄ±ÅŸtÄ±ÄŸÄ± bilgiler: keremari, MÃ¼ÅŸteri Kodu:  from Ä°P: 176.232.180.158', '2024-03-03 19:23:53'),
(18, 'keremari', '', '176.88.122.231', 'HesabÄ±nÄ±za izinsiz giriÅŸ yapÄ±lÄ±yor, giriÅŸ yapmaya Ã§alÄ±ÅŸtÄ±ÄŸÄ± bilgiler: keremari, MÃ¼ÅŸteri Kodu:  from Ä°P: 176.88.122.231', '2024-03-05 18:02:39'),
(19, 'keremari', '', '176.88.122.231', 'HesabÄ±nÄ±za izinsiz giriÅŸ yapÄ±lÄ±yor, giriÅŸ yapmaya Ã§alÄ±ÅŸtÄ±ÄŸÄ± bilgiler: keremari, MÃ¼ÅŸteri Kodu:  from Ä°P: 176.88.122.231', '2024-03-05 18:05:02'),
(22, 'keremari', '', '176.88.122.231', 'HesabÄ±nÄ±za izinsiz giriÅŸ yapÄ±lÄ±yor, giriÅŸ yapmaya Ã§alÄ±ÅŸtÄ±ÄŸÄ± bilgiler: keremari, MÃ¼ÅŸteri Kodu:  from Ä°P: 176.88.122.231', '2024-03-05 18:08:56'),
(23, 'keremari', '', '176.88.122.231', 'HesabÄ±nÄ±za izinsiz giriÅŸ yapÄ±lÄ±yor, giriÅŸ yapmaya Ã§alÄ±ÅŸtÄ±ÄŸÄ± bilgiler: keremari, MÃ¼ÅŸteri Kodu:  from Ä°P: 176.88.122.231', '2024-03-05 18:09:10'),
(24, 'keremari', '', '176.88.122.231', 'HesabÄ±nÄ±za izinsiz giriÅŸ yapÄ±lÄ±yor, giriÅŸ yapmaya Ã§alÄ±ÅŸtÄ±ÄŸÄ± bilgiler: keremari, MÃ¼ÅŸteri Kodu:  from Ä°P: 176.88.122.231', '2024-03-05 18:13:44'),
(25, 'keremari', '', '176.88.122.231', 'HesabÄ±nÄ±za izinsiz giriÅŸ yapÄ±lÄ±yor, giriÅŸ yapmaya Ã§alÄ±ÅŸtÄ±ÄŸÄ± bilgiler: keremari, MÃ¼ÅŸteri Kodu:  from Ä°P: 176.88.122.231', '2024-03-05 18:13:53'),
(26, 'keremari', '', '176.88.122.231', 'HesabÄ±nÄ±za izinsiz giriÅŸ yapÄ±lÄ±yor, giriÅŸ yapmaya Ã§alÄ±ÅŸtÄ±ÄŸÄ± bilgiler: keremari, MÃ¼ÅŸteri Kodu:  from Ä°P: 176.88.122.231', '2024-03-05 18:14:17'),
(27, 'keremari', '', '176.88.122.231', 'HesabÄ±nÄ±za izinsiz giriÅŸ yapÄ±lÄ±yor, giriÅŸ yapmaya Ã§alÄ±ÅŸtÄ±ÄŸÄ± bilgiler: keremari, MÃ¼ÅŸteri Kodu:  from Ä°P: 176.88.122.231', '2024-03-05 18:15:00'),
(28, 'keremari', '', '176.88.122.231', 'HesabÄ±nÄ±za izinsiz giriÅŸ yapÄ±lÄ±yor, giriÅŸ yapmaya Ã§alÄ±ÅŸtÄ±ÄŸÄ± bilgiler: keremari, MÃ¼ÅŸteri Kodu:  from Ä°P: 176.88.122.231', '2024-03-05 18:15:06'),
(29, 'keremari', '', '176.88.122.231', 'HesabÄ±nÄ±za izinsiz giriÅŸ yapÄ±lÄ±yor, giriÅŸ yapmaya Ã§alÄ±ÅŸtÄ±ÄŸÄ± bilgiler: keremari, MÃ¼ÅŸteri Kodu:  from Ä°P: 176.88.122.231', '2024-03-05 18:15:12'),
(30, 'keremari', '', '176.88.122.231', 'HesabÄ±nÄ±za izinsiz giriÅŸ yapÄ±lÄ±yor, giriÅŸ yapmaya Ã§alÄ±ÅŸtÄ±ÄŸÄ± bilgiler: keremari, MÃ¼ÅŸteri Kodu:  from Ä°P: 176.88.122.231', '2024-03-09 07:06:14'),
(31, 'keremari', '0', '176.88.122.231', 'HesabÄ±nÄ±za izinsiz giriÅŸ yapÄ±lÄ±yor, giriÅŸ yapmaya Ã§alÄ±ÅŸtÄ±ÄŸÄ± bilgiler: keremari, MÃ¼ÅŸteri Kodu: 0 from Ä°P: 176.88.122.231', '2024-03-09 07:11:04');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `punishments`
--

CREATE TABLE `punishments` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `punishment_type` enum('warning','suspension','ban') NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `reason` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `site_settings`
--

CREATE TABLE `site_settings` (
  `site_name` varchar(255) NOT NULL,
  `site_description` text DEFAULT NULL,
  `seo_keywords` text DEFAULT NULL,
  `site_logo` varchar(255) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `footer_text` text DEFAULT NULL,
  `facebook_url` varchar(255) DEFAULT NULL,
  `twitter_url` varchar(255) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `instagram_url` varchar(255) DEFAULT NULL,
  `google_analytics_code` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `site_settings`
--

INSERT INTO `site_settings` (`site_name`, `site_description`, `seo_keywords`, `site_logo`, `favicon`, `footer_text`, `facebook_url`, `twitter_url`, `email_address`, `instagram_url`, `google_analytics_code`, `created_at`, `updated_at`) VALUES
('KLisans', 'Lisans YÃ¶netimi Sistemi, modern iÅŸletmelerin dijital varlÄ±klarÄ±nÄ± ve lisanslama gereksinimlerini yÃ¶netmelerine olanak tanÄ±yan kapsamlÄ± bir Ã§Ã¶zÃ¼mdÃ¼r. Ä°ÅŸletmelerin yazÄ±lÄ±m, uygulama, belge ve diÄŸer dijital kaynaklarÄ± etkin bir ÅŸekilde izlemelerini, yÃ¶netmelerini ve lisanslamalarÄ±nÄ± saÄŸlayan bu sistem, verimliliÄŸi artÄ±rarak kaynaklarÄ±n daha etkin bir ÅŸekilde kullanÄ±lmasÄ±nÄ± saÄŸlar.', 'Anahtar Kelime 1, Anahtar Kelime 2', 'logo.png', 'favicon.ico', 'Footer Metinleri', 'https://www.facebook.com/', 'https://twitter.com/', NULL, 'https://www.instagram.com/', 'UA-12345678-1', '2024-02-28 09:28:32', '2024-02-28 19:39:17');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `customer_code` varchar(30) NOT NULL,
  `authorized_code` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ip_address` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `role` enum('user','support','admin') NOT NULL DEFAULT 'user',
  `account_status` enum('pending','approved','') NOT NULL,
  `license_right` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `customer_code`, `authorized_code`, `email`, `ip_address`, `password`, `profile_photo`, `role`, `account_status`, `license_right`) VALUES
(1, 'keremari', '', 'aridogan', 'aridogankerem40@gmail.com', '176.88.122.231', '5a79bcb7aed89a4219015f33b2ba51ebf0cdc3bcf844e43c064f74aa40f9f571', NULL, 'admin', 'approved', 99);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `licenses`
--
ALTER TABLE `licenses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ip_address_unique` (`ip_address`);

--
-- Tablo için indeksler `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Tablo için indeksler `punishments`
--
ALTER TABLE `punishments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`username`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `licenses`
--
ALTER TABLE `licenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Tablo için AUTO_INCREMENT değeri `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Tablo için AUTO_INCREMENT değeri `punishments`
--
ALTER TABLE `punishments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
