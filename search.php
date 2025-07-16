<?php
session_start();
include 'db.php';
include 'menu.php';

$q = trim($_GET['q'] ?? '');
$results = [];

if ($q !== '') {
    $stmt = $pdo->prepare("
        SELECT posts.*, users.username 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        WHERE content LIKE ? OR title LIKE ?
        ORDER BY created_at DESC
    ");
    $stmt->execute(["%$q%", "%$q%"]);
    $results = $stmt->fetchAll();
}
?>

<h2>🔍 Search Posts</h2>
<form method="get" style="text-align:center;margin-bottom:20px;">
    <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Enter keyword..." style="width:300px;">
    <button type="submit">Search</button>
</form>

<?php if ($q !== ''): ?>
    <div style="max-width:800px;margin:auto;">
        <h3>Results for "<?= htmlspecialchars($q) ?>"</h3>
        <?php if (count($results) === 0): ?>
            <p>No results found.</p>
        <?php else: ?>
            <?php foreach ($results as $r): ?>
                <div style="background:white; padding:15px; margin-bottom:15px; border-radius:8px;">
                    <h4><?= htmlspecialchars($r['title']) ?></h4>
                    <p><?= nl2br(htmlspecialchars($r['content'])) ?></p>
                    <small>By <?= htmlspecialchars($r['username']) ?> on <?= $r['created_at'] ?></small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
<?php endif; ?>
