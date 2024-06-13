<?php
session_start();

// Připojení konfiguračního souboru
require_once '../config.php';
$modal = null;

$conn = new mysqli(DB_SERVER1, DB_USERNAME1, DB_PASSWORD1, DB_NAME1);

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
    $userSql = "SELECT * FROM users WHERE id = $loggedInUserId";
    $userResult = $conn->query($userSql);

    if ($userResult->num_rows > 0) {
        $userRow = $userResult->fetch_assoc();
        $loggedInUserName = $userRow['username'];
        $loggedInName = $userRow['name'];
        $loggedInEmail = $userRow['email'];
    }
}

if(isset($_POST['submit'])){
    $result = null;
    // Příklad proměnných pro nová data
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Příprava SQL dotazu s placeholdery
    $sql = "UPDATE `users` SET `name`=?, `username`=?, `email`=? WHERE id=?";

    // Příprava příkazu
    $stmt = $conn->prepare($sql);

    // Vazba proměnných k placeholdrům
    $stmt->bind_param("sssi", $name, $username, $email, $loggedInUserId);

    // Spuštění příkazu
    if ($stmt->execute()) {
        $modal = '    
        <script>
            $( document ).ready(function() {
                $("#mUserSuccess").modal("show");
            });
        </script>';
    } else {
        $error = $stmt->error;
        $modal = '<script>
        $( document ).ready(function() {
            $("#mUserError").modal("show");
        });
    </script>';
    }

}

if(isset($_POST['viewFile'])){
    header('location: file-detail.php?id=' . $_POST['fileid']);
}
if(isset($_POST['delFile'])){
    $del = unlink($_POST['filepath']);
    $sql = "DELETE FROM file_id WHERE id = " . $_POST['fileid'];
    $result = mysqli_query($conn, $sql);
    if($result && $del){
        $modal = '    
        <script>
        $( document ).ready(function() {
            $("#mDelSuccess").modal("show");
        });
        </script>';
    }
}

?>

<?php include '../header.php'; ?>
<?php echo $modal;?>
<div class="container" style="display: none">
    <ul class="nav nav-underline nav-fill">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#home">Všechny soubory</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#menu1">Mé soubory</a>
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
    <div class="container bg-body-tertiary border p-3 mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">File Library</a></li>
                <li class="breadcrumb-item">Můj profil</li>
            </ol>
        </nav>
    </div>
    <div class="container bg-body-tertiary border p-3">
        <h3>Můj profil</h3>
        <form class="mb-5" method="post">
            <div class="row mb-3">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Jméno</label>
                <div class="col-sm-10">
                <input type="text" class="form-control" id="inputEmail3" name="name" value="<?php echo $loggedInName?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputPassword3" class="col-sm-2 col-form-label">Uživatelské jméno</label>
                <div class="col-sm-10">
                <input type="text" class="form-control" id="inputPassword3" name="username" value="<?php echo $loggedInUserName?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputPassword3" class="col-sm-2 col-form-label">E-mail</label>
                <div class="col-sm-10">
                <input type="text" class="form-control" id="inputPassword3" name="email" value="<?php echo $loggedInEmail?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Uložit</button>
        </form>
        <h4>Moje soubory</h4>
        <style>
            tr{
                width:100%;
            }
        </style>
        <div class="table-responsive">
        <table class="table table-bordered table-hover mt-4">
            <thead>
                <tr>
                    <td>
                        ID
                    </td>
                    <td>
                        Náhled
                    </td>
                    <td>
                        Název
                    </td>
                    <td>
                        Nahráno
                    </td>
                    <td>
                        Akce
                    </td>
                </tr>
            </thead>
            <tbody>
            <?php
        $sql = "SELECT * FROM file_id WHERE user_id = $loggedInUserId";
        $result = mysqli_query($conn, $sql);
        if ($result !== null && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '
                <tr>
                    <td>
                    ' . $row['id'] . '
                    </td>
                    <td style="width:20%">
                    <img class="w-25" src="../' . $row['image_path'] . '">
                    </td>
                    <td>
                    ' . $row['title'] . '
                    </td>
                    <td>
                    ' . $row['created_at'] . '
                    </td>
                    <td style="width:20%">
                        <form method="post">
                            <button class="btn btn-outline-primary" type="submit" name="viewFile">Detail</button>
                            <button class="btn btn-outline-danger" type="submit" name="delFile">Smazat</button>
                            <input type="hidden" name="fileid" value="' . $row['id'] . '">
                            <input type="hidden" name="filepath" value="' . $row['file_path'] . '">
                        </form>
                    </td>
                </tr>
                
                ';
            }
        } else {
            echo '        <div class="alert alert-info">
            <i class="fas fa-info-circle me-3"></i><span>Zatím zde nejsou žádné soubory</span>
        </div>';
        }
        
        ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
<div class="modal fade" id="mDelSuccess">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex">
                    <div class="mx-auto text-center">
                        <i class="far fa-check-circle text-success mb-4" style="font-size:75px"></i>
                        <br>
                        <h4>Soubor byl úpěšně smazán!</h4>
                        <br>
                        <button class="btn btn-success" data-bs-dismiss="modal">Zavřít</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mUserError">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex">
                    <div class="mx-auto text-center">
                        <i class="far fa-times-circle text-success mb-4" style="font-size:75px"></i>
                        <br>
                        <h4>Vyskytla se chyba při úpravě záznamů!</h4>
                        <br>
                        <a href="" class="btn btn-success">Zavřít</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mUserSuccess">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex">
                    <div class="mx-auto text-center">
                        <i class="far fa-check-circle text-success mb-4" style="font-size:75px"></i>
                        <br>
                        <h4>Údaje byly úspěšně upraveny!</h4>
                        <br>
                        <a href="" class="btn btn-success">Zavřít</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>