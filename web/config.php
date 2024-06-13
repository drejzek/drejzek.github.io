<?php

define('DB_SERVER', 'db.dw128.webglobe.com');
define('DB_USERNAME', 'davidrejzek');
define('DB_PASSWORD', 'FC3aKkc49rnotW4A');
define('DB_NAME', 'davidrejzek_cz');

define('DB_SERVER1', 'db.dw128.webglobe.com');
define('DB_USERNAME1', 'davidrejzek');
define('DB_PASSWORD1', 'FC3aKkc49rnotW4A');
define('DB_NAME1', 'davidrejzek_cz');

// define('DB_SERVER1', 'db.dw128.webglobe.com');
// define('DB_USERNAME1', 'davidrejzek');
// define('DB_PASSWORD1', 'FC3aKkc49rnotW4A');
// define('DB_NAME1', 'davidrejzek_cz');

$con = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$conn = mysqli_connect(DB_SERVER1, DB_USERNAME1, DB_PASSWORD1, DB_NAME1);


?>
