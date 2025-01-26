$validLicense = false;

$verificationURL = "https://esmenya.com.tr/licence/verification";
$clientIP = $_SERVER['REMOTE_ADDR'];

function checkLicenseValidity($verificationURL, $clientIP) {
    $ch = curl_init();
    if (!$ch) {
        die("CURL başlatılamadı.");
    }
    curl_setopt($ch, CURLOPT_URL, $verificationURL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('ip_address' => $clientIP)));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    if ($response === false) {
        die("CURL hatası: " . curl_error($ch));
    }

    curl_close($ch);

    if ($response === "valid") {
        return true;
    } else {
        return false;
    }
}

if(!$validLicense) {
    $validLicense = checkLicenseValidity($verificationURL, $clientIP);
}

if ($validLicense) {
} else {
    $errorPage = file_get_contents("https://esmenya.com.tr/licence/invalid");
    if ($errorPage === false) {
        die("Hata sayfası alınamadı.");
    }
    echo $errorPage;
    exit;
}
