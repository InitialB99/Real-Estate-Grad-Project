<?php
session_start();

include_once '../Classes/connect.php';
include_once '../Classes/Login.php';

$id = $_SESSION['realestate_sessionid'];
$db = new Database();

// Show properties
$query_title = isset($_GET['q']) ? $_GET['q'] : '';

$params = [];
if (!empty($query_title)) {
    $query = "SELECT * FROM properties WHERE title LIKE ? OR listing_type LIKE ? OR rooms LIKE ? OR description LIKE ? OR location LIKE ? LIMIT 1000";
    $params = ['%' . $query_title . '%', '%' . $query_title . '%', '%' . $query_title . '%', '%' . $query_title . '%', '%' . $query_title . '%'];
} else {
    $query = "SELECT * FROM properties LIMIT 1000";
}

$properties = $db->read($query, $params);
header('Content-type: application/json');
echo json_encode($properties);
