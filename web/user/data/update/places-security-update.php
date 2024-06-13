<?php


define('DB_SERVER', 'db.dw128.webglobe.com');
define('DB_USERNAME', 'davidrejzek');
define('DB_PASSWORD', 'FC3aKkc49rnotW4A');
define('DB_NAME', 'davidrejzek_cz');

$con = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

require '../class.data.php';

$id = $_POST['id'];

$sfield = [];

for($i=0;$i<6;$i++)
   $sfield[$i] = isset($_POST['sfield'][$i]) ? 1 : 0;

$security_sys = $sfield[0];
$cctv = $sfield[1];
$security = $sfield[2];
$dogs = $sfield[3];
$homelessOrDrugsMen = $sfield[4];
$paranormal = $sfield[5];


$overall_status = $_POST['overall_status'];
$statistics = $_POST['statistics'];
$neighbours = $_POST['neighbours'];
$catchnum = $_POST['catchnum'];
$entrance_difficulty = $_POST['entrance_difficulty'];

$dt = new Data($id);
if($res = $dt->UpdateSecurity($overall_status, $statistics, $neighbours, $catchnum, $entrance_difficulty, $cctv, $security_sys, $security, $dogs, $homelessOrDrugsMen, $paranormal)){
    echo '1';
}
else{
    echo '2';
}