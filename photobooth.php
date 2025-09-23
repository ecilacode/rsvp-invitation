<?php
// Handle submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photos'])) {
    $photos = $_FILES['photos'];
    $frame = $_POST['frame'] ?? 'none';

    // Limit: 1–4 photos
    $count = count($photos['name']);
    if ($count < 1 || $count > 4) {
        die("Please upload between 1 and 4 photos.");
    }

    // Debug (uncomment if needed)
    // var_dump($photos['name']); exit;

    // Load images into GD
    $gdImages = [];
    foreach ($photos['tmp_name'] as $i => $tmp) {
        $mime = mime_content_type($tmp);
        if (!in_array($mime, ['image/jpeg','image/png','image/webp'])) {
            die("Unsupported file type: $mime");
        }

        $img = @imagecreatefromstring(file_get_contents($tmp));
        if ($img === false) {
            die("Invalid image uploaded.");
        }

        // Resize each image to consistent size (300x400)
        $resized = imagecreatetruecolor(300, 400);
        imagecopyresampled($resized, $img, 0, 0, 0, 0, 300, 400, imagesx($img), imagesy($img));
        $gdImages[] = $resized;
        imagedestroy($img);
    }

    // Create final canvas (strip layout: stacked vertically)
    $finalHeight = 400 * $count;
    $strip = imagecreatetruecolor(300, $finalHeight);
    $white = imagecolorallocate($strip, 255, 255, 255);
    imagefill($strip, 0, 0, $white);

    // Paste each photo
    foreach ($gdImages as $i => $gd) {
        imagecopy($strip, $gd, 0, $i * 400, 0, 0, 300, 400);
        imagedestroy($gd);
    }

    // Add frame overlay if chosen
    if ($frame !== 'none') {
        $frameFile = __DIR__ . "/frames/{$frame}.png"; // overlay PNGs stored in /frames/
        if (file_exists($frameFile)) {
            $overlay = imagecreatefrompng($frameFile);
            imagecopyresampled($strip, $overlay, 0, 0, 0, 0, 300, $finalHeight, imagesx($overlay), imagesy($overlay));
            imagedestroy($overlay);
        }
    }

    // Output to base64
    ob_start();
    imagepng($strip);
    $imageData = ob_get_clean();
    imagedestroy($strip);

    $base64 = 'data:image/png;base64,' . base64_encode($imageData);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Barbie Photobooth 💖</title>
  <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;700&family=Great+Vibes&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Barlow', sans-serif;
      background: linear-gradient(135deg, #ff8ad8, #ff48c4, #ff85a1);
      color: white;
      text-align: center;
      padding: 40px;
    }
    h1 {
      font-family: 'Great Vibes', cursive;
      font-size: 3em;
      margin-bottom: 20px;
    }
    form {
      background: white;
      color: black;
      padding: 20px;
      border-radius: 15px;
      display: inline-block;
      text-align: left;
    }
    input, button {
      width: 100%;
      padding: 10px;
      margin: 8px 0;
      border-radius: 8px;
      border: 1px solid #ccc;
    }
    button {
      background: #ff48c4;
      color: white;
      font-weight: bold;
      cursor: pointer;
    }
    button:hover {
      background: #d63384;
    }
    .frame-preview {
      display: flex;
      justify-content: space-around;
      margin-top: 15px;
      flex-wrap: wrap;
    }
    .frame-option {
      text-align: center;
      font-size: 0.9em;
      cursor: pointer;
      margin: 5px;
    }
    .frame-option img {
      width: 100px;
      height: 120px;
      border-radius: 10px;
      border: 3px solid transparent;
      object-fit: cover;
      transition: border 0.3s ease;
    }
    .frame-option.selected img {
      border: 3px solid #ff48c4;
      box-shadow: 0 0 10px #ff48c4;
    }
    .preview {
      margin-top: 30px;
      background: white;
      padding: 20px;
      border-radius: 15px;
      display: inline-block;
    }
    .preview img {
      max-width: 300px;
      border-radius: 10px;
      border: 3px solid #ff48c4;
    }
  </style>
</head>
<body>
  <h1>📸 Barbie Glam Photobooth 🎀</h1>

  <form method="POST" enctype="multipart/form-data" id="uploadForm">
    <label>Upload 1–4 photos:</label>
    <input type="file" name="photos[]" id="photoInput" multiple accept="image/*" required>

    <label>Choose a frame:</label>
    <input type="hidden" name="frame" id="frameInput" value="none">

    <div class="frame-preview">
      <div class="frame-option selected" data-frame="none">
        <img src="https://via.placeholder.com/100x120/ff48c4/ffffff?text=No+Frame" alt="No Frame">
        <p>No Frame</p>
      </div>
      <div class="frame-option" data-frame="barbie">
        <img src="frames/barbie.png" alt="Barbie Frame">
        <p>Barbie</p>
      </div>
      <div class="frame-option" data-frame="sparkle">
        <img src="frames/sparkle.png" alt="Sparkle Frame">
        <p>Sparkle</p>
      </div>
      <div class="frame-option" data-frame="hearts">
        <img src="https://via.placeholder.com/100x120/ff85a1/ffffff?text=❤️" alt="Hearts Frame">
        <p>Hearts</p>
      </div>
    </div>

    <button type="submit">✨ Generate Photo Strip ✨</button>
  </form>
<script src="https://unpkg.com/heic2any/dist/heic2any.min.js"></script>
<script>
  const form = document.getElementById('uploadForm');
  const fileInput = document.getElementById('photoInput');

  form.addEventListener('submit', async function (e) {
    e.preventDefault(); // stop auto-submit
    const files = fileInput.files;
    if (!files.length) {
      alert("Please upload at least one photo.");
      return;
    }

    const newFiles = [];
    for (let file of files) {
      let processedFile = file;

      // ✅ Convert HEIC → JPG
      if (
        file.type === "image/heic" ||
        file.type === "image/heif" ||
        file.name.toLowerCase().endsWith(".heic")
      ) {
        console.log("Converting HEIC:", file.name);
        try {
          const blob = await heic2any({ blob: file, toType: "image/jpeg" });
          processedFile = new File([blob], file.name.replace(/\.heic$/i, ".jpg"), { type: "image/jpeg" });
        } catch (err) {
          console.error("HEIC conversion failed:", err);
          alert("Conversion failed: " + err.message);
          return;
        }
      }

      // ✅ Resize (max width/height 1200px, maintain aspect ratio)
      processedFile = await resizeImage(processedFile, 1200, 1200);
      newFiles.push(processedFile);
    }

    // Replace input files with processed ones
    const dt = new DataTransfer();
    newFiles.forEach(f => dt.items.add(f));
    fileInput.files = dt.files;

    // ✅ Now submit for real
    form.submit();
  });

  // Frame selection handler
  document.querySelectorAll('.frame-option').forEach(option => {
    option.addEventListener('click', () => {
      document.querySelectorAll('.frame-option').forEach(o => o.classList.remove('selected'));
      option.classList.add('selected');
      document.getElementById('frameInput').value = option.dataset.frame;
    });
  });

  // 🔧 Resize function using Canvas
  async function resizeImage(file, maxWidth, maxHeight) {
    return new Promise((resolve, reject) => {
      const img = new Image();
      const reader = new FileReader();

      reader.onload = e => {
        img.onload = () => {
          let { width, height } = img;

          // maintain aspect ratio
          if (width > maxWidth || height > maxHeight) {
            const ratio = Math.min(maxWidth / width, maxHeight / height);
            width = Math.round(width * ratio);
            height = Math.round(height * ratio);
          }

          const canvas = document.createElement("canvas");
          canvas.width = width;
          canvas.height = height;
          const ctx = canvas.getContext("2d");
          ctx.drawImage(img, 0, 0, width, height);

          canvas.toBlob(blob => {
            if (!blob) return reject("Canvas resize failed");
            resolve(new File([blob], file.name, { type: "image/jpeg" }));
          }, "image/jpeg", 0.9); // quality 90%
        };
        img.src = e.target.result;
      };
      reader.onerror = err => reject(err);
      reader.readAsDataURL(file);
    });
  }
</script>



  <?php if (!empty($base64)): ?>
    <div class="preview">
      <h2>Your Photo Strip 💕</h2>
      <img src="<?= $base64 ?>" alt="Photo Strip Preview">
      <br><br>
      <a href="<?= $base64 ?>" download="photobooth_strip.png">
        <button>⬇️ Download Photo Strip</button>
      </a>
    </div>
  <?php endif; ?>
</body>
</html>
