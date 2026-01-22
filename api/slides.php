<?php
require_once '../database.php';

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (isset($_GET["query"])) {
        $query = $_GET["query"];
        $result = query("SELECT * FROM slides WHERE title LIKE '%$query%' OR url LIKE '%$query%'");
    } else {
        $result = query("SELECT * FROM slides");
    }
    echo json_encode($result);
}
