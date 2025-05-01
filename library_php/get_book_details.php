<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'models/Book.php';

$book = new Book();

$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($book_id > 0) {

    $book_data = $book->getBookDetails($book_id);

    if($book_data) {

        $response = array(
            "id" => $book_data['id'],
            "title" => $book_data['title'],
            "author" => $book_data['author'],
            "description" => $book_data['description'],
            "isbn" => $book_data['isbn'],
            "published_year" => $book_data['published_year'],
            "image_url" => $book_data['image_url']
        );

        http_response_code(200);

        echo json_encode($response);
    } else {

        http_response_code(404);
        echo json_encode(array("message" => "Book not found."));
    }
} else {

    http_response_code(400);
    echo json_encode(array("message" => "Invalid book ID."));
}
?>