<?php
session_start();
define('SITE_LOADED', true);
require_once 'models/Book.php';

$book = new Book();
$allBooks = $book->read();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/books.css">
    <style>
        /* Force hide any duplicate navigation */
        .mobile-menu:not(:first-of-type),
        .sidebar:not(:first-of-type) {
            display: none !important;
        }
    </style>
    <title>Book Spectrum - Books Collection</title>
</head>

<body>
    <div class="container">
        <?php include 'includes/sidebar.php'; ?>
        
        <div class="main-content">
            <header>
                <button id="menu-toggle" class="mobile-only-button" aria-label="Toggle menu">
                    <i class="fa-solid fa-bars"></i> ☰
                </button>
                
                <div class="search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Search Your Favourite Books ..." id="searchInput">
                </div>
                
                <div class="user-actions">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="profile.php" class="profile-link">
                            <img src="<?php echo isset($_SESSION['profile_pic']) ? $_SESSION['profile_pic'] : './images/blue-circle-with-white-user_78370-4707.avif'; ?>" 
                                 alt="Profile" 
                                 class="profile-image">
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-secondary">
                            <i class="fa-solid fa-sign-in-alt"></i> Login
                        </a>
                        <a href="register.php" class="btn btn-primary">
                            <i class="fa-solid fa-user-plus"></i> Register
                        </a>
                    <?php endif; ?>
                </div>
            </header>
            
            <div class="section">
                <div class="heading">
                    <span>Books Collection</span>
                </div>
                
                <div class="book-list">
                    <?php while($row = $allBooks->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="book-card" data-id="<?php echo $row['id']; ?>">
                            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($row['title']); ?>" 
                                 class="book-image">
                            <div class="book-title"><?php echo htmlspecialchars($row['title']); ?></div>
                            <div class="book-author"><?php echo htmlspecialchars($row['author']); ?></div>
                            <div class="book-category"><?php echo htmlspecialchars($row['category']); ?></div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="popup-overlay" id="bookPopup">
        <div class="book-popup">
            <span class="popup-close" id="closePopupBtn">&times;</span>
            <div class="popup-header">
                <div class="popup-category" id="popupCategory">Loading...</div>
                <div class="popup-title" id="popupTitle">Loading...</div>
                <div class="popup-author" id="popupAuthor">Loading...</div>
                <div class="popup-meta">
                    <span id="popupYear"><i class="fa-solid fa-calendar-days"></i> Loading...</span>
                    <span id="popupPages"><i class="fa-solid fa-file-lines"></i> Loading...</span>
                    <span id="popupRating"><i class="fa-solid fa-star"></i> Loading...</span>
                </div>
            </div>
            <img src="images/loading-book.jpg" alt="Book Cover" class="popup-cover" id="popupCover">
            <div class="popup-description" id="popupDescription">
                Loading book details...
            </div>
            <div class="popup-actions">
                <button class="btn btn-primary" id="rentBtn">
                    <i class="fa-solid fa-spinner fa-spin"></i> Loading...
                </button>
            </div>
        </div>
    </div>
    
    <script src="https://kit.fontawesome.com/17218a83e4.js" crossorigin="anonymous"></script>
    <script src="./js/script.js"></script>
    <script src="./js/books.js"></script>
    <script src="./js/responsive.js"></script>
</body>

</html>
