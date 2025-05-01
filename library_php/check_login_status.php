<?php
session_start();
header('Content-Type: application/json');

$response = [
    'isLoggedIn' => isset($_SESSION['user_id']),
    'userId' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null,
    'username' => isset($_SESSION['username']) ? $_SESSION['username'] : null
];

echo json_encode($response);
?> 