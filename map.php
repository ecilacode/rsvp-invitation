<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Casa Ibarra Event Map</title>

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
      background: #f4f6f8;
    }
    h2 {
      margin-bottom: 10px;
      text-align: center;
    }
    #map-container {
      position: relative;
      width: 100%;
      max-width: 900px;
      margin: auto;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 6px 16px rgba(0,0,0,0.15);
    }
    #map {
      height: 500px; /* static small card */
      width: 100%;
    }
  </style>
</head>
<body>
  <h2>📍 Casa Ibarra - Event Venue</h2>
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

  </script>
</body>
</html>
