<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    if ($conn) {
        echo "Database connection successful!";
    } else {
        echo "Database connection failed.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?> 