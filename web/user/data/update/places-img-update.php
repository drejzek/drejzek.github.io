<?php

    //require '/urbexnation/web/config.php';

    define('DB_SERVER', 'db.dw128.webglobe.com');
    define('DB_USERNAME', 'davidrejzek');
    define('DB_PASSWORD', 'FC3aKkc49rnotW4A');
    define('DB_NAME', 'davidrejzek_cz');

    $con = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    require '../class.data.php';

    $id = $_POST['id'];
    $owid = $_POST['src'];
    $dt = new Data($id);
    if($res = $dt->UpdateImg($owid))
        echo 1;
    else
        echo 2;

?>