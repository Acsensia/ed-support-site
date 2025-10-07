<?php
//error_reporting(0);
include('../../include/db.php');
$db = new DB([
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'db' => 'ships',
]);
Header("Content-type: application/json");
$response = ['status' => true, 'values' => []];

if ($db->isConnect()) {
    $extractedValues = $db->query("SELECT id FROM `pages`");
    if ($extractedValues) {
        $response['values'] = $extractedValues;
    }
    else{
        $response['status'] = false;
        $response['error'] = "Error the `pages` table is empty";
    }
} else {
    $response['status'] = false;
    $response['error'] = 'Error DB connection';
}
echo json_encode($response);