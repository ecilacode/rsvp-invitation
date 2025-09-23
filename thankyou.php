<?php
require 'db_connect.php';

$code = $_GET['code'] ?? '';
if (!$code) {
    die("Invalid link.");
}

$stmt = $pdo->prepare("SELECT * FROM guests WHERE code = ?");
$stmt->execute([$code]);
$guest = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$guest) {
    die("Guest not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thank You</title>
    <style>
        body { font-family: 'Arial', sans-serif; text-align: center; background: #fff0f5; }
        .container { max-width: 600px; margin: 50px auto; background: white; padding: 20px; border-radius: 10px; }
        h1 { color: #d63384; }
        .qr { margin-top: 20px; }
        a.download-btn { display: inline-block; margin-top: 15px; padding: 10px 15px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; }
        a.download-btn:hover { background: #218838; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Thank you, <?= htmlspecialchars($guest['name']) ?>!</h1>
        <p>Your RSVP has been recorded as: <strong><?= ucfirst($guest['rsvp_status']) ?></strong></p>

        <div class="qr">
            <h3>Your QR Code</h3>
            <img src="qr.php?code=<?= urlencode($guest['code']) ?>" alt="QR Code">
            <br>
            <a href="qr.php?code=<?= urlencode($guest['code']) ?>&download=1" class="download-btn">Download QR</a>
        </div>
    </div>
</body>
</html>
