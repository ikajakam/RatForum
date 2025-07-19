<?php
session_start();
include 'db.php';
include 'menu.php';

// Check if thread ID is present
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p style='color:red;'>❌ Invalid thread ID.</p>";
    exit;
}

$thread_id = (int)$_GET['id'];

// Fetch the main post
$stmt = $pdo->prepare("
    SELECT posts.*, users.username 
    FROM posts 
    JOIN users ON posts.user_id = users.id 
    WHERE posts.id = ? AND parent_id IS NULL
");
$stmt->execute([$thread_id]);
$thread = $stmt->fetch();

if (!$thread) {
    echo "<p style='color:red;'>❌ Thread not found.</p>";
    exit;
}

// Fetch replies
$replyStmt = $pdo->prepare("
    SELECT posts.*, users.username 
    FROM posts 
    JOIN users ON posts.user_id = users.id 
    WHERE parent_id = ? 
    ORDER BY created_at ASC
");
$replyStmt->execute([$thread_id]);
$replies = $replyStmt->fetchAll();
?>

<div style="max-width:800px;margin: 20px auto;">
    <div style="padding: 15px; background: white; border-radius: 8px;">
        <h2><?= htmlspecialchars($thread['title']) ?></h2>
        <p><?= nl2br(htmlspecialchars($thread['content'])) ?></p>
        <small>Posted by <strong><?= htmlspecialchars($thread['username']) ?></strong> on <?= $thread['created_at'] ?></small>
    </div>

    <h3 style="margin-top:30px;">💬 Replies</h3>
    <?php if (count($replies) === 0): ?>
        <p>No replies yet. Be the first!</p>
    <?php else: ?>
<?php foreach ($replies as $reply): ?>
    <div style="margin-top: 10px; padding: 10px; background: #f9f9f9; border-left: 4px solid #ccc;">
        <p><?= nl2br(htmlspecialchars($reply['content'])) ?></p>
        <small>
            By <?= htmlspecialchars($reply['username']) ?> at <?= $reply['created_at'] ?>
            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $reply['user_id']): ?>
                | <a href="edit_comment.php?id=<?= $reply['id'] ?>">✏️ Edit</a>
            <?php endif; ?>
        </small>
    </div>
<?php endforeach; ?>

    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <h3 style="margin-top:30px;">➕ Add a reply</h3>
        <form method="post">
            <textarea name="content" rows="5" required></textarea><br>
            <button type="submit">Post Reply</button>
        </form>
    <?php else: ?>
        <p style="margin-top:30px;">🔐 <a href="login.php">Log in</a> to reply.</p>
    <?php endif; ?>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $content = trim($_POST['content']);
    if ($content) {
        $stmt = $pdo->prepare("INSERT INTO posts (user_id, content, parent_id) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $content, $thread_id]);
        header("Location: thread.php?id=$thread_id");
        exit;
    }
}
?>
