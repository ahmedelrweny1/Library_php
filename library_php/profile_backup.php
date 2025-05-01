<?php
session_start();
require_once 'models/User.php';
require_once 'models/RentedBook.php';
require_once 'includes/session.php';

requireLogin();

$user = new User();
$user_data = $user->getUserById($_SESSION['user_id']);
$rentedBook = new RentedBook();
$rentedBooks = $rentedBook->getUserRentedBooks($_SESSION['user_id']);

if(isset($_GET['return']) && is_numeric($_GET['return'])) {
    $rental_id = intval($_GET['return']);
    if($rentedBook->returnBook($rental_id)) {
        $_SESSION['message'] = "Book returned successfully.";
    } else {
        $_SESSION['message'] = "Failed to return book.";
    }
    header("Location: profile.php");
    exit();
}

$activeBooks = 0;
$returnedBooks = 0;
$allBooks = [];

if($rentedBooks->rowCount() > 0) {
    while($row = $rentedBooks->fetch(PDO::FETCH_ASSOC)) {
        $allBooks[] = $row;
        if($row['return_date']) {
            $returnedBooks++;
        } else {
            $activeBooks++;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/sidebar.css">
    <link rel="stylesheet" href="./css/profile.css">
    <style>
        .mobile-menu:not(:first-of-type),
        .sidebar:not(:first-of-type) {
            display: none !important;
        }
    </style>
    <title>Book Spectrum - Profile</title>
</head>

<body>
    <div class="container">
        <?php include 'includes/sidebar.php'; ?>
        
        <div class="main-content">
            <header>
                <button id="menu-toggle" aria-label="Toggle menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                
                <?php if(isset($_SESSION['message'])): ?>
                <div class="message">
                    <?php 
                        echo htmlspecialchars($_SESSION['message']); 
                        unset($_SESSION['message']); 
                    ?>
                </div>
                <?php endif; ?>
            </header>
            
            <section class="section info">
                <div class="user-profile">
                    <div class="image">
                        <img src="./images/blue-circle-with-white-user_78370-4707.avif" alt="user">
                    </div>
                    <div class="about">
                        <h3><strong>Username:</strong> <?php echo htmlspecialchars($user_data['username']); ?></h3>
                        <h3><strong>Email:</strong> <?php echo htmlspecialchars($user_data['email']); ?></h3>
                        <h3><strong>Joined:</strong> <?php echo date('d M Y', strtotime($user_data['created_at'])); ?></h3>
                        
                        <div class="user-stats">
                            <div class="stat">
                                <span class="count"><?php echo count($allBooks); ?></span>
                                <span class="label">Total Books</span>
                            </div>
                            <div class="stat">
                                <span class="count"><?php echo $activeBooks; ?></span>
                                <span class="label">Currently Reading</span>
                            </div>
                            <div class="stat">
                                <span class="count"><?php echo $returnedBooks; ?></span>
                                <span class="label">Completed</span>
                            </div>
                        </div>
                    </div>
                    <a href="logout.php" class="btn btn-danger">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </a>
                </div>
            </section>
            
            <section class="section rented">
                <div class="heading">
                    <span>My Library</span>
                    <a href="./books.php">Explore More Books <i class="fa-solid fa-angles-right"></i></a>
                </div>
                <div class="books">
                    <?php
                    if(!empty($allBooks)):
                        foreach($allBooks as $row):
                    ?>
                    <div class="book">
                        <div class="book-cover">
                            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" 
                                alt="<?php echo htmlspecialchars($row['title']); ?>" 
                                class="book-image">
                            <?php if($row['return_date']): ?>
                                <div class="status completed">Completed</div>
                            <?php else: ?>
                                <div class="status reading">Reading</div>
                            <?php endif; ?>
                        </div>
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p><?php echo htmlspecialchars($row['author']); ?></p>
                        <div class="book-details">
                            <span class="rental-date">Rented: <?php echo date('d M Y', strtotime($row['borrow_date'])); ?></span>
                            <?php if($row['return_date']): ?>
                                <span class="returned">Returned: <?php echo date('d M Y', strtotime($row['return_date'])); ?></span>
                            <?php else: ?>
                                <a href="profile.php?return=<?php echo $row['rent_id']; ?>" class="btn btn-primary">
                                    <i class="fa-solid fa-rotate-left"></i> Return Book
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php 
                        endforeach;
                    else:
                    ?>
                    <div class="no-books">
                        <i class="fa-solid fa-book-open fa-3x"></i>
                        <p>You haven't rented any books yet.</p>
                        <a href="./books.php" class="btn btn-primary">
                            <i class="fa-solid fa-compass"></i> Browse Books
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/17218a83e4.js" crossorigin="anonymous"></script>
    <script src="./js/script.js"></script>
</body>

</html>