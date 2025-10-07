<?php
include('include/image.php');
include('include/db.php');
error_reporting(0);
$db = new DB([
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'db' => 'ships',
]);
Header("Content-type: application/json");
$response = ['status' => true, 'ships' => []];
if ($db->isConnect() && isset($_REQUEST['values'])){
    $allowedValues = $db->query("SELECT table_name, table_var_name FROM `select-able_values`");

    $column = $_REQUEST['values'];

    if ($allowedValues){
        $where = "";
        foreach ($allowedValues as $allowedValue){
            if (isset($_REQUEST[$allowedValue['table_name']])){
                $fixed_value = $allowedValue['table_name'];
                if (substr($allowedValue['table_name'], -1) == 's'){
                    $fixed_value = rtrim($allowedValue['table_name'], 's');
                }
                $where = $where . $fixed_value . "=" . "'" . $_REQUEST[$allowedValue['table_name']] . "'" . " AND ";
            }
            else{
            }
        }
        $where = rtrim($where, ' ');
        $where = rtrim($where, 'D');
        $where = rtrim($where, 'N');
        $where = rtrim($where, 'A');
        $filteringText = null;
        if (isset($_REQUEST['text'])){
            $filteringText ="'%" . $_REQUEST['text'] . "%'";
        }

        $ships = $db->query("SELECT name, $column FROM `main` " . (($where)?("WHERE " . $where):"") . (($filteringText)? (($where)?"AND ":"WHERE ") . "name LIKE $filteringText":"").
        "ORDER BY $column desc");
        $iteration = 0;
        if ($ships){
            $response['ships'] = $ships;
        }
        else{
            $response['status'] = false;
            $response['error'] = "Error no pages with given values found";
        }
    }
    else{
        $ships = $db->query("SELECT name, $column FROM `main`");
        if ($ships){
            $response['ships'] = $ships;
        }
        else{
            $response['status'] = false;
            $response['error'] = "Error table contains no data";
        }
    }

    //echo json_encode($response['ships'][0]);
    //return;

    $diagr = new Diagram();
    $diagr->setTitle("COMPARATIVE DIAGRAM");
    $diagr->setHeight(500);
    $diagr->setMin(0);
    $diagr->setBgColor(200,200,200);
    $diagr->setAxisColor(0,0,0);
//$diagr->setBgImage('bg.jpg');
//$diagr->setBgImage('background.png');
    /*
    $diagr->setColors([
        [255,0,0],
        [0,255,0],
    ]);
    */
    $data = [];
    $width = 0;
    foreach ($response['ships'] as $value){
        if ($value[$column] == 0)continue;
        $data[$value['name']] = $value[$column];
        $width += 100;
    }
    if ($width < 1200) $width = 1200;
    $diagr->setWidth($width);
//    echo json_encode($data);
//    return;
    $diagr->setData($data);
    echo base64_encode($diagr->draw());
} else {
    $errorResponse['error'] = 'Error DB connection';
    echo json_encode($errorResponse);
}