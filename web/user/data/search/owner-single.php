<?php
define('DB_SERVER', 'db.dw128.webglobe.com');
define('DB_USERNAME', 'davidrejzek');
define('DB_PASSWORD', 'FC3aKkc49rnotW4A');
define('DB_NAME', 'davidrejzek_cz');

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Kontrola spojení
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Získání hodnoty z formuláře
$id = $_POST['id'];

// SQL příkaz pro vyhledání dat
$sql = "SELECT * FROM places_owner WHERE id = $id";

$result = $con->query($sql);

if ($result->num_rows > 0) {
  // Výpis výsledků
  $o = mysqli_fetch_array($result);
  echo $o['name'] . ';' . $o['ico'];
} else {
  echo "";
}

$conn->close();
?>
