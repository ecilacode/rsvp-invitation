<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../db_connect.php';
require '../libs/phpqrcode/phpqrcode.php';


// Generate random code 
function generateCode($length = 6) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $length);
}

// --- Handle Add Guest ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] === "add") {
    if (!empty($_POST["name"])) {
        $name = trim($_POST["name"]);
        $code = generateCode();

        $stmt = $pdo->prepare("INSERT INTO guests (name, code, rsvp_status, rsvp_at) VALUES (:name, :code, 'pending', NULL)");
        $stmt->execute([":name" => $name, ":code" => $code]);

        header("Location: " . $_SERVER['PHP_SELF'] . "?success=add&name=" . urlencode($name));
        exit;
    }
}

// --- Handle Delete Guest ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] === "delete") {
    if (!empty($_POST["id"])) {
        $id = (int) $_POST["id"];
        $stmt = $pdo->prepare("DELETE FROM guests WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: " . $_SERVER['PHP_SELF'] . "?success=delete");
        exit;
    }
}

// QR Code generation function
function generateQRCode($code, $name) {
    $url = "http://localhost/rsvp_invitation/index.php?code=" . urlencode($code);
    //uncomment to prod
    //$url = "https://jaehannesglamparty.greatsite-net/index.php?code=" . urlencode($code);

    
    // Sanitize the name so no spaces or special chars break the filename
    $safeName = preg_replace('/[^A-Za-z0-9_-]/', '_', $name);

    // Set header for download
    header('Content-Type: image/png');
    header('Content-Disposition: attachment; filename="qr_' . $safeName . '_' . $code . '.png"');

    // Output QR Code image
    QRcode::png($url, null, QR_ECLEVEL_L, size: 10);
    exit;
}


// Handle QR Code download
if (isset($_GET['qr'])) {
    $code = $_GET['qr'];
    $name = $_GET['name'];
    generateQRCode($code, $name);
}

// Fetch all guests
$stmt = $pdo->query("SELECT * FROM guests ORDER BY id DESC");
$guests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Management</title>
    <style>
        body { font-family: Arial, sans-serif; background: #fafafa; text-align:center; }
        .container { max-width: 700px; margin: 30px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);}
        input[type=text] { padding: 8px; width: 60%; border: 1px solid #ccc; border-radius: 5px; }
        button { padding: 8px 12px; border: none; border-radius: 5px; cursor: pointer; }
        .add { background: #ff66a3; color: white; }
        .delete { background: #ff3333; color: white; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Guest Management</h2>

        <?php if (isset($_GET['success'])): ?>
            <?php if ($_GET['success'] === 'add'): ?>
                <p style="color:green;">Guest <?= htmlspecialchars($_GET['name']) ?> added successfully!</p>
            <?php elseif ($_GET['success'] === 'delete'): ?>
                <p style="color:red;">Guest deleted successfully.</p>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Add Guest Form -->
        <form method="POST" action="">
            <input type="hidden" name="action" value="add">
            <input type="text" name="name" placeholder="Enter guest name" required>
            <button type="submit" class="add">Add Guest</button>
        </form>

        <!-- Guest List Table -->
        <h3>Current Guest List</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Code</th>
                <th>Status</th>
                <th>Action</th>
                <th>Preview</th>
                
            </tr>
            <?php foreach ($guests as $guest): ?>
            <tr>
                <td><?= $guest['id'] ?></td>
                <td><?= htmlspecialchars($guest['name']) ?></td>
                <td><?= htmlspecialchars($guest['code']) ?></td>
                <td><?= ucfirst($guest['rsvp_status']) ?></td>
                
                <td>
                    <a href="admin.php?qr=<?= urlencode($guest['code']) ?>&name=<?= urlencode($guest['name']) ?>" style="padding:5px 10px; background:#28a745; color:white; text-decoration:none; border-radius:5px;">Download QR</a>
                
                    <form method="POST" action="" onsubmit="return confirm('Delete this guest?');" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $guest['id'] ?>">
                        <button type="submit" class="delete">Delete</button>
                    </form>
                </td>

                <td>
                    <img src="../qr.php?code=<?= urlencode($guest['code']) ?>" alt="QR Code" style="width:80px; height:80px;">
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
