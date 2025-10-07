<?php
//error_reporting(0);
include('include/db.php');
include('include/pagination.php');
$db = new DB([
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'db' => 'ships',
]);
Header("Content-type: application/json");
$pagination = new Pagination();
$pagination->limits = [5, 10, 15, 20];
$pagination->setLimit((isset($_REQUEST['limit']))?$_REQUEST['limit']:5);
$response = ['status' => true, 'rows' => [], 'rowsCount' => 0, 'page' => 1, 'limit' => $pagination->getLimit()];
if ($db->isConnect()) {
    $wh = "WHERE name<>''";
    $c = $db->queryOne("SELECT COUNT(*) as 'count' FROM `main` $wh");
    $pagination->setRowsCount($c['count']);
    $pagination->setPage((isset($_REQUEST['page']))?$_REQUEST['page']:1);

    $rows = $db->query("SELECT * FROM `main` $wh ORDER BY `size` LIMIT ".$pagination->getFirst()."," . $pagination->getLimit());
    if ($rows) {
        $response['rows'] = $rows;
        $response['rowsCount'] = (int)$pagination->getRowsCount();
        $response['page'] = $pagination->getPage();
        $response['pageCount'] = $pagination->getPageCount();
    }
} else {
    $response['status'] = false;
    $response['error'] = 'Error DB connection';
}

echo json_encode($response);
