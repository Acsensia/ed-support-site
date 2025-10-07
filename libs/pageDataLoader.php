<?php
error_reporting(0);
include('include/db.php');
$db = new DB([
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'db' => 'ships',
]);
Header("Content-type: application/json");
$response = ['status' => true, 'page' => [], 'paragraphs' => [], 'notes' => [], 'ship_data' => [], 'main_image' => []];
if ($db->isConnect()) {
    $id = (isset($_REQUEST['id']))?$_REQUEST['id']:0;
    $page = $db->query("SELECT * FROM `pages` WHERE id=$id");
    $paragraphs = $db->query("SELECT * FROM `desc_paragraphs` WHERE page_id=$id ORDER BY 'order'");
    $notes = $db->query("SELECT * FROM `desc_notes` WHERE page_id=$id ORDER BY 'order'");
    $main_image = $db->query("SELECT image FROM `desc_mainphotos` WHERE page_id=$id");
    if ($page) {
        $ship_id = (int)($page[0]['ship_id']);
        $ship_data = $db->query("SELECT * FROM `main` WHERE id=$ship_id");

        $response['page'] = $page;
        $response['paragraphs'] = $paragraphs;
        $response['notes'] = $notes;
        $response['ship_data'] = $ship_data;
        $response['main_image'] = $main_image;
    }
} else {
    $response['status'] = false;
    $response['error'] = 'Error DB connection';
}

echo json_encode($response);