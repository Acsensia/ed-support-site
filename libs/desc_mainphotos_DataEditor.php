<?php
include('include/db.php');
$db = new DB([
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'db' => 'ships',
]);

if (isset($_POST['oper'])) {
    if ($_POST['oper'] == 'add') {
        $ship_id = $db->insert('desc_mainphotos', [
            'id' => $_POST['id'],
            'page_id' => $_POST['page_id'],
            'image' => $_POST['image'],
        ]);

        if (!$ship_id) {
            Header("HTTP/1.1 422 Can not add");
            echo json_encode(['success' => false]);
        } else {
            echo json_encode(['success' => true]);
        }
    }
    else if ($_POST['oper'] == 'edit') {

        $ship_id = $db->update('desc_mainphotos', $_POST['id'], [
            'page_id' => $_POST['page_id'],
            'image' => $_POST['image'],
        ]);

        if (!$ship_id) {
            Header("HTTP/1.1 666 Can not update");
            echo json_encode(['success' => false]);
        } else {
            echo json_encode(['success' => true]);
        }

    }
    else if ($_POST['oper'] == 'del') {
        $ship_id = $db->delete('desc_mainphotos', $_POST['id']);

        if (!$ship_id) {
            Header("HTTP/1.1 666 Can not delete");
            echo json_encode(['success' => false]);
        } else {
            echo json_encode(['success' => true]);
        }
    }
} else {
    Header("HTTP/1.1 400 Bad params");
    echo json_encode(['success' => false]);
}