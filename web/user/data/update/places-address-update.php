<?php

session_start();

define('DB_SERVER', 'db.dw128.webglobe.com');
define('DB_USERNAME', 'davidrejzek');
define('DB_PASSWORD', 'FC3aKkc49rnotW4A');
define('DB_NAME', 'davidrejzek_cz');

$con = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

require '../class.data.php';

$id = $_POST['id'];
$street = $_POST['street'];
$streetnum = $_POST['streetnum'];
$city = $_POST['city'];
$country = $_POST['country'];
$zip_code = $_POST['zip_code'];
$new_value = $street . ';' . $streetnum . ';' . $city . ';' . $country . ';' . $zip_code . ';';

$addr_cl = new Data($id);
$addr = $addr_cl->getAddressData();
$old_value = $addr['street'] . ';' . $addr['street_num'] . ';' . $addr['city'] . ';' . $addr['country'] . ';' . $addr['zip_code'] . ';';

$dt = new Data($id);

if($res = $dt->UpdateAddress($street, $streetnum, $city, $country, $zip_code)){
    if($dt->updateHistory('address', 'places', $id, $old_value, $new_value, 0, $_SESSION['user_id']))
        echo 1;
}
else{
    echo '2';
}