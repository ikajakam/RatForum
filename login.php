<?php include 'db.php'; ?>
<?php include 'menu.php'; ?>

<h2>Login</h2>
<form method="post">
    Username or Email: <input type="text" name="user" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
</form>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['user']);
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$user, $user]);
    $row = $stmt->fetch();

    if ($row && password_verify($pass, $row['password_hash'])) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['is_admin'] = $row['is_admin'];
        echo "<p>✅ Welcome, " . htmlspecialchars($row['username']) . "! <a href='index.php'>Go to forum</a></p>";
    } else {
        echo "<p style='color:red;'>❌ Invalid credentials</p>";
    }
}
?>
