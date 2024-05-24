<?php
session_start();

require_once 'Classes/Connect.php';
require_once 'Classes/Login.php';
require_once 'Classes/User.php';
require_once 'Classes/checks.php';

// Check user
$id = $_SESSION['realestate_sessionid'];
$checks = new checks();
$user_data = $checks->check_client($id);

if(!$user_data){
    header("Location: log_in.php");
}

$db = new Database();

//SAVE PROPERTY
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_property'])) {
    $property_id = $_POST['property_id'];

    $query = "select id from users where sessionid = ?";
    $params = [$id];
    $result = $db->read($query, $params)[0];
    $userid = $result['id'];

    $query = "insert into saved_properties (userid, spropertyid) 
              values (?, ?)";
    $params = [$userid, $property_id];
    $db->save($query, $params);
}

// Show properties
$query_title = isset($_GET['q']) ? $_GET['q'] : '';

$params = [];
if (!empty($query_title)) {
    $query = "SELECT * FROM properties WHERE title LIKE ? OR propertyid LIKE ? OR description LIKE ? OR location LIKE ? LIMIT 1000";
    $params = ['%' . $query_title . '%', '%' . $query_title . '%', '%' . $query_title . '%', '%' . $query_title . '%'];
} else {
    $query = "SELECT * FROM properties LIMIT 1000";
}

$properties = $db->read($query, $params);
header('Content-type: application/json');
echo json_encode($properties);
