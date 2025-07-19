<?php
session_start();
include 'db.php';
include 'menu.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>🔐 Please <a href='login.php'>log in</a> to edit comments.</p>";
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p style='color:red;'>❌ Invalid comment ID.</p>";
    exit;
}

$comment_id = (int)$_GET['id'];

// Fetch the comment
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND parent_id IS NOT NULL");
$stmt->execute([$comment_id]);
$comment = $stmt->fetch();

if (!$comment) {
    echo "<p style='color:red;'>❌ Comment not found or it's not a reply.</p>";
    exit;
}

// Only allow editing by the owner
if ($comment['user_id'] != $_SESSION['user_id']) {
    echo "<p style='color:red;'>⛔ You are not allowed to edit this comment.</p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['content']);

    $stmt = $pdo->prepare("UPDATE posts SET content = ? WHERE id = ?");
    $stmt->execute([$content, $comment_id]);

    // Redirect back to the thread
    $parent_id = $comment['parent_id'];
    echo "<p style='color:green;'>✅ Comment updated! <a href='thread.php?id=$parent_id'>Back to thread</a></p>";
    exit;
}
?>

<h2>Edit Your Comment</h2>
<form method="post">
    Content:<br>
    <textarea name="content" rows="6"><?= htmlspecialchars($comment['content']) ?></textarea><br><br>
    <button type="submit">Save Changes</button>
</form>
