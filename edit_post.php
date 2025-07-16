<?php
session_start();
include 'db.php';
include 'menu.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>🔐 Please <a href='login.php'>log in</a> to edit posts.</p>";
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p style='color:red;'>❌ Invalid post ID.</p>";
    exit;
}

// Handle form submit — deliberately missing CSRF token
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
    $stmt->execute([$title, $content, $post_id]);
    echo "<p style='color:green;'>✅ Post updated! <a href='thread.php?id=$post_id'>View thread</a></p>";
}
?>

<h2>Edit Your Post</h2>
<form method="post">
    Title: <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>"><br><br>
    Content:<br>
    <textarea name="content" rows="8"><?= htmlspecialchars($post['content']) ?></textarea><br><br>
    <button type="submit">Save Changes</button>
</form>
