<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<link rel="stylesheet" href="style.css">

<div class="menu">
    <img src="logo.png" alt="RatForum">
    <span>RatForum</span>
    <nav>
        <a href="index.php">Home</a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="new_thread.php">New Thread</a>
            <a href="profile.php">Profile</a>
            <a href="showcase.php">System Showcase</a>
            <a href="benchmarks.php">Benchmarks</a>
                        <a href="search.php">Search</a>
                        <a href="users.php">User directory</a>


            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="register.php">Register</a>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </nav>
</div>
