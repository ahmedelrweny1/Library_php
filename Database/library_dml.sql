USE library_db;

-- Insert Sample Users (password is 'password123' hashed)
INSERT INTO users (username, password, email, full_name) VALUES
('john_doe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'john@example.com', 'John Doe'),
('jane_smith', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jane@example.com', 'Jane Smith'),
('admin_user', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com', 'Admin User');

-- Insert Sample Books
INSERT INTO books (title, author, description, isbn, published_year, image_url, category) VALUES
('The Hunger Games', 'Suzanne Collins', 'In a dystopian future, teenagers are forced to fight for their lives in a televised battle.', '9780439023481', 2008, 'images/books/hunger-games.jpg', 'Young Adult'),
('Six of Crows', 'Leigh Bardugo', 'A convict''s son plans an impossible heist in this fantasy thriller.', '9781627792127', 2015, 'images/books/six-of-crows.jpg', 'Fantasy'),
('Project Hail Mary', 'Andy Weir', 'An astronaut wakes up alone on a spacecraft with no memory of how he got there.', '9780593135204', 2021, 'images/books/project-hail-mary.jpg', 'Science Fiction'),
('The Silent Patient', 'Alex Michaelides', 'A woman''s act of violence against her husband sparks a psychological mystery.', '9781250301697', 2019, 'images/books/silent-patient.jpg', 'Thriller'),
('Pride and Prejudice', 'Jane Austen', 'A classic tale of love and social class in 19th century England.', '9780141439518', 1813, 'images/books/pride-prejudice.jpg', 'Classic'),
('Dune', 'Frank Herbert', 'A science fiction masterpiece about power, politics, and destiny.', '9780441172719', 1965, 'images/books/dune.jpg', 'Science Fiction'),
('To Kill a Mockingbird', 'Harper Lee', 'A powerful story of racial injustice and loss of innocence in the American South.', '9780446310789', 1960, 'images/books/mockingbird.jpg', 'Classic'),
('The Guest List', 'Lucy Foley', 'A wedding celebration turns dark when someone turns up dead.', '9780062868930', 2020, 'images/books/guest-list.jpg', 'Mystery');

-- Insert Sample Rentals
INSERT INTO rentals (user_id, book_id, rental_date, return_date, status) VALUES
(1, 1, DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_ADD(NOW(), INTERVAL 4 DAY), 'active'),
(2, 3, DATE_SUB(NOW(), INTERVAL 15 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY), 'returned'),
(1, 4, DATE_SUB(NOW(), INTERVAL 20 DAY), NULL, 'overdue');

-- Insert Sample Reviews
INSERT INTO reviews (user_id, book_id, rating, comment) VALUES
(1, 1, 5, 'An amazing book that kept me on the edge of my seat!'),
(2, 1, 4, 'Great story and character development.'),
(1, 3, 5, 'Fascinating sci-fi novel with realistic science.'),
(2, 4, 4, 'Intriguing mystery with unexpected twists.'); 