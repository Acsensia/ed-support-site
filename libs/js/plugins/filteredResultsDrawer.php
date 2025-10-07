<?php
//error_reporting(0);
include('../../include/db.php');
include('../../include/pagination.php');
$db = new DB([
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'db' => 'ships',
]);
Header("Content-type: application/json");
$pagination = new Pagination();
$pagination->limits = [6, 12, 18, 24];
$pagination->setLimit((isset($_REQUEST['limit']))?$_REQUEST['limit']:6);
$response = ['status' => true, 'pages' => [], 'pagesCount' => 0, 'pageN' => 1, 'limit' => $pagination->getLimit()];

if ($db->isConnect()) {
    $allowedValues = $db->query("SELECT table_name, table_var_name FROM `select-able_values`");
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

        $pageCount = $db->queryOne("SELECT COUNT(*) as 'count' FROM `main` " . (($where)?("WHERE " . $where):"") . (($filteringText)? (($where)?"AND ":"WHERE ") . "name LIKE $filteringText":""));
        $pagination->setRowsCount($pageCount['count']);
        $pagination->setPage((isset($_REQUEST['page']))?$_REQUEST['page']:1);

        $pages = $db->query("SELECT * FROM `main` " . (($where)?("WHERE " . $where):"") . (($filteringText)? (($where)?"AND ":"WHERE ") . "name LIKE $filteringText":"") .
            "LIMIT " . $pagination->getFirst() . "," . $pagination->getLimit());
        $iteration = 0;
        foreach($pages as $page){
            $id = $page['id'];
            $image = $db->query("SELECT image FROM `desc_mainphotos` WHERE page_id=$id");
            if ($image){
                $pages[$iteration]['image'] = $image[0]['image'];
            }
            $iteration++;
        }
        if ($pages){
            $response['pages'] = $pages;
            $response['pageN'] = $pagination->getPage();
            $response['pagesCount'] = (int)$pagination->getPageCount();
        }
        else{
            $response['status'] = false;
            $response['error'] = "Error no pages with given values found";
        }
    }
    else{
        $pages = $db->query("SELECT * FROM `main`");
        if ($pages){
            $response['pages'] = $pages;
        }
        else{
            $response['status'] = false;
            $response['error'] = "Error table contains no data";
        }
    }
} else {
    $response['status'] = false;
    $response['error'] = 'Error DB connection';
}
echo json_encode($response);