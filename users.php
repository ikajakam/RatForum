<?php
session_start();
include 'db.php';
include 'menu.php';

$allowed_sorts = ['username', 'id'];
$sort = in_array($_GET['sort'] ?? '', $allowed_sorts) ? $_GET['sort'] : 'id';

$stmt = $pdo->prepare("SELECT id, username FROM users ORDER BY $sort ASC");
$stmt->execute();
$users = $stmt->fetchAll();
?>

<h2>🧑 User Directory</h2>
<p style="text-align:center;">
    Sort by: 
    <a href="?sort=id">Join Order</a> | 
    <a href="?sort=username">Username</a>
</p>

<ul style="max-width:600px;margin:20px auto; background:white;padding:20px;border-radius:8px;">
    <?php foreach ($users as $u): ?>
        <li>
            <a href="profile.php?id=<?= $u['id'] ?>">
                <?= htmlspecialchars($u['username']) ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
