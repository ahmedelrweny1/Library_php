<?php
require_once __DIR__ . '/../config/database.php';

class RentedBook {
    private $conn;
    private $table_name = "rented_books";

    public $id;
    public $user_id;
    public $book_id;
    public $borrow_date;
    public $return_date;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function rentBook() {
        $query = "INSERT INTO " . $this->table_name . "
                (user_id, book_id, borrow_date)
                VALUES
                (:user_id, :book_id, :borrow_date)";

        $stmt = $this->conn->prepare($query);

        $this->borrow_date = date('Y-m-d H:i:s');

        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":book_id", $this->book_id);
        $stmt->bindParam(":borrow_date", $this->borrow_date);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function returnBook($id) {
        $query = "UPDATE " . $this->table_name . "
                SET return_date = :return_date
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        
        $return_date = date('Y-m-d H:i:s');
        $stmt->bindParam(":return_date", $return_date);
        $stmt->bindParam(":id", $id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getUserRentedBooks($user_id) {
        $query = "SELECT b.*, rb.id as rent_id, rb.borrow_date, rb.return_date 
                FROM " . $this->table_name . " rb
                JOIN books b ON rb.book_id = b.id
                WHERE rb.user_id = :user_id
                ORDER BY rb.borrow_date DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();

        return $stmt;
    }

    public function isBookAvailable($book_id) {
        $query = "SELECT COUNT(*) as count
                FROM " . $this->table_name . "
                WHERE book_id = :book_id
                AND return_date IS NULL";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":book_id", $book_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] == 0;
    }
}
?>