<?php
session_start();

// Připojení konfiguračního souboru
require_once '../../config.php';

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

<?php include '../../header-user.php'; ?>
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
                <input type="search" class="form-control" placeholder="Hledat lokace..." aria-label="Search" value="<?php echo $search; ?>" name="search">
                <button class="btn btn-success" type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>
    </div>
    <div class="d-flex pb-2 border-bottom">
    <h3>Moje lokace (<?php echo $result->num_rows?>)</h3>
    <a class="btn ms-auto" data-bs-toggle="modal" data-bs-target="#mAddRow"><i class="fas fa-plus me-2"></i> Přidat</a>
    </div>
    <div class="row mb-5 pb-5">  
        <div class="table-responsive rounded">
            <table class="table table-hover shadow mt-4 rounded-5">
                <thead class="fw-5">
                    <tr>
                        <td>
                            <input type="checkbox" name="check_all" onclick="checkAll(this);">
                        </td>
                        <td>
                            Název
                        </td>
                        <td>
                            Souřadnice
                        </td>
                        <td>
                            Typ lokace
                        </td>
                        <td>
                            Stav lokace
                        </td>
                    </tr>
                </thead>
                <tbody>  
                    <?php
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()) {
                            switch($row['status']){
                                case '0':
                                break;
                                case '1':
                                    $status = 'Prázdné';
                                break;
                                case '2':
                                    $status = 'Používané';
                                break;       
                                case '3':
                                    $status = 'V opravě';
                                break;
                                case '4':
                                    $status = 'Zaniklé';
                                break;
                                case '4':
                                    $status = 'K záchraně';
                                break;
                                case '4':
                                  $status = 'Zachráněné';
                                break;
                              }
                              switch($row['species']){
                                case '0':
                                    
                                break;
                                case '1':
                                    $species = 'Obytné';
                                    $ico = 'fas fa-building';
                                break;
                                case '2':
                                    $species = 'Reprezentativvní';
                                    $ico = 'fas fa-university';
                                break;       
                                case '3':
                                    $species = 'Průmyslové';
                                    $ico = 'fas fa-industry';
                                break;
                                case '4':
                                  $species = 'Veřejné';
                                  $ico = 'fas fa-school';
                                break;
                                case '5':
                                    $species = 'Komerční'; 
                                    $ico = 'fas fa-hospital-alt';
                                break;
                                case '6':
                                    $species = 'Vojenské';   
                                    $ico = 'fas fa-shield-alt';
                                break;       
                                case '7':
                                    $species = 'Dopravní';   
                                    $ico = 'fas fa-car';
                                break;
                              }
                            echo '
                            <tr class="py-3">
                                <td>
                                    <input type="checkbox">
                                </td>
                                <td>
                                    <a class="text-black text-decoration-none" href="edit2.php?id=' . $row['id'] . '">
                                        ' . $row['name'] . '
                                    </a>
                                </td>
                                <td>
                                    ' . $row['coordinates'] . '
                                </td>
                                <td>
                                    ' . $species . '
                                </td>
                                <td>
                                    ' . $status . '
                                </td>
                            </tr>
                            
                            ';
                        }
                        echo '
                            </tbody>
                            </table>
                        </div>';
                    }
                    else{
                        echo '
                            </tbody>
                            </table>
                        </div>';
                        echo '<span class="text-secondary">Kde nic, tu nic</span>';
                    }
                    ?>
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
                <form id="frmAddRow" method="post" action="../data/insert/places-insert.php">
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
                        xhr.open('POST', '../data/insert/places-insert.php', true);
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

