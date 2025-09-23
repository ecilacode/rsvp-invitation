<?php
require 'libs/phpqrcode/phpqrcode.php';

$code = $_GET['code'] ?? '';
if (!$code) {
    die("No code provided.");
}

$url = "http://localhost/rsvp_invitation/index.php?code=" . urlencode($code);

// If download requested
if (isset($_GET['download'])) {
    header('Content-Type: image/png');
    header('Content-Disposition: attachment; filename="qr_' . $code . '.png"');
    QRcode::png($url, false, QR_ECLEVEL_L, 4);
    exit;
}

// Otherwise, just display inline
header('Content-Type: image/png');
QRcode::png($url, false, QR_ECLEVEL_L, 4);
exit;
