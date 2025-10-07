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

$f = fopen("1.txt", "w");
fclose($f);
$f = fopen("1.txt", "w");
fputs($f, "POST:\n");
fputs($f, var_export($_POST, true));
fclose($f);

$pagination = new Pagination();
$pagination->limits = [10, 15, 20];
$pagination->setLimit((isset($_REQUEST['rows']))?$_REQUEST['rows']:10);

$builderCount = $db->find('main');
$builder = $db->find('main')->select(['id','name','size','role', 'manufacturer', 'price', 'length', 'width', 'height', 'years_produced', 'insurance',
    'pilot_seats', 'multicrew', 'fighter_hangar', 'hull_mass', 'mass_lock_factor', 'armour', 'armour_hardness', 'shields', 'heat_capacity', 'fuel_capacity',
    'maneuverability', 'top_speed', 'boost_speed', 'unladen_jump_range', 'cargo_capacity', 'hrdp_utility', 'hrdp_small', 'hrdp_medium', 'hrdp_large',
    'hrdp_huge', 'cmpr_class1', 'cmpr_class2', 'cmpr_class3', 'cmpr_class4', 'cmpr_class5', 'cmpr_class6', 'cmpr_class7', 'cmpr_class8', 'rscm_class1',
    'rscm_class2', 'rscm_class3', 'rscm_class4', 'rscm_class5', 'rscm_class6', 'rscm_class7', 'rscm_class8']);
if (isset($_POST['_search']) && $_POST['_search'] === 'true') {
    $fields = [
        'id' => 'id',
        'name' => 'name',
        'size' => 'size',
        'role' => 'role',
        'manufacturer' => 'manufacturer',
        'price' => 'price',
        'length' => 'length',
        'width' => 'width',
        'height' => 'height',
        'years_produced' => 'years_produced',
        'insurance' => 'insurance',
        'pilot_seats' => 'pilot_seats',
        'multicrew' => 'multicrew',
        'fighter_hangar' => 'fighter_hangar',
        'hull_mass' => 'hull_mass',
        'mass_lock_factor' => 'mass_lock_factor',
        'armour' => 'armour',
        'armour_hardness' => 'armour_hardness',
        'shields' => 'shields',
        'heat_capacity' => 'heat_capacity',
        'fuel_capacity' => 'fuel_capacity,',
        'maneuverability' => 'maneuverability',
        'top_speed' => 'top_speed',
        'boost_speed' => 'boost_speed',
        'unladen_jump_range' => 'unladen_jump_range',
        'cargo_capacity' => 'cargo_capacity',
        'hrdp_utility' => 'hrdp_utility',
        'hrdp_small' => 'hrdp_small',
        'hrdp_medium' => 'hrdp_medium',
        'hrdp_large' => 'hrdp_large',
        'hrdp_huge' => 'hrdp_huge',
        'cmpr_class1' => 'cmpr_class1',
        'cmpr_class2' => 'cmpr_class2',
        'cmpr_class3' => 'cmpr_class3',
        'cmpr_class4' => 'cmpr_class4',
        'cmpr_class5' => 'cmpr_class5',
        'cmpr_class6' => 'cmpr_class6',
        'cmpr_class7' => 'cmpr_class7',
        'cmpr_class8' => 'cmpr_class8',
        'rscm_class1' => 'rscm_class1',
        'rscm_class2' => 'rscm_class2',
        'rscm_class3' => 'rscm_class3',
        'rscm_class4' => 'rscm_class4',
        'rscm_class5' => 'rscm_class5',
        'rscm_class6' => 'rscm_class6',
        'rscm_class7' => 'rscm_class7',
        'rscm_class8' => 'rscm_class8',
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
            $row['size'],
            $row['role'],
            $row['manufacturer'],
            $row['price'],
            $row['length'],
            $row['width'],
            $row['height'],
            $row['years_produced'],
            $row['insurance'],
            $row['pilot_seats'],
            $row['multicrew'],
            $row['fighter_hangar'],
            $row['hull_mass'],
            $row['mass_lock_factor'],
            $row['armour'],
            $row['armour_hardness'],
            $row['shields'],
            $row['heat_capacity'],
            $row['fuel_capacity'],
            $row['maneuverability'],
            $row['top_speed'],
            $row['boost_speed'],
            $row['unladen_jump_range'],
            $row['cargo_capacity'],
            $row['hrdp_utility'],
            $row['hrdp_small'],
            $row['hrdp_medium'],
            $row['hrdp_large'],
            $row['hrdp_huge'],
            $row['cmpr_class1'],
            $row['cmpr_class2'],
            $row['cmpr_class3'],
            $row['cmpr_class4'],
            $row['cmpr_class5'],
            $row['cmpr_class6'],
            $row['cmpr_class7'],
            $row['cmpr_class8'],
            $row['rscm_class1'],
            $row['rscm_class2'],
            $row['rscm_class3'],
            $row['rscm_class4'],
            $row['rscm_class5'],
            $row['rscm_class6'],
            $row['rscm_class7'],
            $row['rscm_class8'],
        ],
    ]);
}

$response['rows'] = $r;
echo json_encode($response);
