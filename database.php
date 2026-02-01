<?php

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'sliders';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

function query($query)
{
    global $conn;
    $result = $conn->query($query);
    $query_type = strtoupper(trim(explode(' ', trim($query))[0]));

    if (in_array($query_type, ['INSERT', 'UPDATE', 'DELETE'])) {
        if ($result) {
            return $conn->affected_rows;
        } else {
            return false;
        }
    } else {
        if ($result && $result->num_rows > 0) {
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            return $data;
        }

        return [];
    }
}
