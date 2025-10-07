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
        $ship_id = $db->insert('select-able_values', [
            'id' => $_POST['id'],
            'table_name' => $_POST['table_name'],
            'table_var_name' => $_POST['table_var_name'],
        ]);

        if (!$ship_id) {
            Header("HTTP/1.1 422 Can not add");
            echo json_encode(['success' => false]);
        } else {
            echo json_encode(['success' => true]);
        }
    }
    else if ($_POST['oper'] == 'edit') {

        $ship_id = $db->update('select-able_values', $_POST['id'], [
            'table_name' => $_POST['table_name'],
            'table_var_name' => $_POST['table_var_name'],
        ]);

        if (!$ship_id) {
            Header("HTTP/1.1 666 Can not update");
            echo json_encode(['success' => false]);
        } else {
            echo json_encode(['success' => true]);
        }

    }
    else if ($_POST['oper'] == 'del') {
        $ship_id = $db->delete('select-able_values', $_POST['id']);

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