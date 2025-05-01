<?php
if (!defined('SITE_LOADED')) die("Direct access not allowed");

include_once 'models/User.php';

$currentPage = basename($_SERVER['PHP_SELF']);

$userLoggedIn = false;
if (isset($_SESSION['user_id'])) {
    $userLoggedIn = true;
    $user = new User();
    $userInfo = $user->getUserById($_SESSION['user_id']);
}
?>

<div class="sidebar">
    <div class="sidebar-header">
        <h1>Book Spectrum</h1>
        <button id="close-menu" class="close-menu">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li><a href="index.php" <?php echo ($currentPage == 'index.php') ? 'class="active"' : ''; ?>>Home</a></li>
            <li><a href="books.php" <?php echo ($currentPage == 'books.php') ? 'class="active"' : ''; ?>>Books</a></li>
            <?php if ($userLoggedIn): ?>
                <li><a href="profile.php" <?php echo ($currentPage == 'profile.php') ? 'class="active"' : ''; ?>>Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php" <?php echo ($currentPage == 'login.php') ? 'class="active"' : ''; ?>>Login</a></li>
                <li><a href="register.php" <?php echo ($currentPage == 'register.php') ? 'class="active"' : ''; ?>>Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</div> 