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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.3/dist/quill.snow.css" rel="stylesheet" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <style> 
    body{
      background-color: #f8f9fa;
    }
      .form-control, .form-select{
        border-radius: 5px;
        border: 1px solid #d2d2d2;
        margin: 2px;
      }
      .form-control:focus, .form-select:focus{
        border: 2px solid #0164E7;
        box-shadow: none;
      }
      .table{
        border-radius: 5px;
      }
      #timeline{
        max-height: 198px;
        overflow: auto;
      }
      #results{
        max-height: 250px;
        overflow: auto;
      }
      #results > list-group-item{
        max-height: 66px;
      }
      .card{
        border-radius: 0px;
        padding: 0px;
        margin-bottom: 2rem;
      }
      .card-header{
        background: #fff;
        padding: 10px;
      }
      .card-body{
        margin: 0px;
      }
      .control-panel{
        /* position: absolute;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%); */
      }
</style>
</head>
<body onload="loadOwners()">
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
          <li><a href="/urbexnation/web/user/lokace/" class="nav-link px-2 text-white"><i class="fas fa-chevron-left"></i> Zpět</a></li>
      </ul>
      <div class="dropdown text-end text-white">
        <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle text-white" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fas fa-user me-2"></i> <?php echo htmlspecialchars($loggedInUserName); ?>
        </a>
        <ul class="dropdown-menu text-small ">
          <li><a class="dropdown-item" href="/urbexnation/web/user/nahrat.php">Nahrát</a></li>
          <li><a class="dropdown-item" href="/urbexnation/web/user/profile.php">Můj profil</a></li>
          <li><a class="dropdown-item" href="/urbexnation/web/user/">Moje soubory</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="logout.php">Odhlásit se</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>
