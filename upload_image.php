<?php
session_start();
include 'db.php';
include 'menu.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>🔐 Please <a href='login.php'>log in</a> to upload a system image.</p>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle image fetch
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = trim($_POST['url'] ?? '');

    if (filter_var($url, FILTER_VALIDATE_URL)) {
        // Vulnerable: fetch the image without any domain or protocol filtering
        $imageData = @file_get_contents($url);

        if ($imageData) {
            $imagePath = 'uploads/' . uniqid() . '.jpg';
            file_put_contents($imagePath, $imageData);

            $stmt = $pdo->prepare("UPDATE users SET showcase_image = ? WHERE id = ?");
            $stmt->execute([$imagePath, $user_id]);

            echo "<p style='color:green;'>✅ Image fetched and stored!</p>";
        } else {
            echo "<p style='color:red;'>❌ Failed to fetch image from URL.</p>";
        }
    } else {
        echo "<p style='color:red;'>❌ Invalid URL.</p>";
    }
}

// Fetch current image
$stmt = $pdo->prepare("SELECT showcase_image FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$row = $stmt->fetch();
?>

<h2>Upload Your System Image</h2>
<form method="post">
    Image URL: <input type="text" name="url" required style="width: 100%;"><br><br>
    <button type="submit">Fetch and Save</button>
</form>

<?php if (!empty($row['showcase_image'])): ?>
    <h3>Your Current Image:</h3>
    <img src="<?= htmlspecialchars($row['showcase_image']) ?>" style="max-width:400px;">
<?php endif; ?>
