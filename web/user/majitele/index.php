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
    $sql = "SELECT * FROM places_owner WHERE name LIKE '%$search%'";

    $result = mysqli_query($con, $sql);
}
else{
    $sql = "SELECT * FROM places_owner";
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
                <input type="search" class="form-control" placeholder="Hledat majitele..." aria-label="Search" value="<?php echo $search; ?>" name="search">
                <button class="btn btn-success" type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>
    </div>
    <div class="d-flex pb-2 border-bottom">
    <h3>Majitelé (<?php echo $result->num_rows?>)</h3>
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
                            IČO
                        </td>
                        <td>
                            Právní forma
                        </td>
                        <td>
                            Město
                        </td>
                    </tr>
                </thead>
                <tbody>  
                    <?php
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()) {
                            switch($row['legal_form']){
                                case '1':
                                    $status = 'Fyzická osoba';
                                break;
                                case '2':
                                    $status = 'Právnická osoba';
                                break;       
                              }
                            echo '
                            <tr class="py-3">
                                <td>
                                    <input type="checkbox">
                                </td>
                                <td>
                                    <button class="text-black btn btn-link text-decoration-none btn-small" data-bs-toggle="modal" data-bs-target="#mEditOwner" data-bs-whatever="' . $row['id'] . '">
                                        ' . $row['name_wi'] . '
                                    </button>
                                </td>
                                <td>
                                    ' . $row['ico'] . '
                                </td>
                                <td>
                                    ' . $status . '
                                </td>
                                <td>
                                    ' . $row['city'] . '
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
<div class="modal" id="mEditOwner">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex mb-3">
                    <h4>Upravit majitele</h4>
                    <button class="ms-auto btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="frmAddRow" method="post" action="../data/insert/places-insert.php">
                <ul class="nav nav-underline">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#owEditTab1">Zákdladni informace</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#owEditTab2">Adresa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#owEditTab3">Kontakt</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#owEditTab4">Lokace</a>
                    </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content m-3">
                        <div class="tab-pane container active" id="owEditTab1">
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="#iOwName" class="form-label">Jméno majitele</label>
                                    <input type="text" name="owName" id="iOwName" class="form-control">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="#iOwLForm" class="form-label">Právní forma</label>
                                    <select class="form-select" name="mLegalForm" id="iOwLForm">
                                        <option value="1">Fyzická osoba</option>
                                        <option value="2">Právnická osoba</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane container fade" id="owEditTab2">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Ulice</label>
                                <input type="text" class="form-control" id="exampleFormControlInput1" name="street" required>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Č. P.</label>
                                        <input type="text" class="form-control" id="exampleFormControlInput1" name="streetnum"  required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Město</label>
                                        <input type="text" class="form-control" id="exampleFormControlInput1" name="city"  required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">PSČ</label>
                                        <input type="text" class="form-control" id="exampleFormControlInput1" name="zip_code"  required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Stát</label>
                                        <input type="text" class="form-control" id="exampleFormControlInput1" name="country"  required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane container fade" id="owEditTab3">
                            <div class="form-group mb-3">
                                <label for="" class="form-label">E-mail</label>
                                <input type="email" name="" id="" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Telefon</label>
                                <input type="tel" name="" id="" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Web:</label>
                                <input type="url" name="" id="" class="form-control">
                            </div>
                        </div>
                        <div class="tab-pane container fade" id="owEditTab4">

                        </div>
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

