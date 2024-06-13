<?php
session_start();

// Připojení konfiguračního souboru
require_once '../../config.php';
require_once 'class.data.php';

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
<div class="container mt-3">
    <div class="d-flex border-bottom pb-3">
    <h3>Údržba dat</h3>
    <button class="btn btn-outline-primary ms-auto" data-bs-toggle="modal" data-bs-target="#mStartDataMaintence"><i class="fas fa-plus me-2"></i> Spustit</button>
    </div>
    <div class="row mb-5">  
        <div class="alert alert-info my-3">
            <p><i class="fas fa-info-circle"></i> Údržba dat sloouží pro kontrolu tabulek v databázi a jejich propjení. Při údržbě je kontrolováo především chybné uložžení dat.</p>
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
</div>
<form action="">
    <div class="modal" id="mStartDataMaintence">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h4>Vybrte sekci jež chcete zkontrolovat</h4>
                    <div class="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-center justify-content-center">
                        <div class="list-group list-group-radio d-grid gap-2 border-0">
                            <div class="position-relative">
                                <input class="form-check-input position-absolute top-50 end-0 me-3 fs-5" type="radio" name="listGroupRadioGrid" id="listGroupRadioGrid1" value="" checked>
                                <label class="list-group-item py-3 pe-5" for="listGroupRadioGrid1">
                                    <strong class="fw-semibold">Lokace</strong>
                                    <span class="d-block small opacity-75">Obecné, adresa, bezpečnost, časová osa, odkazy</span>
                                </label>
                            </div>

                            <div class="position-relative">
                                <input class="form-check-input position-absolute top-50 end-0 me-3 fs-5" type="radio" name="listGroupRadioGrid" id="listGroupRadioGrid2" value="">
                                <label class="list-group-item py-3 pe-5" for="listGroupRadioGrid2">
                                    <strong class="fw-semibold">Majitelé</strong>
                                    <span class="d-block small opacity-75">Obecné, napojení na ARES</span>
                                </label>
                            </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#mSelectPartsToCheck">Pokračovat</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="mSelectPartsToCheck">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                <div class="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-center justify-content-center">
                    <div class="list-group">
                        <label class="list-group-item d-flex gap-3">
                            <input class="form-check-input flex-shrink-0" type="checkbox" value="" style="font-size: 1.375em;">
                            <span class="pt-1 form-checked-content">
                                <strong>Hlavní tabulky - obecné</strong>
                            </span>
                        </label>
                        <label class="list-group-item d-flex gap-3">
                            <input class="form-check-input flex-shrink-0" type="checkbox" value="" style="font-size: 1.375em;">
                            <span class="pt-1 form-checked-content">
                                <strong>Adresy</strong>
                            </span>
                        </label>
                        <label class="list-group-item d-flex gap-3">
                            <input class="form-check-input flex-shrink-0" type="checkbox" value="" style="font-size: 1.375em;">
                            <span class="pt-1 form-checked-content">
                                <strong>Bezpečnost</strong>
                            </span>
                        </label>
                        <label class="list-group-item d-flex gap-3">
                            <input class="form-check-input flex-shrink-0" type="checkbox" value="" style="font-size: 1.375em;">
                            <span class="pt-1 form-checked-content">
                                <strong>Časové osy</strong>
                            </span>
                        </label>
                        <label class="list-group-item d-flex gap-3">
                            <input class="form-check-input flex-shrink-0" type="checkbox" value="" style="font-size: 1.375em;">
                            <span class="pt-1 form-checked-content">
                                <strong>Odkazy</strong>
                            </span>
                        </label>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
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

