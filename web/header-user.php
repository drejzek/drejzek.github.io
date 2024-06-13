<?php
// Kontrola, zda session ještě nebyla spuštěna
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Připojení konfiguračního souboru
require_once 'config.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: /max-play/login-register/index.php");
    exit();
}

$loggedInUserName = '';
$loggedInUserId = $_SESSION['user_id'];

if (!empty($loggedInUserId)) {
    $userSql = "SELECT username FROM users WHERE id = $loggedInUserId";
    $userResult = $conn->query($userSql);

    if ($userResult->num_rows > 0) {
        $userRow = $userResult->fetch_assoc();
        $loggedInUserName = $userRow['username'];
    }
}

// Zkontrolujeme, zda proměnná $search existuje, a pokud ne, nastavíme ji na prázdný řetězec
$search = isset($search) ? $search : '';
?>

<!DOCTYPE html>
<html lang="cs" data-bs-theme="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Max-Online.Cz Max-Play</title>
    <script src="guard.js"></script> <!-- Přidáváme odkaz na guard.js -->
    <?php
    // Zkontrolujeme, zda uživatel přistupuje z mobilního zařízení
    //$isMobile = isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(android|iphone|ipad|ipod|blackberry|windows phone)/i', $_SERVER['HTTP_USER_AGENT']);

    // Vybereme odpovídající CSS soubor podle zařízení
    //$headerCSS = $isMobile ? 'mobile-header.css' : 'header.css';
    ?>
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="https://getbootstrap.com/docs/5.3/examples/list-groups/list-groups.css">
    <link rel="stylesheet" href="/urbexnation/assets/leaflet/dist/leaflet.awesome-markers.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
    <script src="/urbexnation/assets/leaflet/dist/leaflet.awesome-markers.js"></script>

    <style>
      .form-control{
        border-radius: 0px;
      }
      .form-control:focus{
        border: 1px solid #0164E7;
        box-shadow: none;
      }
      .table{
        border-radius: 5px;
      }
    </style>
</head>
<body class="bg-body-tertiary">
<nav class="navbar navbar-expand-lg mb-3" style="background: #0265E7; color:#fff;">
  <div class="container-fluid">
    <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 link-body-emphasis text-decoration-none text-white">
      <span class="pe-4 me-4 border-end">Urbex Nation</span>
    </a>
    <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <i class="fas fa-ellipsis-h text-white"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0 text-white">
          <li><a href="../" class="nav-link px-2 text-white">Domů</a></li>
          <li class="nav-item dropdown">
          <a class="nav-link text-decoration-none text-white dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Moje lokace
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="lokace">Moje lokace</a></li>
            <li><a class="dropdown-item" href="lokace/links.php">Odkazy</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
          <li><a href="majitele/" class="nav-link px-2 text-white">Majitelé</a></li>
          <li><a href="" class="nav-link px-2 text-white">Soubory</a></li>
          <li><a href="data/maintenance.php" class="nav-link px-2 text-white">Údržba dat</a></li>
      </ul>
      <div class="dropdown text-end text-white">
        <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle text-white" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fas fa-user me-2"></i> <?php echo htmlspecialchars($loggedInUserName); ?>
        </a>
        <ul class="dropdown-menu text-small ">
          <li><a class="dropdown-item" href="/max-play/web/user/nahrat.php">Nahrát</a></li>
          <li><a class="dropdown-item" href="/max-play/web/user/profile.php">Můj profil</a></li>
          <li><a class="dropdown-item" href="/max-play/web/user/">Moje soubory</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="logout.php">Odhlásit se</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>