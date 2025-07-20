<?php
session_start();
include 'db.php';
include 'menu.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>🔐 Please <a href='login.php'>log in</a> to view your profile.</p>";
    exit;
}

$view_id = $_GET['id'] ?? $_SESSION['user_id'];


$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$view_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "<p style='color:red;'>❌ User not found.</p>";
    exit;
}
?>

<h2>Your Profile</h2>
<div style="max-width:600px;margin:20px auto;background:#fff;padding:20px;border-radius:8px;">
    <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Bio:</strong><br> <?= nl2br(htmlspecialchars($user['bio'] ?? '')) ?></p>
    <p><strong>Signature:</strong><br> <?= nl2br(htmlspecialchars($user['signature'] ?? '')) ?></p>


    <h3>Your PC Showcase</h3>
    <ul>
        <li><strong>CPU:</strong> <?= htmlspecialchars($user['showcase_cpu'] ?? '') ?></li>
        <li><strong>GPU:</strong> <?= htmlspecialchars($user['showcase_gpu'] ?? '') ?></li>
        <li><strong>RAM:</strong> <?= htmlspecialchars($user['showcase_ram'] ?? '') ?></li>
        <li><strong>Storage:</strong> <?= htmlspecialchars($user['showcase_storage'] ?? '') ?></li>
        <li><strong>Cooling:</strong> <?= htmlspecialchars($user['showcase_cooling'] ?? '') ?></li>
        <li><strong>PSU:</strong> <?= htmlspecialchars($user['showcase_psu'] ?? '') ?></li>

        <?php if (!empty($user['showcase_image'])): ?>
    <h3>Your System Image</h3>
    <img src="<?= htmlspecialchars($user['showcase_image']) ?>" style="max-width:400px;">
<?php endif; ?>

    </ul>
</div>
