<?php
require '../../../config.php';

// Kontrola spojení
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Získání hodnoty z formuláře
$searchText = $_POST['searchText'];

if(empty($_POST['searchText'])){
  $searchText = " ";
}

// SQL příkaz pro vyhledání dat
$sql = "SELECT * FROM places_owner WHERE name LIKE '%$searchText%' OR ico LIKE '%$searchText%' ";

$result = $con->query($sql);

if ($result->num_rows > 0 && $searchText != "") {
  // Výpis výsledků
  while($row = $result->fetch_assoc()) {
    echo "<li class='list-group-item d-flex'><span class='me-auto'>" . $row["name"] . "<br><small>IČO: " . $row["ico"] . "</small></span><form id=\"newOwner\" method=\"post\"><input name=\"id\" type=\"hidden\" value=\"" . $row['id'] . "\"><button data-bs-toggle='modal' data-bs-target='#mOWnerAddAsk' data-bs-whatever='" . $row['id'] . "' type=\"button\" class='btn' onclick='loadNewOwner()'>Vybrat</button></form></li>";
  }
} else {
  echo "";
}

$conn->close();
?>
