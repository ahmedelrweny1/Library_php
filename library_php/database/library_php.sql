CREATE DATABASE IF NOT EXISTS library_php;
USE library_php;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(100) NOT NULL,
    description TEXT,
    isbn VARCHAR(13),
    published_year INT,
    image_url VARCHAR(255) DEFAULT 'images/book-default.jpg',
    created_at DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS rented_books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    borrow_date DATETIME NOT NULL,
    return_date DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (book_id) REFERENCES books(id)
);

-- Insert sample books data
INSERT INTO books (title, author, description, isbn, published_year, image_url, created_at) VALUES
('To Kill a Mockingbird', 'Harper Lee', 'A classic novel about racism and justice in the American South', '9780061120084', 1960, 'images/book-1.jpg', NOW()),
('1984', 'George Orwell', 'A dystopian novel about totalitarianism and surveillance', '9780451524935', 1949, 'images/book-2.jpg', NOW()),
('The Great Gatsby', 'F. Scott Fitzgerald', 'A novel about wealth, love, and the American Dream', '9780743273565', 1925, 'images/book-3.jpg', NOW()),
('Pride and Prejudice', 'Jane Austen', 'A romantic novel about manners and relationships', '9780141439518', 1813, 'images/book-4.jpg', NOW()),
('The Catcher in the Rye', 'J.D. Salinger', 'A novel about teenage alienation and identity', '9780316769488', 1951, 'images/book-5.jpg', NOW()),
('The Hobbit', 'J.R.R. Tolkien', 'A fantasy novel about a hobbit who goes on an adventure', '9780618260300', 1937, 'images/book-6.jpg', NOW()),
('The Lord of the Rings', 'J.R.R. Tolkien', 'A fantasy novel about the quest to destroy a powerful ring', '9780618640157', 1954, 'images/book-7.jpg', NOW()),
('Harry Potter and the Sorcerer\'s Stone', 'J.K. Rowling', 'A fantasy novel about a young wizard', '9780590353427', 1997, 'images/book-8.jpg', NOW());
