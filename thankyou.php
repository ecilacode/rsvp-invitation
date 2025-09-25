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

// Calendar event details - Philippine Time (UTC+8)
$event_title = "Jaehanne's 18th Birthday Party";
$event_date = "20241010T100000Z"; // October 10, 2024, 6:00 PM Philippine Time (10:00 AM UTC)
$event_end = "20241010T150000Z";  // October 10, 2024, 11:00 PM Philippine Time (3:00 PM UTC)
$event_location = "Casa Ibarra, MOA Complex, Pasay City, Philippines";
$event_description = "Join us for a night of shine and sparkle! Dress code: Anything that shines, sparkles, and glitters (except pink for ladies). Party like Barbie!";

// Generate ICS file content
function generateICS($title, $start, $end, $location, $description) {
    $ics_content = "BEGIN:VCALENDAR\r\n";
    $ics_content .= "VERSION:2.0\r\n";
    $ics_content .= "PRODID:-//Jaehanne Birthday Party//EN\r\n";
    $ics_content .= "BEGIN:VEVENT\r\n";
    $ics_content .= "UID:" . uniqid() . "@example.com\r\n";
    $ics_content .= "DTSTAMP:" . gmdate('Ymd\THis\Z') . "\r\n";
    $ics_content .= "DTSTART:" . $start . "\r\n";
    $ics_content .= "DTEND:" . $end . "\r\n";
    $ics_content .= "SUMMARY:" . $title . "\r\n";
    $ics_content .= "DESCRIPTION:" . $description . "\r\n";
    $ics_content .= "LOCATION:" . $location . "\r\n";
    $ics_content .= "BEGIN:VALARM\r\n";
    $ics_content .= "TRIGGER:-PT1H\r\n"; // 1 hour before
    $ics_content .= "ACTION:DISPLAY\r\n";
    $ics_content .= "DESCRIPTION:Party starts in 1 hour!\r\n";
    $ics_content .= "END:VALARM\r\n";
    $ics_content .= "END:VEVENT\r\n";
    $ics_content .= "END:VCALENDAR\r\n";
    
    return $ics_content;
}

// Handle calendar download
if (isset($_GET['calendar']) && $_GET['calendar'] === 'download') {
    $ics_content = generateICS($event_title, $event_date, $event_end, $event_location, $event_description);
    
    header('Content-Type: text/calendar; charset=utf-8');
    header('Content-Disposition: attachment; filename="Jaehanne_Birthday_Party.ics"');
    header('Content-Length: ' . strlen($ics_content));
    
    echo $ics_content;
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - Jaehanne's Party</title>
    
    <!-- Barbie-style fonts -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" as="style" onload="this.rel='stylesheet'">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&display=swap" as="style" onload="this.rel='stylesheet'">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700&display=swap" as="style" onload="this.rel='stylesheet'">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600;700&display=swap" as="style" onload="this.rel='stylesheet'">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Kalam:wght@300;400;700&display=swap" as="style" onload="this.rel='stylesheet'">
    
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #FF69B4 0%, #FF1493 50%, #E91E63 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            overflow-x: hidden;
        }

        /* Animated background sparkles */
        .sparkles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
        }

        .sparkle {
            position: absolute;
            width: 6px;
            height: 6px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            animation: sparkle 3s infinite ease-in-out;
        }

        @keyframes sparkle {
            0%, 100% {
                opacity: 0.3;
                transform: scale(0.5) rotate(0deg);
            }
            50% {
                opacity: 1;
                transform: scale(1.2) rotate(180deg);
            }
        }

        /* Main container */
        .thank-you-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 3rem 2rem;
            max-width: 600px;
            width: 100%;
            text-align: center;
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            border: 3px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        .thank-you-container::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(circle at 30% 30%, rgba(255, 105, 180, 0.1) 2px, transparent 3px),
                radial-gradient(circle at 70% 70%, rgba(255, 20, 147, 0.1) 1px, transparent 2px);
            background-size: 60px 60px, 40px 40px;
            animation: backgroundMove 15s linear infinite;
            pointer-events: none;
        }

        @keyframes backgroundMove {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Header styling */
        .thank-you-header {
            font-family: 'Comfortaa', cursive;
            font-weight: 700;
            font-size: clamp(2rem, 8vw, 3.5rem);
            color: #E91E63;
            text-transform: lowercase;
            margin-bottom: 1rem;
            text-shadow: 
                2px 2px 0 #FFE609,
                4px 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
        }

        /* Hero image - Fixed overlapping */
        .hero-image {
            width: 150px;
            height: 180px; /* Reduced height */
            margin: 1.5rem auto;
            background: white;
            border-radius: 15px;
            padding: 15px 15px 45px 15px; /* Reduced bottom padding */
            position: relative;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            transform: rotate(-2deg);
            transition: transform 0.3s ease;
            z-index: 2; /* Ensure it's above background elements */
        }

        .hero-image:hover {
            transform: rotate(0deg) scale(1.05);
        }

        .hero-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: 50% 10%;
            border-radius: 8px;
        }

        .hero-image::after {
            content: "see you there!✨";
            position: absolute;
            bottom: 5px; /* Reduced bottom position */
            left: 50%;
            transform: translateX(-50%);
            font-family: 'Kalam', cursive;
            font-weight: 700;
            font-size: 0.7rem; /* Slightly smaller */
            color: #E91E63;
        }

        /* Different caption for not attending */
        .hero-image.not-attending::after {
            content: "maybe next time!";
            font-size: 0.7rem; /* Slightly smaller */
        }

        /* Content styling */
        .guest-info {
            font-family: 'Nunito', sans-serif;
            font-size: clamp(1rem, 4vw, 1.3rem);
            color: #333;
            margin: 2rem 0;
            position: relative;
            z-index: 1;
        }

        .guest-name {
            font-family: 'Fredoka', sans-serif;
            font-weight: 700;
            color: #FF1493;
            font-size: 1.2em;
        }

        .rsvp-status {
            display: inline-block;
            background: linear-gradient(45deg, #FF69B4, #FF1493);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 700;
            margin: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Button container */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin: 2rem 0;
            position: relative;
            z-index: 1;
        }

        /* Calendar button */
        .calendar-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            background: linear-gradient(45deg, #4CAF50, #45a049);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-family: 'Quicksand', sans-serif;
            font-weight: 700;
            font-size: clamp(0.9rem, 3vw, 1.1rem);
            text-transform: lowercase;
            transition: all 0.3s ease;
            box-shadow: 0 6px 12px rgba(76, 175, 80, 0.3);
            border: none;
            cursor: pointer;
        }

        .calendar-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(76, 175, 80, 0.4);
            background: linear-gradient(45deg, #45a049, #4CAF50);
        }

        /* QR Code section */
        .qr-section {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 20px;
            padding: 1.5rem;
            margin: 2rem 0;
            border: 2px solid rgba(255, 105, 180, 0.3);
            position: relative;
            z-index: 1;
        }

        .qr-title {
            font-family: 'Fredoka', sans-serif;
            font-weight: 600;
            font-size: clamp(1.2rem, 4vw, 1.5rem);
            color: #E91E63;
            margin-bottom: 1rem;
            text-transform: lowercase;
        }

        .qr-code {
            background: white;
            padding: 1rem;
            border-radius: 15px;
            display: inline-block;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }

        .qr-code img {
            display: block;
            max-width: 150px;
            height: auto;
        }

        /* Download QR button */
        .download-qr-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.8rem 1.5rem;
            background: linear-gradient(45deg, #FF69B4, #FF1493);
            color: white;
            text-decoration: none;
            border-radius: 20px;
            font-family: 'Nunito', sans-serif;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(255, 20, 147, 0.3);
        }

        .download-qr-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(255, 20, 147, 0.4);
        }

        /* Event details */
        .event-details {
            background: rgba(255, 230, 9, 0.1);
            border-radius: 15px;
            padding: 1rem;
            margin: 1.5rem 0;
            border-left: 4px solid #FFE609;
            position: relative;
            z-index: 1;
        }

        .event-details h3 {
            font-family: 'Fredoka', sans-serif;
            color: #E91E63;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .event-details p {
            font-family: 'Nunito', sans-serif;
            color: #666;
            font-size: 0.9rem;
            margin: 0.3rem 0;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .thank-you-container {
                padding: 2rem 1rem;
                margin: 1rem;
                border-radius: 20px;
            }

            .hero-image {
                width: 120px;
                height: 160px;
            }

            .action-buttons {
                gap: 0.8rem;
            }

            .calendar-btn {
                padding: 0.8rem 1.5rem;
            }
        }

        /* Success animation */
        .success-icon {
            font-size: 3rem;
            color: #4CAF50;
            margin-bottom: 1rem;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>
<body>
    <!-- Animated background sparkles -->
    <div class="sparkles">
        <div class="sparkle" style="top: 20%; left: 10%; animation-delay: 0s;"></div>
        <div class="sparkle" style="top: 80%; left: 20%; animation-delay: 1s;"></div>
        <div class="sparkle" style="top: 30%; left: 80%; animation-delay: 2s;"></div>
        <div class="sparkle" style="top: 70%; left: 70%; animation-delay: 1.5s;"></div>
        <div class="sparkle" style="top: 10%; left: 60%; animation-delay: 0.5s;"></div>
        <div class="sparkle" style="top: 90%; left: 40%; animation-delay: 2.5s;"></div>
    </div>

    <div class="thank-you-container">
        <div class="success-icon">🎉</div>
        
        <h1 class="thank-you-header">thank you, <span class="guest-name"><?= htmlspecialchars($guest['name']) ?></span>!</h1>
        
        <!-- Hero image from original invitation -->
        <?php if ($guest['rsvp_status'] === 'attending'): ?>
        <div class="hero-image">
            <img src="images/DSCF2668.jpg" alt="Party Preview" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
            
        </div>
        <?php else: ?>
        <div class="hero-image not-attending" style="transform: rotate(2deg);">
            <div style="width: 100%; height: 100%; background: linear-gradient(45deg, #6c757d, #495057); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem;">💙</div>
        </div>
        <?php endif; ?>

        <div class="guest-info">
            <?php if ($guest['rsvp_status'] === 'attending'): ?>
                <p>Your RSVP has been recorded as:</p>
                <span class="rsvp-status">Attending</span>
            <?php else: ?>
                <p>Your RSVP has been recorded as:</p>
                <span class="rsvp-status" style="background: linear-gradient(45deg, #6c757d, #495057);">Not Attending</span>
                <div style="margin-top: 1.5rem; padding: 1rem; background: rgba(255, 193, 7, 0.1); border-radius: 15px; border-left: 4px solid #ffc107;">
                    <p style="color: #856404; font-size: 0.95rem; line-height: 1.5;">
                        We're sad you can't make it, but we understand! 💛<br>
                        You'll be missed on this special night. Maybe next time! ✨
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Event Details and Calendar - Only show if attending -->
        <?php if ($guest['rsvp_status'] === 'attending'): ?>
        <div class="event-details">
            <h3>📅 Event Details</h3>
            <p><strong>Date:</strong> October 10, 2024 (Friday)</p>
            <p><strong>Time:</strong> 6:00 PM onwards</p>
            <p><strong>Venue:</strong> Casa Ibarra, MOA Complex, Pasay</p>
            <p><strong>Theme:</strong> Shine and Sparkle ✨</p>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="?code=<?= urlencode($code) ?>&calendar=download" class="calendar-btn">
                📅 Add to Calendar
            </a>
        </div>

        <!-- QR Code Section -->
        <div class="qr-section">
            <h3 class="qr-title">your entry qr code</h3>
            <div class="qr-code">
                <img src="qr.php?code=<?= urlencode($guest['code']) ?>" alt="Entry QR Code">
            </div>
            <p style="font-size: 0.85rem; color: #666; margin-bottom: 1rem;">
                Show this QR code at the party entrance
            </p>
            <a href="qr.php?code=<?= urlencode($guest['code']) ?>&download=1" class="download-qr-btn">
                💾 Download QR Code
            </a>
        </div>

        <div style="margin-top: 2rem; font-size: 0.9rem; color: #888; font-style: italic;">
            Can't wait to see you sparkle and shine! 💖✨
        </div>
        <?php else: ?>
        <div style="margin-top: 2rem; font-size: 0.9rem; color: #888; font-style: italic;">
            Hope to celebrate with you another time! 💕
        </div>
        <?php endif; ?>
    </div>

    <script>
        // Add some interactive sparkle effects
        document.addEventListener('mousemove', function(e) {
            if (Math.random() > 0.9) { // Only occasionally create sparkles
                const sparkle = document.createElement('div');
                sparkle.style.position = 'fixed';
                sparkle.style.left = e.clientX + 'px';
                sparkle.style.top = e.clientY + 'px';
                sparkle.style.width = '4px';
                sparkle.style.height = '4px';
                sparkle.style.background = 'gold';
                sparkle.style.borderRadius = '50%';
                sparkle.style.pointerEvents = 'none';
                sparkle.style.zIndex = '9999';
                sparkle.style.animation = 'sparkle 1s ease-out forwards';
                
                document.body.appendChild(sparkle);
                
                setTimeout(() => {
                    sparkle.remove();
                }, 1000);
            }
        });
    </script>
</body>
</html>