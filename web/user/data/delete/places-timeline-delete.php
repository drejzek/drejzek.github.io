<?php

define('DB_SERVER1', 'db.dw128.webglobe.com');
define('DB_USERNAME1', 'davidrejzek_cz');
define('DB_PASSWORD1', 'FC3aKkc49rnotW4A');
define('DB_NAME1', 'davidrejzek');

$con = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$conn = new mysqli(DB_SERVER1, DB_USERNAME1, DB_PASSWORD1, DB_NAME1);

require '../class.data.php';

$id = $_POST['id'];
$tl_id = $_POST['TlID'];


$dt = new Data($id);
if($res = $dt->DeleteTimeline($tl_id)){
    echo '1';
}
else{
    echo '2';
}