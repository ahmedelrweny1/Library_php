<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();
define('SITE_LOADED', true);
require_once 'models/Book.php';

$book = new Book();

$recommendedBooks = $book->getRandomBooks(4);
$latestBooks = $book->getLatestBooks(4);
$featuredBook = $book->getRandomBooks(1)->fetch(PDO::FETCH_ASSOC);
$loggedIn = isset($_SESSION['user_id']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/index.css">
    <style>
        .mobile-menu:not(:first-of-type),
        .sidebar:not(:first-of-type) {
            display: none !important;
        }
    </style>
    <title>Book Spectrum</title>
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
                    <?php if($loggedIn): ?>
                        <a href="./profile.php" class="profile-link">
                            <img src="./images/blue-circle-with-white-user_78370-4707.avif" alt="profile" width="40" height="40">
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
            
            <section class="home">
                <div class="section recommended">
                    <div class="heading">
                        <span>Recommended</span>
                        <a href="./books.php">See All <i class="fa-solid fa-angles-right"></i></a>
                    </div>
                    <div class="books">
                        <?php while ($row = $recommendedBooks->fetch(PDO::FETCH_ASSOC)): ?>
                            <div class="book" data-id="<?php echo $row['id']; ?>">
                                <img src="<?php echo htmlspecialchars($row['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($row['title']); ?>" 
                                     class="book-image">
                                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                                <p><?php echo htmlspecialchars($row['author']); ?></p>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                
                <div class="section categories">
                    <div class="heading">
                        <span>Latest Books</span>
                        <a href="./books.php">See All <i class="fa-solid fa-angles-right"></i></a>
                    </div>
                    <div class="books">
                        <?php while ($row = $latestBooks->fetch(PDO::FETCH_ASSOC)): ?>
                            <div class="book" data-id="<?php echo $row['id']; ?>">
                                <img src="<?php echo htmlspecialchars($row['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($row['title']); ?>" 
                                     class="book-image">
                                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                                <p><?php echo htmlspecialchars($row['author']); ?></p>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>

            <div class="book-details-modal" id="bookDetailsModal">
                <div class="modal-backdrop"></div>
                <div class="modal-content" data-book-id="<?php echo $featuredBook['id']; ?>">
                    <div class="modal-header">
                        <h3 id="detail-title"><?php echo htmlspecialchars($featuredBook['title']); ?></h3>
                        <button class="close-modal">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="book-cover">
                            <img src="<?php echo htmlspecialchars($featuredBook['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($featuredBook['title']); ?>" 
                                 id="detail-img">
                        </div>
                        <div class="book-info">
                            <p class="author" id="detail-author">
                                <i class="fa-solid fa-user-pen"></i> 
                                <?php echo htmlspecialchars($featuredBook['author']); ?>
                            </p>
                            
                            <div class="description">
                                <h4>Description</h4>
                                <p id="detail-description">
                                    <?php echo htmlspecialchars($featuredBook['description']); ?>
                                </p>
                            </div>
                            
                            <div class="book-meta">
                                <?php if($featuredBook['published_year']): ?>
                                    <p class="detail-year">
                                        <i class="fa-solid fa-calendar-days"></i>
                                        <strong>Published:</strong> <?php echo htmlspecialchars($featuredBook['published_year']); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <?php if($featuredBook['isbn']): ?>
                                    <p class="detail-isbn">
                                        <i class="fa-solid fa-barcode"></i>
                                        <strong>ISBN:</strong> <?php echo htmlspecialchars($featuredBook['isbn']); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="rent-button" class="btn btn-primary" <?php echo !$loggedIn ? 'data-requires-login="true"' : ''; ?>>
                            <?php if(!$loggedIn): ?>
                                Login to Rent
                            <?php else: ?>
                                Rent This Book
                            <?php endif; ?>
                            <i class="fa-solid fa-bookmark"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://kit.fontawesome.com/17218a83e4.js" crossorigin="anonymous"></script>
    <script src="./js/script.js"></script>
    <script src="./js/index.js"></script>
    <script src="./js/responsive.js"></script>
</body>

</html>