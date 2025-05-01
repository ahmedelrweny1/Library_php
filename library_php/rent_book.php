<?php
session_start();
require_once 'models/RentedBook.php';
require_once 'models/Book.php';
require_once 'includes/session.php';

requireLogin();

$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($book_id <= 0) {
    $_SESSION['message'] = "Invalid book ID provided.";
    header("Location: index.php");
    exit();
}

$book = new Book();
$book_data = $book->getBookDetails($book_id);

if(!$book_data) {
    $_SESSION['message'] = "Book not found.";
    header("Location: index.php");
    exit();
}

$rental = new RentedBook();
$rental->user_id = $_SESSION['user_id'];
$rental->book_id = $book_id;

if(!$rental->isBookAvailable($book_id)) {
    $_SESSION['message'] = "Sorry, this book is already rented.";
    header("Location: books.php");
    exit();
}

if($rental->rentBook()) {
    $_SESSION['message'] = "You have successfully rented \"" . htmlspecialchars($book_data['title']) . "\".";
    header("Location: profile.php");
} else {
    $_SESSION['message'] = "Failed to rent book. Please try again.";
    header("Location: index.php");
}
exit();
?>