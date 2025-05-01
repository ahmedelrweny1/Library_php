<?php
require_once __DIR__ . '/../config/database.php';

class Book {
    private $conn;
    private $table_name = "books";

    public $id;
    public $title;
    public $author;
    public $description;
    public $isbn;
    public $published_year;
    public $image_url;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getRandomBooks($limit = 4) {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY RAND() LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function getLatestBooks($limit = 4) {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function getBookDetails($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function searchBooks($keyword) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE title LIKE :keyword 
                  OR author LIKE :keyword 
                  OR description LIKE :keyword";
        $stmt = $this->conn->prepare($query);
        $searchTerm = "%" . $keyword . "%";
        $stmt->bindParam(":keyword", $searchTerm);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                (title, author, description, isbn, published_year, created_at)
                VALUES
                (:title, :author, :description, :isbn, :published_year, :created_at)";

        $stmt = $this->conn->prepare($query);

        $this->created_at = date('Y-m-d H:i:s');

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":isbn", $this->isbn);
        $stmt->bindParam(":published_year", $this->published_year);
        $stmt->bindParam(":created_at", $this->created_at);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read($id = null) {
        if($id) {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
        } else {
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($query);
        }

        $stmt->execute();
        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET
                    title = :title,
                    author = :author,
                    description = :description,
                    isbn = :isbn,
                    published_year = :published_year
                WHERE
                    id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":author", $this->author);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":isbn", $this->isbn);
        $stmt->bindParam(":published_year", $this->published_year);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>