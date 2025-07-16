<?php
session_start();
include 'menu.php';
include 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit;
}

// Handle admin tools
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $target = $_POST['target'] ?? '';

    switch ($action) {
        case 'delete_post':
            $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
            $stmt->execute([$target]);
            $msg = "✅ Post ID $target deleted.";
            break;

        case 'promote_user':
            $stmt = $pdo->prepare("UPDATE users SET is_admin = 1 WHERE username = ?");
            $stmt->execute([$target]);
            $msg = "✅ Promoted $target to admin.";
            break;

        case 'ratify_user':
            $stmt = $pdo->prepare("UPDATE users SET username = CONCAT('🐀', username) WHERE username = ?");
            $stmt->execute([$target]);
            $msg = "🐀 Ratified $target.";
            break;

        case 'wipe_signature':
            $stmt = $pdo->prepare("UPDATE users SET signature = '' WHERE username = ?");
            $stmt->execute([$target]);
            $msg = "🧻 Signature wiped for $target.";
            break;

        case 'cheese_click':
            $_SESSION['cheese_count'] = ($_SESSION['cheese_count'] ?? 0) + 1;
            $msg = "🧀 Cheese clicked {$_SESSION['cheese_count']} times!";
            break;
    }
}

// Fetch display data
$bios = $pdo->query("SELECT username, bio FROM users WHERE bio != ''")->fetchAll();
$sigs = $pdo->query("SELECT username, signature FROM users WHERE signature != ''")->fetchAll();
$posts = $pdo->query("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC LIMIT 10")->fetchAll();
$benches = $pdo->query("SELECT benchmark_title FROM benchmarks ORDER BY created_at DESC LIMIT 10")->fetchAll();
?>

<h2>🛡️ Admin Panel</h2>
<div style="max-width:900px;margin: 20px auto; background:white;padding:20px;border-radius:8px;">
    <p style="text-align:right;"><a href="admin.php?logout=1">🚪 Logout</a></p>
    <?php if (!empty($msg)) echo "<p style='color:green;'>$msg</p>"; ?>

    <h3>Admin Tools</h3>
    <form method="post" style="margin-bottom:10px;">
        <input type="hidden" name="action" value="delete_post">
        Post ID to delete: <input type="number" name="target" required>
        <button type="submit">🧹 Delete Post</button>
    </form>

    <form method="post" style="margin-bottom:10px;">
        <input type="hidden" name="action" value="promote_user">
        Username to promote: <input type="text" name="target" required>
        <button type="submit">⚙️ Promote to Admin</button>
    </form>

    <form method="post" style="margin-bottom:10px;">
        <input type="hidden" name="action" value="ratify_user">
        Username to ratify: <input type="text" name="target" required>
        <button type="submit">🐀 Ratify User</button>
    </form>

    <form method="post" style="margin-bottom:10px;">
        <input type="hidden" name="action" value="wipe_signature">
        Username to wipe sig: <input type="text" name="target" required>
        <button type="submit">🧻 Wipe Signature</button>
    </form>

    <form method="post" style="margin-bottom:10px;">
        <input type="hidden" name="action" value="cheese_click">
        <button type="submit">🧀 Cheese Button</button>
    </form>

    <hr>

    <h3>Latest User Bios</h3>
    <ul><?php foreach ($bios as $b): ?><li><strong><?= htmlspecialchars($b['username']) ?>:</strong> <?= $b['bio'] ?></li><?php endforeach; ?></ul>

    <h3>Latest Signatures</h3>
    <ul><?php foreach ($sigs as $s): ?><li><strong><?= htmlspecialchars($s['username']) ?>:</strong> <?= $s['signature'] ?></li><?php endforeach; ?></ul>

    <h3>Latest Posts</h3>
    <ul><?php foreach ($posts as $p): ?><li><strong><?= htmlspecialchars($p['username']) ?>:</strong> <?= $p['content'] ?></li><?php endforeach; ?></ul>

    <h3>Latest Benchmark Titles</h3>
    <ul><?php foreach ($benches as $b): ?><li><?= $b['benchmark_title'] ?></li><?php endforeach; ?></ul>
</div>
