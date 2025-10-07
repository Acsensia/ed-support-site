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

if ($db->isConnect() && isset($_REQUEST['values'])) {
    $allowedValues = $db->query("SELECT table_name, table_var_name FROM `select-able_values`");
    $valuesName = $_REQUEST['values'];
    //$response['value'] = $valuesName;//
    $valueIsAllowed = false;
    $columnName = "";
    foreach ($allowedValues as $allowedValue){
        if ($allowedValue['table_name'] == $valuesName){
            $valueIsAllowed = true;
            $columnName = $allowedValue['table_var_name'];
            break;
        }
    }
    if (!$valueIsAllowed){
        $response['status'] = false;
        $response['error'] = "Error passed values are not allowed to be transformed into 'select' options";
    }
    else{
        $extractedValues = $db->query("SELECT $columnName FROM `$valuesName`");
        if ($extractedValues) {
            $response['values'] = $extractedValues;
        }
        else{
            $response['status'] = false;
            $response['error'] = "Error passed values are allowed, but the table with said values is empty";
        }
    }
} else {
    $response['status'] = false;
    $response['error'] = 'Error DB connection';
}
echo json_encode($response);