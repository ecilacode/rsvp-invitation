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

<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest"> 

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome - Jaehanne's Party</title>
  <!-- Barbie-style fonts -->
  <link rel="preload" href="https:/ /fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" as="style" onload="this.rel='stylesheet'">
  <link rel="preload" href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&display=swap" as="style" onload="this.rel='stylesheet'">
  <link rel="preload" href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700&display=swap" as="style" onload="this.rel='stylesheet'">
  <link rel="preload" href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600;700&display=swap" as="style" onload="this.rel='stylesheet'">
  <link rel="preload" href="https://fonts.googleapis.com/css2?family=Kalam:wght@300;400;700&display=swap" as="style" onload="this.rel='stylesheet'">

  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
      font-family:'Nunito', sans-serif;
      background: linear-gradient(135deg, #FF69B4 0%, #FF1493 50%, #E91E63 100%);
      min-height:100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      padding:1rem;
      overflow-x:hidden;
    }

    .sparkles {
      position:fixed; top:0; left:0;
      width:100%; height:100%;
      z-index:-1; pointer-events:none;
    }
    .sparkle {
      position:absolute; width:6px; height:6px;
      background:rgba(255,255,255,0.8);
      border-radius:50%;
      animation:sparkle 3s infinite ease-in-out;
    }
    @keyframes sparkle {
      0%,100% {opacity:0.3; transform:scale(0.5) rotate(0deg);}
      50% {opacity:1; transform:scale(1.2) rotate(180deg);}
    }

    .welcome-container {
      background:rgba(255,255,255,0.95);
      backdrop-filter:blur(20px);
      border-radius:30px;
      padding:3rem 2rem;
      max-width:600px; width:100%;
      text-align:center;
      box-shadow:0 20px 40px rgba(0,0,0,0.3),
                 inset 0 1px 0 rgba(255,255,255,0.8);
      border:3px solid rgba(255,255,255,0.3);
      position:relative;
      overflow:hidden;
    }

    .welcome-header {
      font-family:'Comfortaa', cursive;
      font-weight:700;
      font-size:clamp(2rem,8vw,3.5rem);
      color:#E91E63;
      text-transform:lowercase;
      margin-bottom:1rem;
      text-shadow:2px 2px 0 #FFE609,
                  4px 4px 8px rgba(0,0,0,0.2);
      position:relative;
      z-index:1;
    }

    .guest-name {
      font-family:'Fredoka', sans-serif;
      font-weight:700;
      color:#FF1493;
    }

    .caption {
      font-size:1.2rem;
      color:#555;
      margin:1rem 0 2rem 0;
    }

    .action-buttons {
      display:flex;
      flex-direction:column;
      gap:1rem;
      margin-top:2rem;
    }

    .btn {
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap:0.5rem;
      padding:1rem 2rem;
      background:linear-gradient(45deg,#FF69B4,#FF1493);
      color:white;
      text-decoration:none;
      border-radius:25px;
      font-family:'Quicksand', sans-serif;
      font-weight:700;
      font-size:clamp(0.9rem,3vw,1.1rem);
      transition:all 0.3s ease;
      box-shadow:0 6px 12px rgba(255,20,147,0.3);
      border:none;
      cursor:pointer;
    }
    .btn:hover {
      transform:translateY(-3px);
      box-shadow:0 8px 16px rgba(255,20,147,0.4);
    }

    @media(max-width:768px) {
      .welcome-container {padding:2rem 1rem; margin:1rem; border-radius:20px;}
      .action-buttons {gap:0.8rem;}
      .btn {padding:0.8rem 1.5rem;}
    }
  </style>
</head>
<body>
  <!-- Sparkles -->
  <div class="sparkles">
    <div class="sparkle" style="top:20%; left:10%; animation-delay:0s;"></div>
    <div class="sparkle" style="top:80%; left:20%; animation-delay:1s;"></div>
    <div class="sparkle" style="top:30%; left:80%; animation-delay:2s;"></div>
    <div class="sparkle" style="top:70%; left:70%; animation-delay:1.5s;"></div>
    <div class="sparkle" style="top:10%; left:60%; animation-delay:0.5s;"></div>
    <div class="sparkle" style="top:90%; left:40%; animation-delay:2.5s;"></div>
  </div>

  <div class="welcome-container">
    <h1 class="welcome-header">welcome, <span class="guest-name"><?= htmlspecialchars($guest['name']) ?></span>! 🎀</h1>
    <p class="caption">You're officially part of the sparkle ✨<br>let’s make tonight unforgettable!</p>

    <div class="action-buttons">
      <a href="https://drive.google.com/drive/folders/1Wn1HUMBgVFG4RB84lCzbNuLVbvDNxnIT" target="_blank" class="btn"> 📸 View / Upload Party Photos </a>
      <a href="photobooth.php?code=<?= urlencode($guest['code']) ?>" class="btn"> ✨ Try the Party Filter </a>
    </div>
  </div>
</body>
</html>
