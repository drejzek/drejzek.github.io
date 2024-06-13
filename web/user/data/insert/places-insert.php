<?php

define('DB_SERVER', 'db.dw128.webglobe.com');
define('DB_USERNAME', 'davidrejzek');
define('DB_PASSWORD', 'FC3aKkc49rnotW4A');
define('DB_NAME', 'davidrejzek_cz');

    $con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    require '../class.data.php';

    $name = $_POST['locname'];

    $dt = new Data(0);
    $r = $dt->InsertRow($name);

    echo $r ? 1 : 2;


?>