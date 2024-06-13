<?php

session_start();

define('DB_SERVER', 'db.dw128.webglobe.com');
define('DB_USERNAME', 'davidrejzek');
define('DB_PASSWORD', 'FC3aKkc49rnotW4A');
define('DB_NAME', 'davidrejzek_cz');

$con = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

require '../class.data.php';

$id = $_POST['id'];
$coordinates = $_POST['coordinates'];
$species = $_POST['species'];
$status = $_POST['status'];

$new = $coordinates . ';' . $species . ';' . $status . ';';

$general_cl = new Data($id);
$old = $general_cl->getGeneralData()['coordinates'] . ';' . $general_cl->getGeneralData()['species'] . ';' . $general_cl->getGeneralData()['status'] . ';';

$dt = new Data($id);
if($res = $dt->UpdateMap($coordinates, $species, $status)){
    if($dt->updateHistory('map', 'places', $id, $old, $new, 0, $_SESSION['user_id']))
        echo 1;
}
else{
    echo '2';
}