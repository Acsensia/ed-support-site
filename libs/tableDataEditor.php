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
        $ship_id = $db->insert('main', [
            'id' => $_POST['id'],
            'name' => $_POST['name'],
            'size' => $_POST['size'],
            'role' => $_POST['role'],
            'manufacturer' => $_POST['manufacturer'],
            'price' => $_POST['price'],
            'length' => $_POST['length'],
            'width' => $_POST['width'],
            'height' => $_POST['height'],
            'years_produced' => $_POST['years_produced'],
            'insurance' => $_POST['insurance'],
            'pilot_seats' => $_POST['pilot_seats'],
            'multicrew' => ($_POST['multicrew']=='on')?1:0,
            'fighter_hangar' => ($_POST['fighter_hangar']=='on')?1:0,
            'hull_mass' => $_POST['hull_mass'],
            'mass_lock_factor' => $_POST['mass_lock_factor'],
            'armour' => $_POST['armour'],
            'armour_hardness' => $_POST['armour_hardness'],
            'shields' => $_POST['shields'],
            'heat_capacity' => $_POST['heat_capacity'],
            'fuel_capacity' => $_POST['fuel_capacity'],
            'maneuverability' => $_POST['maneuverability'],
            'top_speed' => $_POST['top_speed'],
            'boost_speed' => $_POST['boost_speed'],
            'unladen_jump_range' => $_POST['unladen_jump_range'],
            'cargo_capacity' => $_POST['cargo_capacity'],
            'hrdp_utility' => $_POST['hrdp_utility'],
            'hrdp_small' => $_POST['hrdp_small'],
            'hrdp_medium' => $_POST['hrdp_medium'],
            'hrdp_large' => $_POST['hrdp_large'],
            'hrdp_huge' => $_POST['hrdp_huge'],
            'cmpr_class1' => $_POST['cmpr_class1'],
            'cmpr_class2' => $_POST['cmpr_class2'],
            'cmpr_class3' => $_POST['cmpr_class3'],
            'cmpr_class4' => $_POST['cmpr_class4'],
            'cmpr_class5' => $_POST['cmpr_class5'],
            'cmpr_class6' => $_POST['cmpr_class6'],
            'cmpr_class7' => $_POST['cmpr_class7'],
            'cmpr_class8' => $_POST['cmpr_class8'],
            'rscm_class1' => $_POST['rscm_class1'],
            'rscm_class2' => $_POST['rscm_class2'],
            'rscm_class3' => $_POST['rscm_class3'],
            'rscm_class4' => $_POST['rscm_class4'],
            'rscm_class5' => $_POST['rscm_class5'],
            'rscm_class6' => $_POST['rscm_class6'],
            'rscm_class7' => $_POST['rscm_class7'],
            'rscm_class8' => $_POST['rscm_class8'],
        ]);

        if (!$ship_id) {
            Header("HTTP/1.1 422 Can not add");
            echo json_encode(['success' => false]);
        } else {
            echo json_encode(['success' => true]);
        }
    }
    else if ($_POST['oper'] == 'edit') {

        $ship_id = $db->update('main', $_POST['id'], [
            'name' => $_POST['name'],
            'size' => $_POST['size'],
            'role' => $_POST['role'],
            'manufacturer' => $_POST['manufacturer'],
            'price' => $_POST['price'],
            'length' => $_POST['length'],
            'width' => $_POST['width'],
            'height' => $_POST['height'],
            'years_produced' => $_POST['years_produced'],
            'insurance' => $_POST['insurance'],
            'pilot_seats' => $_POST['pilot_seats'],
            'multicrew' => ($_POST['multicrew']=='on')?1:0,
            'fighter_hangar' => ($_POST['fighter_hangar']=='on')?1:0,
            'hull_mass' => $_POST['hull_mass'],
            'mass_lock_factor' => $_POST['mass_lock_factor'],
            'armour' => $_POST['armour'],
            'armour_hardness' => $_POST['armour_hardness'],
            'shields' => $_POST['shields'],
            'heat_capacity' => $_POST['heat_capacity'],
            'fuel_capacity' => $_POST['fuel_capacity'],
            'maneuverability' => $_POST['maneuverability'],
            'top_speed' => $_POST['top_speed'],
            'boost_speed' => $_POST['boost_speed'],
            'unladen_jump_range' => $_POST['unladen_jump_range'],
            'cargo_capacity' => $_POST['cargo_capacity'],
            'hrdp_utility' => $_POST['hrdp_utility'],
            'hrdp_small' => $_POST['hrdp_small'],
            'hrdp_medium' => $_POST['hrdp_medium'],
            'hrdp_large' => $_POST['hrdp_large'],
            'hrdp_huge' => $_POST['hrdp_huge'],
            'cmpr_class1' => $_POST['cmpr_class1'],
            'cmpr_class2' => $_POST['cmpr_class2'],
            'cmpr_class3' => $_POST['cmpr_class3'],
            'cmpr_class4' => $_POST['cmpr_class4'],
            'cmpr_class5' => $_POST['cmpr_class5'],
            'cmpr_class6' => $_POST['cmpr_class6'],
            'cmpr_class7' => $_POST['cmpr_class7'],
            'cmpr_class8' => $_POST['cmpr_class8'],
            'rscm_class1' => $_POST['rscm_class1'],
            'rscm_class2' => $_POST['rscm_class2'],
            'rscm_class3' => $_POST['rscm_class3'],
            'rscm_class4' => $_POST['rscm_class4'],
            'rscm_class5' => $_POST['rscm_class5'],
            'rscm_class6' => $_POST['rscm_class6'],
            'rscm_class7' => $_POST['rscm_class7'],
            'rscm_class8' => $_POST['rscm_class8'],
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
        $ship_id = $db->delete('main', $_POST['id']);

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