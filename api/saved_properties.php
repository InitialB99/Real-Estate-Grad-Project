<?php
session_start();

require_once '../Classes/Connect.php';
require_once '../Classes/checks.php';
require_once '../Classes/manageproperty.php';

// Check user
$id = $_SESSION['realestate_sessionid'];
$checks = new checks();
$user_data = $checks->check_client($id);
if (!$user_data) {
    echo json_encode([]);
    exit();
}

// Fetch saved properties for the user
$userid = $user_data['id'];
$db = new Database();
$query = "SELECT spropertyid FROM saved_properties WHERE userid = ?";
$params = [$userid];
$savedProperties = $db->read($query, $params);
$savedPropertiesIds = array_column($savedProperties, 'spropertyid');

echo json_encode($savedPropertiesIds);
