<?php
session_start();

require_once '../Classes/Connect.php';
require_once '../Classes/Login.php';
require_once '../Classes/User.php';

if (!isset($_SESSION['realestate_sessionid']) || !is_numeric($_SESSION['realestate_sessionid'])) {
    header("Location: log_in.php");
    die;
}

$id = $_SESSION['realestate_sessionid'];
$login = new Login();
$result = $login->check_login($id);

if ($result) {
    $user = new User($id);
    $user_data = $user->get_data($id);

    if (!$user_data) {
        header("Location: log_in.php");
        die;
    } else if ($user_data['access'] !== 0) {
        header("Location: home.php");
        die;
    }
} else {
    header("Location: log_in.php");
    die;
}

$property_id = "";

// Handle saving properties
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_property'])) {
    $property_id = $_POST['property_id'];

    $db = new Database();

    $query = "SELECT id FROM users WHERE sessionid = ?";
    $params = [$id];
    $result = $db->read($query, $params);
    $userid = $result[0]['id'];

    $query = "INSERT INTO saved_properties (userid, spropertyid) VALUES (?, ?)";
    $params = [$userid, $property_id];

    $db->save($query, $params);
    header("Location: user_home.php"); // Refresh the page to avoid resubmission
    die;
}

// Show properties
$db = new Database();
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
?>
