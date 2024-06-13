<?php

    include '../config.php';

    $f1 = "";
    $f2 = "";
    $f3 = "";
    $f4 = "";
    $f5 = "";
    $f6 = "";

    $ftype1 = "";
    $ftype2 = "";
    $ftype3 = "";
    $ftype4 = "";
    $ftype5 = "";
    $ftype6 = "";
    $ftype7 = "";

    $ftype_enabled = 0;

    // Pokud byl formulář odeslán
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Inicializace pole pro podmínky
        $conditions = array();
        
        // Pro každý checkbox zkontrolujeme, jestli je zaškrtnutý
        if (isset($_POST['filter1'])) {
            $conditions[] = "status = '1'";
            $f1 = "checked";
        }
        if (isset($_POST['filter2'])) {
            $conditions[] = "status = '2'";
            $f2 = "checked";
        }
        if (isset($_POST['filter3'])) {
            $conditions[] = "status = '3'";
            $f3 = "checked";
        }
        if (isset($_POST['filter4'])) {
            $conditions[] = "status = '4'";
            $f4 = "checked";
        }
        if (isset($_POST['filter5'])) {
            $conditions[] = "status = '5'";
            $f5 = "checked";
        }
        if (isset($_POST['filter6'])) {
            $conditions[] = "status = '6'";
            $f6 = "checked";
        }

        //--------------------------------------------

        if (isset($_POST['filtertype1'])) {
            $conditions2[] = "species = '1'";
            $ftype1 = "checked";
            $ftype_enabled = 1;
        }
        if (isset($_POST['filtertype2'])) {
            $conditions2[] = "species = '2'";
            $ftype2 = "checked";
            $ftype_enabled = 1;
        }
        if (isset($_POST['filtertype3'])) {
            $conditions2[] = "species = '3'";
            $ftype3 = "checked";
            $ftype_enabled = 1;
        }
        if (isset($_POST['filtertype4'])) {
            $conditions2[] = "species = '4'";
            $ftype4 = "checked";
            $ftype_enabled = 1;
        }
        if (isset($_POST['filtertype5'])) {
            $conditions2[] = "species = '5'";
            $ftype5 = "checked";
            $ftype_enabled = 1;
        }
        if (isset($_POST['filtertype6'])) {
            $conditions2[] = "species = '6'";
            $ftype6 = "checked";
            $ftype_enabled = 1;
        }
        if (isset($_POST['filtertype7'])) {
            $conditions2[] = "species = '7'";
            $ftype7 = "checked";
            $ftype_enabled = 1;
        }

        // Pokud chcete filtrovat podle více kategorií, přidejte další podmínky pro další checkboxy
        
        // Pokud jsou nějaké podmínky, vytvoříme SQL dotaz
        if (!empty($conditions)) {
            $sql = "SELECT * FROM places WHERE (" . implode(" OR ", $conditions) . ($ftype_enabled ? ") AND (" . implode(" OR ", $conditions2) : "") . ")";
        } else {
            // Pokud nejsou žádné podmínky, vybereme všechny položky
            $sql = "SELECT * FROM places";
        }
        
        // Vykonání SQL dotazu
        $r = mysqli_query($con, $sql);
    }
    else{
        $sql = "SELECT * FROM places";
        $r = mysqli_query($con, $sql);
    }

?>
<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
        <link rel="stylesheet" href="https://getbootstrap.com/docs/5.3/examples/list-groups/list-groups.css">
        <link rel="stylesheet" href="../user/assets/leaflet/dist/leaflet.awesome-markers.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
        <script src="../user/assets/leaflet/dist/leaflet.awesome-markers.js"></script>
        <style>
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
            }
            #map {
                height: 100%;
                width: 100vw; 
            }
        </style>
    </head>
    <body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Dropdown
                </a>
                <ul class="dropdown-menu">
                    <li class="dropdoqn-item">
                        <button class="nav-link" aria-disabled="true" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight">Filtrovat lokace</button>
                    </li>
                    <li class="dropdoqn-item">
                        <button class="nav-link" aria-disabled="true" data-bs-toggle="modal" data-bs-target="#mPlaces">Lokace</button>
                    </li>
                </ul>
                </li>
                <li class="nav-item">
                <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                </li>
            </ul>
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
            </div>
        </div>
        </nav>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasRightLabel">Filtrovat lokace</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="" method="post">
                <span class="fw-bold">Stav lokace</span>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="1" name="filter1" <?php echo $f1?>>
                    <label class="form-check-label" for="1">
                        Prázdné
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="2" name="filter2" <?php echo $f2?>>
                    <label class="form-check-label" for="2">
                        Používané
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="3" name="filter3" <?php echo $f3?>>
                    <label class="form-check-label" for="3">
                        Stavební úpravy
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="4" name="filter4" <?php echo $f4?>>
                    <label class="form-check-label" for="4">
                        Zanklý
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="5" name="filter5" <?php echo $f5?>>
                    <label class="form-check-label" for="5">
                        K záchraně
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" name="filter6" id="6" <?php echo $f6?>>
                    <label class="form-check-label" for="6">
                        Zachráněné
                    </label>
                </div>
                <span class="fw-bold mt-3">Typ lokace</span>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="type1" name="filtertype1" <?php echo $ftype1?>>
                    <label class="form-check-label" for="type1">
                        Obytné
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="type2" name="filtertype2" <?php echo $ftype2?>>
                    <label class="form-check-label" for="type2">
                        Reprezentativní
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="type3" name="filtertype3" <?php echo $ftype3?>>
                    <label class="form-check-label" for="type3">
                        Průmyslové
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="type4" name="filtertype4" <?php echo $ftype4?>>
                    <label class="form-check-label" for="type4">
                        Veřejné
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="type5" name="filtertype5" <?php echo $ftype5?>>
                    <label class="form-check-label" for="type5">
                        Komerční
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" name="filtertype6" id="type6" <?php echo $ftype6?>>
                    <label class="form-check-label" for="type6">
                        Vojenské
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" name="filtertype7" id="type7" <?php echo $ftype7?>>
                    <label class="form-check-label" for="type7">
                        Dopravní
                    </label>
                </div>
                <div class="mt-3">
                    <input type="submit" value="Filtrovat" class="btn btn-outline-success">
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="mPlaces">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">

                </div>
                <div class="modal-body">
                    <?php
                    
                        /* while($l = mysqli_fetch_array($r)){
                            echo '
                            
                            <div class="row">
                                <div class="col-sm-2">
                                    <img src="../user/images/' . $l['header_img_path'] . '" alt="" class="img-thumbnail" width="200">
                                </div>
                                <div class="col-sm-10">
                                    <h4 class="fw-bold mb-0">' . $l['name'] . '</h4>
                                    <span>' . $l['id'] . ' / ' . $l['species'] . ' / ' . $l['status'] . '</span>
                                </div>
                            </div>
                            
                            ';
                        } */
                    
                    ?>
                </div>
            </div>
        </div>
    </div>
        <div id="map" class="rounded"></div>

        <script>
            var map = L.map('map').setView([49.845068, 15.007324], 8);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);
        
            <?php
                $species = null;
                $ico = null;
                $status = null;
                while($l1 = mysqli_fetch_array($r)){
                    switch($l1['species']){
                        case '0':
                            
                        break;
                        case '1':
                            $species = 'Obytné';
                            $ico = 'building';
                        break;
                        case '2':
                            $species = 'Reprezentativvní';
                            $ico = 'university';
                        break;       
                        case '3':
                            $species = 'Průmyslové';
                            $ico = 'industry';
                        break;
                        case '4':
                        $species = 'Veřejné';
                        $ico = 'school';
                        break;
                        case '5':
                            $species = 'Komerční'; 
                            $ico = 'hospital-alt';
                        break;
                        case '6':
                            $species = 'Vojenské';   
                            $ico = 'shield-alt';
                        break;       
                        case '7':
                            $species = 'Dopravní';   
                            $ico = 'traffic-light';
                        break;
                    }
                    switch($l1['status']){
                        case '0':
                        break;
                        case '1':
                            $status = 'Prázdné';
                            $color = "red";
                        break;
                        case '2':
                            $status = 'Nikdy prázdné';
                            $color = "green";
                        break;       
                        case '3':
                            $status = 'V opravě';
                            $color = "yellow";
                        break;
                        case '4':
                            $status = 'Zaniklý';
                            $color = "black";
                        break;
                        case '5':
                        $status = 'K záchraně';
                        $color = "orange";
                    break;
                    case '6':
                        $status = 'Zachráněné';
                        $color = "green";
                    break;
                    }
                    echo '
                        L.marker([' . $l1['coordinates'] . '], {icon: L.AwesomeMarkers.icon({icon: "' . $ico . '", prefix: "fa", markerColor: "' . $color . '", iconColor: "#fff"}) }).addTo(map).bindPopup(\'<h5 class="border-bottom"><i class="me-2 fas fa-' . $ico . '"></i>' . $l1['name'] . ' </h5><strong>ID Lokace: </strong>' . $l1['id'] . '<br><strong>Stav: </strong>' . $status . '<br><strong>Poziční identifikátor: </strong>' . explode(", ", $l1['coordinates'])[0] . "N/" . explode(", ", $l1['coordinates'])[1] . "E" . '<br><br><a class="btn btn-secondary text-white btn-sm mt-3" href="lokace.php?id=' . $l1['identifier'] . '">Zobrazit</a>\');
                    ';
                }
            ?> 

        </script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    </body>
</html>