<?php
session_start();
require_once '../database.php';
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (isset($_GET["query"])) {
        $query = $_GET["query"];
        $userId = $_GET['id'];
        $result = query("SELECT * FROM slides WHERE title LIKE '%$query%' OR url LIKE '%$query%' AND userId = '$userId'");
    }
    echo json_encode($result);
}
