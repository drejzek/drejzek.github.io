<?php

session_start();

define('DB_SERVER', 'db.dw128.webglobe.com');
define('DB_USERNAME', 'davidrejzek');
define('DB_PASSWORD', 'FC3aKkc49rnotW4A');
define('DB_NAME', 'davidrejzek_cz');

$con = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

require '../class.data.php';

$id = $_POST['id'];
$name = $_POST['name'];

for($i=0;$i<2;$i++)
    $field[$i] = isset($_POST['field'][$i]) ? 1 : 0;

$abletourbex = $field[0];
$wasexplored = $field[1];

$identifier = $_POST['identifier'];
$shortcut = $_POST['shortcut'];

$new = $name . ';' . $identifier . ';' . $shortcut . ';' . $abletourbex . ';' . $wasexplored . ';';

$general_cl = new Data($id);
$old = $general_cl->getGeneralData()['name'] . ';' . $general_cl->getGeneralData()['identifier'] . ';' . $general_cl->getGeneralData()['shortcut'] . ';' . $general_cl->getGeneralData()['able_to_urbex'] . ';' . $general_cl->getGeneralData()['was_explored'] . ';';

$dt = new Data($id);

if($res = $dt->UpdateGeneral($name, $abletourbex, $wasexplored, $identifier, $shortcut)){
    if($dt->updateHistory('general', 'places', $id, $old, $new, 0, $_SESSION['user_id']))
        echo 1;
}
else{
    echo '2';
}