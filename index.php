<?php
session_start();
include 'db.php';
include 'menu.php';

// Pagination setup
$postsPerPage = 5;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $postsPerPage;

// Fetch top-level threads (parent_id IS NULL)
$stmt = $pdo->prepare("
    SELECT posts.*, users.username 
    FROM posts 
    JOIN users ON posts.user_id = users.id 
    WHERE parent_id IS NULL 
    ORDER BY created_at DESC 
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $postsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$threads = $stmt->fetchAll();

// Get total post count for pagination
$totalStmt = $pdo->query("SELECT COUNT(*) FROM posts WHERE parent_id IS NULL");
$totalPosts = $totalStmt->fetchColumn();
$totalPages = ceil($totalPosts / $postsPerPage);
?>

<h2>RatForum Message Board</h2>
<div style="max-width:800px;margin: 20px auto;">
    <?php foreach ($threads as $thread): ?>
        <div style="padding: 15px; background: white; margin-bottom: 15px; border-radius: 8px;">
            <h3><?= htmlspecialchars($thread['title']) ?></h3>
            <p><?= nl2br(htmlspecialchars($thread['content'])) ?></p>
<small>
    Posted by <strong><?= htmlspecialchars($thread['username']) ?></strong>
    on <?= $thread['created_at'] ?>
</small><br>

<?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $thread['user_id']): ?>
    <a href="edit_post.php?id=<?= $thread['id'] ?>">✏️ Edit</a> |
<?php endif; ?>

            <a href="thread.php?id=<?= $thread['id'] ?>">💬 View Replies / Reply</a>
        </div>
    <?php endforeach; ?>

    <!-- Pagination -->
    <div style="text-align: center; margin-top: 20px;">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" 
               style="margin: 0 5px; <?= $i === $page ? 'font-weight:bold;' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
</div>
<!--
    Dev note: admin login for testing
    username: admin
    password: ratAdmin!23
-->