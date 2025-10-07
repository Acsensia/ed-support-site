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
    $allowedValues = $db->query("SELECT table_name, table_var_name FROM `select-able_values`");
    $response['values'] = $allowedValues;
} else {
    $response['status'] = false;
    $response['error'] = 'Error DB connection';
}
echo json_encode($response);