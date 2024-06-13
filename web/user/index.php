<?php
session_start();

// Připojení konfiguračního souboru
require_once '../config.php';
require_once 'data/class.data.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: /max-play/auth/");
    exit();
}

$loggedInUserName = '';
$loggedInName = '';
$loggedInUserId = $_SESSION['user_id'];

if (!empty($loggedInUserId)) {
    $userSql = "SELECT * FROM users WHERE id = $loggedInUserId";
    $userResult = $conn->query($userSql);

    if ($userResult->num_rows > 0) {
        $userRow = $userResult->fetch_assoc();
        $loggedInUserName = $userRow['username'];
        $loggedInName = $userRow['name'];
    }
}


if(isset($_POST['viewFile'])){
    header('location: file-detail.php?id=' . $_POST['fileid']);
}

$result = null;

if(isset($_GET['search'])){
    $search = $_GET['search'];
    $sql = "SELECT * FROM places WHERE name LIKE '%$search%'";

    $result = mysqli_query($con, $sql);
}
else{
    $sql = "SELECT * FROM places";
    $result = mysqli_query($con, $sql);
}

?>

<?php include '../header-user.php'; ?>
<style>
    body{
        background: linear-gradient(90deg, rgba(235,234,255,1) 0%, rgba(255,255,255,1) 35%, rgba(255,252,219,1) 100%);

    }
</style>
<div class="container" style="display: none">
    <ul class="nav nav-underline nav-fill">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#home">Všechny soubory</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#menu1">Mé lokace</a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane container active" id="home">

        </div>
        <div class="tab-pane container fade" id="menu1">

        </div>
    </div>
</div>
<div class="container">
    <div class="container mb-3 mt-5 d-flex">
        <form class="mx-auto w-50" role="search" method="get">
            <div class="input-group">
                <input type="search" class="form-control" placeholder="Hledat soubory..." aria-label="Search" value="<?php echo $search; ?>" name="search">
                <button class="btn btn-success" type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>
    </div>
    <div class="d-flex pb-2 border-bottom">
    <h3>Přehled</h3>
    <a class="btn ms-auto" data-bs-toggle="modal" data-bs-target="#mAddRow"><i class="fas fa-plus me-2"></i> Přidat</a>
    </div>
    <div class="row my-3">  
        <div class="col-sm-4">
            <div class="container bg-body-tertiary p-3 d-flex border rounded">
                <div class=" h-100 me-3 position-relative">
                    <div class="">
                        <i class="fas fa-map-marker-alt" style="font-size:30px"></i>
                    </div>
                </div>
                <div>
                    <h4 class=""><?php echo (new Data(0))->getPlacesNum() ?></h4>
                    <h4 class="">Lokací</h4>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="container bg-body-tertiary p-3 d-flex border rounded">
                <div class=" h-100 me-3 position-relative">
                    <div class="">
                        <i class="fas fa-user-tag" style="font-size:30px"></i>
                    </div>
                </div>
                <div>
                    <h4 class=""><?php echo (new Data(0))->getOwnersNum() ?></h4>
                    <h4 class="">Majielů</h4>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="container bg-body-tertiary p-3 d-flex border rounded">
                <div class=" h-100 me-3 position-relative">
                    <div class="">
                        <i class="fas fa-file" style="font-size:30px"></i>
                    </div>
                </div>
                <div>
                    <h4 class=""><?php echo (new Data(0))->getPlacesNum() ?></h4>
                    <h4 class="">Souborů</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="container border bg-body-tertiary d-flex">
        <div class="me-auto">
            <h4 class="m-3">
                <i class="fas fa-wrench text-warning me-3"></i> Údržba dat
            </h4>
            <p>Poslední údržba dat byla provedena <?php $dt = (new Data(0))->getLastMaintenceDate(); $date = explode(' ', $dt)['0']; $time = explode(' ', $dt)['1']; echo explode('-', $date)[2] . '. ' . explode('-', $date)[1] . '. ' . explode('-', $date)[0] . " " . $time; ?></p>
        </div>
        <div class="w-25 position-relative">
            <a href="data/maintenance.php" class="btn btn-primary position-absolute top-50 start-50 translate-middle">Spustit údržbu dat</a>
        </div>
    </div>

    <div class="container border bg-body-tertiary my-3 p-3">
        <h4 class="mb-3">Mapa lokací</h4>
        <div id="map" style="height:600px" class="rounded"></div>

        <script>
        var map = L.map('map').setView([49.845068, 15.007324], 8);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        <?php
                $sql = "SELECT * FROM places";
                $r = mysqli_query($con, $sql);

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
                            $ico = 'car';
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
                        L.marker([' . $l1['coordinates'] . '], {icon: L.AwesomeMarkers.icon({icon: "' . $ico . '", prefix: "fa", markerColor: "' . $color . '", iconColor: "#fff"}) }).addTo(map).bindPopup(\'<h5 class="border-bottom"><i class="me-2 fas fa-' . $ico . '"></i>' . $l1['name'] . ' </h5><strong>ID Lokace: </strong>' . $l1['id'] . '<br><strong>Stav: </strong>' . $status . '<br><strong>Poziční identifikátor: </strong>' . explode(", ", $l1['coordinates'])[0] . "N/" . explode(", ", $l1['coordinates'])[1] . "E" . '<br><br><a class="btn btn-secondary text-white btn-sm mt-3" href="lokace/edit2.php?id=' . $l1['id'] . '">Upravit lokaci</a><a class="btn btn-outline-secondary btn-sm ms-3 mt-3" href="/exploreblog/www/majitel?locid=' . $l1['id'] . '">Detail majitele</a>\');
                    ';
                    /* echo '
                    var marker = L.marker([' . $l1['coordinates'] . ']).addTo(map) .bindPopup(\'<h5 class="border-bottom"><i class="me-2 ' . $ico . '"></i>' . $l1['name'] . ' </h5><strong>ID Lokace: </strong>' . $l1['id'] . '<br><strong>Stav: </strong>' . $status . '<br><strong>Poziční identifikátor: </strong>' . explode(", ", $l1['coordinates'])[0] . "N/" . explode(", ", $l1['coordinates'])[1] . "E" . '<br><br><a class="btn btn-secondary text-white btn-sm mt-3" href="lokace/edit2.php?id=' . $l1['id'] . '">Upravit lokaci</a><a class="btn btn-outline-secondary btn-sm ms-3 mt-3" href="/exploreblog/www/majitel?locid=' . $l1['id'] . '">Detail majitele</a>\');
                    '; */
                }
                ?> 
        </script>


    </div>
    <!-- <div class="container-fluid position-absolute position-fixed bottom-0 w-100 bg-white">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
            <p class="col-md-4 mb-0 text-body-secondary">&copy; 2023 File Library by <a href="http://davidrejzek.cz" target="_blank" rel="noopener noreferrer">David Rejzek</a> <br> Core made by <a href="http://max-online.cz" target="_blank" rel="noopener noreferrer">Max Online CZ</a></p>

            <ul class="nav col-md-4 justify-content-end">
            <li class="nav-item"><a href="." class="nav-link px-2 text-body-secondary">Home</a></li>
            <li class="nav-item"><a href="nahrat.php" class="nav-link px-2 text-body-secondary">Nahrát</a></li>
            <li class="nav-item"><a href="../terms-of-use.php" class="nav-link px-2 text-body-secondary">Podmínky použití</a></li>
            </ul>
        </footer>
    </div> -->
</div>

<div class="modal" id="mAddRow">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex mb-3">
                    <h4>Přidat lokaci</h4>
                    <button class="ms-auto btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="frmAddRow" method="post" action="data/insert/places-insert.php">
                    <div class="form-floating mb-3">
                        <input type="text" name="locname" id="" class="form-control" placeholder="Název lokace">
                        <label for="">Název lokace</label>
                    </div>
                    <input type="button" value="Pokračovat" class="btn btn-success" onclick="sumbitNewRow()">
                    <!-- <input type="submit" value="Pokračovat 2" class="btn btn-success"> -->
                </form>
                <script>
                    function sumbitNewRow() {
                        var form = document.getElementById('frmAddRow');
                        var formData = new FormData(form);

                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'data/insert/places-insert.php', true);
                        xhr.onload = function() {
                            if (xhr.status >= 200 && xhr.status < 300) {
                            if(xhr.responseText == '1'){
                                $('#success').modal('show')
                                $('#mAddRow').modal('hide')
                            }
                            else{
                                $('#unsuccess').modal('show')
                            }
                            // Zde můžete provést další akce po úspěšném odeslání formuláře
                            }
                        };
                        xhr.onerror = function() {
                            console.error('Request failed');
                            // Zde můžete zpracovat případnou chybu při odesílání formuláře
                        };
                        xhr.send(formData);
                    }
                </script>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="success">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex">
                    <div class="mx-auto text-center">
                        <i class="far fa-check-circle text-success mb-4" style="font-size:75px"></i>
                        <br>
                        <h4>Data byla úpěšně změněna!</h4>
                        <br>
                        <button class="btn btn-success" data-bs-dismiss="modal">Zavřít</i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 <div class="modal fade" id="unsuccess">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex">
                    <div class="mx-auto text-center">
                        <i class="far fa-times-circle text-danger mb-4" style="font-size:75px"></i>
                        <br>
                        <h4>Nastala chyba při vykonávání požadavku!</h4>
                        <br>
                        <button class="btn btn-success" data-bs-dismiss="modal">Zavřít</i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function checkAll(source) {
      var checkboxes = document.querySelectorAll('input[type="checkbox"]');
      for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = source.checked;
      }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>

