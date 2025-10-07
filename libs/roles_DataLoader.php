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
$pagination->limits = [10, 15, 20];
$pagination->setLimit((isset($_REQUEST['rows']))?$_REQUEST['rows']:10);

$builderCount = $db->find('roles');
$builder = $db->find('roles')->select(['id','name']);
if (isset($_POST['_search']) && $_POST['_search'] === 'true') {
    $fields = [
        'id' => 'id',
        'name' => 'name',
    ];
    if ($_POST['filters']) {
        $filters = json_decode($_POST['filters'], true);
        foreach($filters['rules'] as $f) {
            if (trim($f['data']) === '') continue;
            $builder->where($fields[$f['field']], $f['op'], $f['data']);
            $builderCount->where($fields[$f['field']], $f['op'], $f['data']);
        }
    } else {
        $builder->where($fields[$_POST['searchField']], $_POST['searchOper'], $_POST['searchString']);
        $builderCount->where($fields[$_POST['searchField']], $_POST['searchOper'], $_POST['searchString']);
    }
}
$c = $builderCount->count();
$pagination->setRowsCount($c);

if (isset($_REQUEST['sidx']) && isset($_REQUEST['sord'])){
    $builder->orderBy($_REQUEST['sidx'] . ' ' . $_REQUEST['sord']);
}

$pagination->setPage((isset($_REQUEST['page']))?$_REQUEST['page']:1);
$builder->offset($pagination->getFirst());
$builder->limit($pagination->getLimit());

$rows = $builder->rows();

$response = [
    'rows' => [],
    'page' => $pagination->getPage(),
    'records' => $pagination->getRowsCount(),
    'total' => $pagination->getPageCount(),
    'sql' => $builder->sql(),
];
$r = [];
foreach($rows as $row) {
    array_push($r, [
        'id' => $row['id'],
        'cell' => [
            $row['id'],
            $row['name'],
        ],
    ]);
}

$response['rows'] = $r;
echo json_encode($response);