<?php
session_start();
include 'db.php';
include 'menu.php';

// Require login
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>🔐 You must be <a href='login.php'>logged in</a> to post a new thread.</p>";
    exit;
}
?>

<h2>Create a New Thread</h2>
<form method="post">
    Title: <input type="text" name="title" maxlength="100" required><br><br>
    Content:<br>
    <textarea name="content" rows="8" required></textarea><br><br>
    <button type="submit">Post Thread</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'];

    if ($title && $content) {
        $stmt = $pdo->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $title, $content]);
        $new_id = $pdo->lastInsertId();
        header("Location: thread.php?id=$new_id");
        exit;
    } else {
        echo "<p style='color:red;'>❌ Title and content cannot be empty.</p>";
    }
}
?>
