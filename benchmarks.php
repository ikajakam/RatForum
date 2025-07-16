<?php
session_start();
include 'db.php';
include 'menu.php';

// Handle benchmark form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $title = trim($_POST['title']);
    $cpu   = (int)$_POST['cpu'];
    $gpu   = (int)$_POST['gpu'];
    $fps   = (int)$_POST['fps'];

    if ($title && $cpu && $gpu && $fps) {
        $stmt = $pdo->prepare("
            INSERT INTO benchmarks (user_id, benchmark_title, cpu_score, gpu_score, fps_score)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$user_id, $title, $cpu, $gpu, $fps]);
        echo "<p style='color:green;'>✅ Benchmark submitted!</p>";
    } else {
        echo "<p style='color:red;'>❌ All fields are required and must be valid numbers.</p>";
    }
}

// Fetch all benchmarks (no pagination for now)
$stmt = $pdo->query("
    SELECT benchmarks.*, users.username 
    FROM benchmarks 
    JOIN users ON benchmarks.user_id = users.id 
    ORDER BY benchmarks.created_at DESC
");
$benchmarks = $stmt->fetchAll();
?>

<h2>System Benchmarks</h2>

<?php if (isset($_SESSION['user_id'])): ?>
    <form method="post" style="margin-bottom:30px;">
        Benchmark Title (appears as table header): 
        <input type="text" name="title" required><br><br>
        CPU Score: <input type="number" name="cpu" required><br><br>
        GPU Score: <input type="number" name="gpu" required><br><br>
        FPS Score: <input type="number" name="fps" required><br><br>
        <button type="submit">Submit Benchmark</button>
    </form>
<?php else: ?>
    <p>🔐 <a href="login.php">Log in</a> to submit a benchmark.</p>
<?php endif; ?>

<table border="1" cellpadding="10" style="background:white; border-collapse:collapse; max-width:800px; margin:auto;">
    <thead>
        <tr>
            <!-- Deliberate stored XSS: no escaping on title -->
            <th><?= $benchmarks[0]['benchmark_title'] ?? 'Benchmark Title' ?></th>
            <th>CPU Score</th>
            <th>GPU Score</th>
            <th>FPS Score</th>
            <th>User</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($benchmarks as $b): ?>
            <tr>
                <td><?= htmlspecialchars($b['benchmark_title']) ?></td>
                <td><?= (int)$b['cpu_score'] ?></td>
                <td><?= (int)$b['gpu_score'] ?></td>
                <td><?= (int)$b['fps_score'] ?></td>
                <td><?= htmlspecialchars($b['username']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
