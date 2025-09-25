<?php
require 'db_connect.php';

// Random code generator
function generateCode($length = 6) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $length);
}

// Handle RSVP form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $rsvp_status = $_POST['rsvp_status'] ?? '';

    if ($name && in_array($rsvp_status, ['attending', 'not_attending'])) {
        $code = generateCode();

        $stmt = $pdo->prepare("INSERT INTO guests (name, code, rsvp_status, rsvp_at) VALUES (?, ?, ?, NOW())"); //Added rsvp_at to store timestamp
        // $stmt = $pdo->prepare("INSERT INTO guests (name, code, rsvp_status) VALUES (?, ?, ?)"); --- IGNORE ---
        $stmt->execute([$name, $code, $rsvp_status]);

        header("Location: thankyou.php?code=" . urlencode($code));
        exit;
    }
}
?>

<!DOCTYPE html>

<head>
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest"> 



<link rel="preload" href="https://fonts.cdnfonts.com/css/dollie-script-personal-use" as="style" onload="this.rel='stylesheet'">
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Patrick+Hand&display=swap" as="style" onload="this.rel='stylesheet'">
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" as="style" onload="this.rel='stylesheet'">
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&display=swap" as="style" onload="this.rel='stylesheet'">
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800&display=swap" as="style" onload="this.rel='stylesheet'">
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600;700&display=swap" as="style" onload="this.rel='stylesheet'">
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Kalam:wght@300;400;700&display=swap" as="style" onload="this.rel='stylesheet'">

<style>

  html {
    scroll-behavior: smooth;
}

    body {
    font-family: 'Nunito', sans-serif;
    font-weight: 500;
    background: #E9338F;
    color: white;
    font-family: sans-serif;
    font-size: x-large;
  }

  .collage-title, .faq-title, .map-title {
    font-family: 'Quicksand', sans-serif;
    font-weight: 700;
    font-size: clamp(2rem, 6vw, 3.5rem);
    text-transform: lowercase;
    letter-spacing: -0.5px;
    text-shadow: 2px 2px 0 #FF1493, 4px 4px 8px rgba(0,0,0,0.3);
}

        .nav-sidebar {
            position: fixed;
            top: 50%;
            right: 30px;
            transform: translateY(-50%);
            z-index: 1000;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 25px;
            padding: 20px 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .nav-item {
            display: flex;
            align-items: center;
            margin: 15px 0;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 8px 12px;
            border-radius: 15px;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(-5px);
        }

        .nav-item.active {
            background: rgba(255, 255, 255, 0.25);
            color: #FFE609;
            box-shadow: 0 4px 15px rgba(255, 230, 9, 0.3);
            transform: translateX(-8px);
        }

        .nav-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .nav-item:hover .nav-dot {
            background: white;
            transform: scale(1.2);
        }

        .nav-item.active .nav-dot {
            background: #FFE609;
            box-shadow: 0 0 10px #FFE609;
            transform: scale(1.3);
        }

        .nav-label {
            font-size: 0.85rem;
            white-space: nowrap;
        }

        /* Mobile responsive navigation */
        @media (max-width: 768px) {
            .nav-sidebar {
                right: 15px;
                padding: 15px 10px;
                transform: translateY(-50%) scale(0.9);
            }
            
            .nav-label {
                display: none; /* Hide labels on mobile, show only dots */
            }
            
            .nav-dot {
                margin-right: 0;
            }
        }

  /* Sparkle container */
  .sparkles {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1; /* stay behind content */
  }

  /* Dot sparkle */
  .sparkle {
    position: absolute;
    width: 4px;
    height: 4px;
    background: white;
    border-radius: 50%;
    opacity: 0.8;
    animation: sparkle 3s infinite ease-in-out;
  }

  /* Star sparkle */
  .star {
    content: "✦";
    position: absolute;
    width: 2px;
    height: 12px;
    background: white;
    opacity: 0.8;
    animation: sparkle 3s infinite ease-in-out;
    
  }
  .star::before {
    content: "";
    position: absolute;
    top: 5px;
    left: -5px;
    width: 12px;
    height: 2px;
    background: white;
  }

  /* Glow + twinkle animation */
  @keyframes sparkle {
    0%, 100% {
      transform: scale(0.5) rotate(0deg);
      opacity: 0.3;
    }
    50% {
      transform: scale(1.3) rotate(45deg);
      opacity: 1;
    }
  }

  /* Content stays above */
  .content {
    position: relative;
    z-index: 1;
    text-align: center;
  }


.hero {
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  color: white;
  text-align: center;
}

.hero h1 {
  font-family: 'Comfortaa';
  font-weight: 700;
  font-size: clamp(2.5rem, 9vw, 5rem);
  text-transform: lowercase;
  letter-spacing: -1px;
  text-shadow: 3px 3px 0 #FFE609, 5px 5px 10px rgba(0,0,0,0.4);
  font-size: 4rem;
  margin-bottom: 10px;
  color: #ffffffff;   
  text-shadow: 1px 1px 0 #ffe609ff;
  animation: glow 2.5s ease-in-out infinite alternate;
}

.hero h2{
    font-family: 'Nunito', sans-serif;
    font-weight: 700;
    font-size: clamp(1rem, 4vw, 1.5rem);
}
.hero h2, 
.hero p {
  margin: 5px 0;
}

.heropic-frame {

  display: inline-block;
  background: white;
  padding: 30px 30px 110px 30px;
  margin-top: 60px;
  position: relative;
  border-radius: 15px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}

.heropic {
  width: 30vh;
  display: block;
}

.heropic-caption {
  position: absolute;
  bottom: 35px;
  left: 50%;
  transform: translateX(-50%) rotate(-2deg);
  font-family: 'Kalam', cursive;
  font-weight: 700;
  font-size: clamp(1.5rem, 4vw, 2.2rem);
  transform: translateX(-50%) rotate(-3deg);
  color: #333;
  pointer-events: none;
}



    .faq{
        display: flex;
        text-align: center;
        flex-direction: column;
        color:white;
        margin:auto;
        max-width: 900px;
    }

    .faq-item {
        padding: 10px 0;
        text-align: center;
    }
    
    .faq-item h2{
    font-family: 'Fredoka', sans-serif;
    font-weight: 600;
    font-size: clamp(1.2rem, 4vw, 1.8rem);
    }

.arrow svg {
  width: 20px;
  height: 20px;
  transition: transform 0.3s ease;
}

.arrow-toggle {
  display: flex;
  flex-direction: column; 
}

.arrow-toggle input {
  display: none;
}

/* Rotate the SVG when open */
.arrow-toggle input:checked + h2 .arrow svg {
  transform: rotate(180deg);
}
.arrow-toggle section {
    max-height: 0;
    overflow: hidden;
    opacity: 0;
    transition: max-height 0.4s ease, opacity 0.3s ease, padding 0.3s ease;
    padding: 0; /* collapsed */
}

.arrow-toggle input:checked ~ section {
    max-height: 500px; /* enough for your tallest answer */
    opacity: 1;
    padding: 20px 0; /* add padding only when opened */
}

.map-section {
  margin: 60px auto;
  padding: 20px;
}

    #map-container { 
      width: 70%;
      max-width: 900px;
      margin: auto;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 6px 16px rgba(0,0,0,0.35);
    }
    #map {
      height: 500px; /* static small card */
      width: 100%;
    }

.rsvp {

  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;

  background-color: white;
  margin: 60px auto;      /* auto centers the box horizontally */
  padding: 20px;

  width: 60%;             /* adjust: try 50%, 40%, etc. */
  max-width: 600px;       /* keeps it from being too wide on large screens */

  border-radius: 15px;    /* rounded corners */
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); /* subtle depth */
}

.rsvpheader {
  text-align: center;
  font-family: 'Comfortaa', cursive;
  font-weight: 700;
  font-size: clamp(2.2rem, 7vw, 3.5rem);
  text-transform: lowercase;
  margin-bottom: 10px;
  color: #E9338F;   
  text-shadow: 1px 1px 0 #180e13ff; /* subtle base shadow */

  /* Softer glow animation */
  animation: glow 2.5s ease-in-out infinite alternate;
}

@keyframes glow {
  from {
    text-shadow: 0 0 5px #fff,
                 0 0 10px #e60073,
                 0 0 15px #e60073;
  }
  to {
    text-shadow: 0 0 8px #fff,
                 0 0 12px #ff4da6,
                 0 0 18px #ff4da6;
  }
}

.rsvp input[type="text"] {
    display:flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    padding: 10px;
    margin: auto;
    border: 1px solid #ccc;
    border-radius: 25px;
    font-size: 1em;
    box-sizing: border-box;
}

.rsvp select {
    display:flex;
    justify-content: center;
    align-items: center;
    width: 84%;
    padding: 10px;
    margin: 5px auto;
    border: 1px solid #ccc;
    border-radius: 25px;
    font-size: 1em;
    box-sizing: border-box;
}

button{
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 10px auto;
    background: #E9338F;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 1em;
    border-radius: 25px;
    cursor: pointer;
    transition: background 0.3s ease;
}
.collage {
  margin: 80px auto;
  max-width: 1000px;
  padding: 20px;
  text-align: center;
}

.collage-title {
  color: white;
  text-shadow: 1px 1px 3px #E9338F;
  margin-bottom: 30px;
}

.collage-grid {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  grid-auto-rows: 150px;
  gap: 12px;
}

.collage-grid img {
  width: 100%;
  height: 100%;
  object-fit: cover;            /* fill box */
  object-position: top center;  /* bias crop so faces stay visible */
  border-radius: 18px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.25);
  transition: transform 0.3s ease;
}

.collage-grid img:hover {
  transform: scale(1.05);
}

/* Staggered layout for creativity */
.collage-grid img:nth-child(1) { grid-column: span 2; grid-row: span 2; }
.collage-grid img:nth-child(2) { grid-column: span 2; grid-row: span 1; }
.collage-grid img:nth-child(3) { grid-column: span 2; grid-row: span 2; }
.collage-grid img:nth-child(4) { grid-column: span 3; grid-row: span 2; }
.collage-grid img:nth-child(5) { grid-column: span 3; grid-row: span 3; }
.collage-grid img:nth-child(6) { grid-column: span 2; grid-row: span 2; }
.collage-grid img:nth-child(7) { grid-column: span 2; grid-row: span 1; }
.collage-grid img:nth-child(8) { grid-column: span 2; grid-row: span 2; }
.collage-grid img:nth-child(9) { grid-column: span 3; grid-row: span 2; }

/* Responsive */
@media (max-width: 768px) {
  .collage-grid {
    grid-template-columns: repeat(2, 1fr);
    grid-auto-rows: 180px;
  }
  .collage-grid img {
    grid-column: span 1 !important;
    grid-row: span 1 !important;
  }
}



/* Lightbox Overlay */
.lightbox {
  display: none; /* hidden by default */
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.85);
  backdrop-filter: blur(3px);
  justify-content: center;
  align-items: center;
}

.lightbox img {
  max-width: 90%;
  max-height: 90%;
  border-radius: 12px;
  box-shadow: 0 6px 18px rgba(0,0,0,0.4);
  animation: fadeIn 0.4s ease;
}

.lightbox .close {
  position: absolute;
  top: 20px;
  right: 35px;
  font-size: 3rem;
  font-weight: bold;
  color: white;
  cursor: pointer;
  transition: transform 0.2s ease;
}

.lightbox .close:hover {
  transform: scale(1.2);
  color: #E9338F;
}

.trail-star {
  position: fixed;
  transform: translate(-50%, -50%) scale(1);
  pointer-events: none;
  user-select: none;
  color: gold;
  opacity: 1;
  transition: opacity 0.4s ease, transform 0.4s ease;
  z-index: 9999;
}


@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}



</style>

<nav class="nav-sidebar">
    <a href="#welcome" class="nav-item" data-section="welcome">
        <div class="nav-dot"></div>
        <span class="nav-label">Welcome</span>
    </a>
    <a href="#collage" class="nav-item" data-section="collage">
        <div class="nav-dot"></div>
        <span class="nav-label">Memories</span>
    </a>
    <a href="#faq" class="nav-item" data-section="faq">
        <div class="nav-dot"></div>
        <span class="nav-label">FAQs</span>
    </a>
    <a href="#rsvp" class="nav-item" data-section="rsvp">
        <div class="nav-dot"></div>
        <span class="nav-label">Registry</span>
    </a>
</nav>

  <div class="sparkles">
    <!-- Round sparkles -->
    <div class="sparkle" style="top:10%; left:20%; animation-delay:0s;"></div>
    <div class="sparkle" style="top:30%; left:70%; animation-delay:1s;"></div>
    <div class="sparkle" style="top:50%; left:40%; animation-delay:2s;"></div>
    <div class="sparkle" style="top:80%; left:60%; animation-delay:1.5s;"></div>
    <div class="sparkle" style="top:15%; left:80%; animation-delay:2.5s;"></div>

    <!-- Star-shaped sparkles -->
    <div class="star" style="top:25%; left:50%; animation-delay:0.5s;"></div>
    <div class="star" style="top:60%; left:30%; animation-delay:1.2s;"></div>
    <div class="star" style="top:75%; left:70%; animation-delay:2.3s;"></div>
    <div class="star" style="top:90%; left:15%; animation-delay:1.8s;"></div>
    <div class="star" style="top:40%; left:85%; animation-delay:2.7s;"></div>
  </div>


<!-- Hero Section -->  
<section class="hero" id="welcome">
  <h1>You're Invited!</h1>
  <h2>A night to sparkle and shine!✨</h2>
  <p>October 10, Friday, 6PM! Casa Ibarra, MOA Complex, Pasay</p>
  
  <div class="heropic-frame">
    <img class="heropic" src="images/EDITED.png" alt="hero_image" loading="lazy">
    <span class="heropic-caption">10.10.25</span>
  </div>
</section>




<!-- Collage Section -->
<section class="collage" id="collage">
  <h2 class="collage-title">Memories &amp; Moments 📸</h2>
  <div class="collage-grid">
    <img src="images/DSCF2590.jpg" alt="pic1" loading="lazy">
    <img src="images/DSCF2608.jpg" alt="pic2" loading="lazy">
    <img src="images/DSCF2627.jpg" alt="pic3" loading="lazy">
    <img src="images/DSCF2651.jpg" alt="pic4" loading="lazy">
    <img src="images/DSCF2628.jpg" alt="pic5" loading="lazy">
    <img src="images/DSCF2698.jpg" alt="pic6" loading="lazy">
    <img src="images/DSCF2702.jpg" alt="pic7" loading="lazy">
    <img src="images/DSCF2713.jpg" alt="pic8" loading="lazy">
    <img src="images/DSCF2716.jpg" alt="pic9" loading="lazy">
  </div>
</section>


<!-- Lightbox Overlay -->
<div id="lightbox" class="lightbox">
  <span class="close">&times;</span>
  <img class="lightbox-content" id="lightbox-img" src="" alt="">
</div>

<!-- FAQ section -->
<section class="faq" id="faq">
  <div class="faq-item" id="q1">
    <label class="arrow-toggle">
      <input type="checkbox" id="q1">
      <h2>
        When is the party?
        <span class="arrow">
          <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" 
               width="20" height="20" fill="currentColor">
            <path d="m16.843 10.211c.108-.141.157-.3.157-.456 
                     0-.389-.306-.755-.749-.755h-8.501c-.445 0-.75.367-.75.755 
                     0 .157.05.316.159.457 1.203 1.554 3.252 4.199 
                     4.258 5.498.142.184.36.29.592.29.23 0 
                     .449-.107.591-.291 1.002-1.299 3.044-3.945 
                     4.243-5.498z"/>
          </svg>
        </span>
      </h2>
      <section id="q1a">
        <p>The party starts on the 10th of October. Doors will open at 6pm.</p>
      </section>
    </label>
  </div>

  <div class="faq-item" id="q2">
    <label class="arrow-toggle">
      <input type="checkbox" id="q2">
      <h2>
        Can I take pictures during the debut?
        <span class="arrow">
          <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" 
               width="20" height="20" fill="currentColor">
            <path d="m16.843 10.211c.108-.141.157-.3.157-.456 
                     0-.389-.306-.755-.749-.755h-8.501c-.445 0-.75.367-.75.755 
                     0 .157.05.316.159.457 1.203 1.554 3.252 4.199 
                     4.258 5.498.142.184.36.29.592.29.23 0 
                     .449-.107.591-.291 1.002-1.299 3.044-3.945 
                     4.243-5.498z"/>
          </svg>
        </span>
      </h2>
      <section id="q2a">
        <p>
          We encourage taking pictures and making memories to this event! Here's a link to join the <a href="https://drive.google.com/drive/folders/1Wn1HUMBgVFG4RB84lCzbNuLVbvDNxnIT" target="_blank">Google Photo Album.</a>  
          We also have a <a href="photobooth.php" target="_blank"> custom filter</a> you can use to make this moment part of your day!
        </p>
      </section>
    </label>
  </div>

  <div class="faq-item" id="q3">
    <label class="arrow-toggle">
      <input type="checkbox" id="q3">
      <h2>
        How do I get to Casa Ibarra?
        <span class="arrow">
          <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" 
               width="20" height="20" fill="currentColor">
            <path d="m16.843 10.211c.108-.141.157-.3.157-.456 
                     0-.389-.306-.755-.749-.755h-8.501c-.445 0-.75.367-.75.755 
                     0 .157.05.316.159.457 1.203 1.554 3.252 4.199 
                     4.258 5.498.142.184.36.29.592.29.23 0 
                     .449-.107.591-.291 1.002-1.299 3.044-3.945 
                     4.243-5.498z"/>
          </svg>
        </span>
      </h2>
      <section id="q3a">
        <h3>By car</h3>
        <p>Expect there will be medium to heavy traffic on the way to the venue since it’s a Friday</p>
        <p><strong>Scroll down below to view the map and come over using Waze and Google Maps!</strong></p>
      </section>
    </label>
  </div>

  <div class="faq-item" id="q4">
    <label class="arrow-toggle">
      <input type="checkbox" id="q4">
      <h2>
        Where should I park?
        <span class="arrow">
          <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" 
               width="20" height="20" fill="currentColor">
            <path d="m16.843 10.211c.108-.141.157-.3.157-.456 
                     0-.389-.306-.755-.749-.755h-8.501c-.445 0-.75.367-.75.755 
                     0 .157.05.316.159.457 1.203 1.554 3.252 4.199 
                     4.258 5.498.142.184.36.29.592.29.23 0 
                     .449-.107.591-.291 1.002-1.299 3.044-3.945 
                     4.243-5.498z"/>
          </svg>
        </span>
      </h2>
      <section id="q4a">
        <p>Casa Ibarra has accomodations for vehicle parking right beside the venue</p>
      </section>
    </label>
  </div>

  <div class="faq-item" id="q5">
    <label class="arrow-toggle">
      <input type="checkbox" id="q5">
      <h2>
        What is the dress code?
        <span class="arrow">
          <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" 
               width="20" height="20" fill="currentColor">
            <path d="m16.843 10.211c.108-.141.157-.3.157-.456 
                     0-.389-.306-.755-.749-.755h-8.501c-.445 0-.75.367-.75.755 
                     0 .157.05.316.159.457 1.203 1.554 3.252 4.199 
                     4.258 5.498.142.184.36.29.592.29.23 0 
                     .449-.107.591-.291 1.002-1.299 3.044-3.945 
                     4.243-5.498z"/>
          </svg>
        </span>
      </h2>
      <section id="q5a">
        <p>
          Dress to impress! The theme is "Shine and Sparkle" so think glitter, sequins, and all things fabulous.  
          Ladies, this is your chance to rock that stunning dress you've been saving for a special occasion.  
          Gentlemen, a sharp suit or dress shirt will have you looking dapper and ready to party.
        </p>
      </section>
    </label>
  </div>
</section>





      <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />


  <div id="map-container">
    <div id="map"></div>
  </div>

  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

  <script>
    // Casa Ibarra exact coordinates
    const coords = [14.5310727, 120.9864595];

    // Initialize map
    const map = L.map('map').setView(coords, 17);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add marker with popup and directions
L.marker(coords).addTo(map)
  .bindPopup(`
    <b>Casa Ibarra</b><br>
    Pasay City, Metro Manila<br><br>
    <a href="https://www.google.com/maps/dir/?api=1&destination=14.5310727,120.9864595" target="_blank">
      📍 Open in Google Maps
    </a><br>
    <a href="https://waze.com/ul?ll=14.5310727%2C120.9864595&navigate=yes" target="_blank">
      🚗 Open in Waze
    </a>
  `)
  .openPopup();

  //mousemove trail
let lastTime = 0;

document.addEventListener('mousemove', function(e) {
  const now = performance.now();

  // limit to ~60fps (spawn max once per frame)
  if (now - lastTime < 16) return;
  lastTime = now;

  const star = document.createElement('div');
  star.classList.add('trail-star');
  star.textContent = "⭐️"; // star emoji
  document.body.appendChild(star);

  // random size between 12px and 28px
  const size = Math.random() * 16 + 12; 
  star.style.fontSize = `${size}px`;

  // position at cursor
  star.style.left = `${e.clientX}px`;
  star.style.top = `${e.clientY}px`;

  // fade + shrink animation
  requestAnimationFrame(() => {
    star.style.opacity = "0";
    star.style.transform = "translate(-50%, -50%) scale(0.5)";
  });

  // remove after animation
  setTimeout(() => {
    star.remove();
  }, 400);
});
    </script>

</section>

<!-- RSVP Section -->
<section class="rsvp" id="rsvp">
<span class="rsvpheader">Tell us you're coming!</span>
    <form method="POST" action="">
        <input type="text" name="name" placeholder="Enter your full name" autocomplete="given-name family-name"  required>
        <select name="rsvp_status" required>
            <option value="">--Select Here--</option>
            <option value="attending">Attending</option>
            <option value="not_attending">Not Attending</option>
        </select>
        <button type="submit">Submit RSVP</button>
    </form>
</div>
</section>

<script>
  // Select elements
  const lightbox = document.getElementById("lightbox");
  const lightboxImg = document.getElementById("lightbox-img");
  const closeBtn = document.querySelector(".lightbox .close");

  // Add click event to collage images
  document.querySelectorAll(".collage-grid img").forEach(img => {
    img.addEventListener("click", () => {
      lightbox.style.display = "flex";
      lightboxImg.src = img.src;
    });
  });

  // Close on click of X
  closeBtn.addEventListener("click", () => {
    lightbox.style.display = "none";
  });

  // Close on outside click
  lightbox.addEventListener("click", (e) => {
    if (e.target === lightbox) {
      lightbox.style.display = "none";
    }
  });

  // Close on ESC key
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      lightbox.style.display = "none";
    }
  });


  // Navigation functionality
    const navItems = document.querySelectorAll('.nav-item');
    const sections = document.querySelectorAll('section[id]');

    // Smooth scroll to section when nav item is clicked
    navItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = item.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);
            
            if (targetSection) {
                targetSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Highlight active section on scroll
    function updateActiveNav() {
        let current = '';
        const scrollPosition = window.pageYOffset + window.innerHeight / 3; // Better offset for detection
        
        // Check each section to find which one is currently in view
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            const sectionId = section.getAttribute('id');
            
            // If we're within this section's boundaries
            if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                current = sectionId;
            }
        });

        // Special case: if we're at the very top, always highlight welcome
        if (window.pageYOffset < 100) {
            current = 'welcome';
        }

        // Special case: if we're at the very bottom, highlight rsvp
        if (window.innerHeight + window.pageYOffset >= document.body.offsetHeight - 100) {
            current = 'rsvp';
        }

        // Update active nav item
        navItems.forEach(item => {
            item.classList.remove('active');
            const navSection = item.getAttribute('data-section');
            if (navSection === current) {
                item.classList.add('active');
            }
        });
        
        // Debug logging (remove in production)
        console.log('Current section:', current, 'Scroll position:', scrollPosition);
    }

    // Listen for scroll events with throttling for better performance
    let scrollTimeout;
    window.addEventListener('scroll', () => {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(updateActiveNav, 10);
    });
    
    // Set initial active section
    setTimeout(updateActiveNav, 100); // Delay to ensure page is loaded
</script>

</body>