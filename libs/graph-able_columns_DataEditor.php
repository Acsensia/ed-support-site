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
        $ship_id = $db->insert('graph-able_columns', [
            'id' => $_POST['id'],
            'name' => $_POST['name'],
        ]);

        if (!$ship_id) {
            Header("HTTP/1.1 422 Can not add");
            echo json_encode(['success' => false]);
        } else {
            echo json_encode(['success' => true]);
        }
    }
    else if ($_POST['oper'] == 'edit') {

        $ship_id = $db->update('graph-able_columns', $_POST['id'], [
            'name' => $_POST['name'],
        ]);
        //echo json_encode([$ship_id]);

        if (!$ship_id) {
            Header("HTTP/1.1 666 Can not update");
            echo json_encode(['success' => false]);
        } else {
            echo json_encode(['success' => true]);
        }

    }
    else if ($_POST['oper'] == 'del') {
        $ship_id = $db->delete('graph-able_columns', $_POST['id']);

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