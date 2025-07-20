<?php
session_start();
include 'db.php';
include 'menu.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>🔐 Please <a href='login.php'>log in</a> to update your showcase.</p>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = ['cpu', 'gpu', 'ram', 'storage', 'cooling', 'psu'];
    $updates = [];

    foreach ($fields as $field) {
        $updates["showcase_$field"] = trim($_POST[$field] ?? '');
    }

    $stmt = $pdo->prepare("
        UPDATE users SET
            showcase_cpu = ?, showcase_gpu = ?, showcase_ram = ?, 
            showcase_storage = ?, showcase_cooling = ?, showcase_psu = ?
        WHERE id = ?
    ");
    $stmt->execute([
        $updates['showcase_cpu'], $updates['showcase_gpu'], $updates['showcase_ram'],
        $updates['showcase_storage'], $updates['showcase_cooling'], $updates['showcase_psu'],
        $user_id
    ]);

    echo "<p style='color:green;'>✅ Showcase updated!</p>";
}

// Fetch current data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<h2>Edit Your PC Showcase</h2>
<form method="post">
    <p>CPU: <?= htmlspecialchars($user['cpu'] ?? '') ?></p>
    <p>GPU: <?= htmlspecialchars($user['gpu'] ?? '') ?></p>
    <p>RAM: <?= htmlspecialchars($user['ram'] ?? '') ?></p>
    <p>Storage: <?= htmlspecialchars($user['storage'] ?? '') ?></p>
    <p>Cooling: <?= htmlspecialchars($user['cooling'] ?? '') ?></p>
    <p>PSU: <?= htmlspecialchars($user['psu'] ?? '') ?></p>

    <button type="submit">Update Showcase</button>
</form>

<p style="margin-top:20px;">
    🖼️ Want to show off a picture of your setup? 
    <a href="upload_image.php">Upload a system image</a>
</p>

