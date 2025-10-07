<?php
//include('include/db.php');
//$db = new DB([
//    'host' => 'localhost',
//    'user' => 'root',
//    'password' => '',
//    'db' => 'ships',
//]);
//
//$rows = $db->find('main')->rows();
//$values = [];
//
//foreach($rows as $row) {
//    $values[$row['id']] = $row['multicrew'];
//}
//Header("Content-type: application/json");
//echo json_encode($values);

include('include/db.php');
$db = new DB([
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'db' => 'ships',
]);

$rows = $db->find('main');
$rows->select(['id','name','size','role', 'manufacturer', 'price', 'multicrew']);
Header("Content-type: application/json");
$result = [];
$result['result'] = $rows->rows();
$result['query'] = $rows->sql();
echo json_encode($result);