<?php
session_start();

// Připojení konfiguračního souboru
require_once 'config.php';

header('location: user/');

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

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

$result = null;
$searcha = array();

$search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

if(strpos($search, 'akey:')){
    $searcha = explode(':', $search);
    $sql = "SELECT * FROM file_id WHERE access_key = '" . $searcha[1] . "'";
    $result = mysqli_query($conn, $sql);
}
else{
    $sql = "SELECT file_id.*, users.username FROM file_id
        LEFT JOIN users ON file_id.user_id = users.id
        WHERE file_id.title LIKE ? OR users.username LIKE ?
        ORDER BY file_id.created_at DESC";

    $stmt = $conn->prepare($sql);
    $searchParam = "%$search%";
    $stmt->bind_param("ss", $searchParam, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
}

?>

<?php include 'header.php'; ?>

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
    <div class="container mb-3 mt-5 d-flex">
        <form class="mx-auto w-50" role="search" method="get">
            <div class="input-group">
                <input type="search" class="form-control" placeholder="Hledat soubory..." aria-label="Search" value="<?php echo $search; ?>" name="search">
                <button class="btn btn-success" type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>
    </div>
<div class="row">    
            <?php
            if(isset($_GET['search'])){
                if ($result !== null && $result->num_rows > 0 && $_GET['search'] != "") {
                    while($row = $result->fetch_assoc()) {
                        if($row['ViewFile'] == 1 && $row['privacy'] >= 3){
                            echo '<div class="col-sm-3 border mb-2">';
                            echo '<a href="player.php?key=' . $row['access_key'] . '">';
                            echo '<div class="d-flex"><img class="mx-auto img-thumbnail w-50" src="' . (isset($row['image_path']) ? $row['image_path'] : 'placeholder.jpg') . '" alt="Uvodni obrazek"></div>';
                            echo '</a>';
                            echo '<h3>' . $row['title'] . '</h3>';
                            echo '<p>Uživatel: ' . (isset($row['username']) ? $row['username'] : 'Není k dispozici') . '</p>';
                            echo '<p>Nahráno: ' . (isset($row['created_at']) ? $row['created_at'] : 'Není k dispozici') . '</p>';
                            echo '</div>';
                        }
                    }
                } else {
                    echo '        <div class="alert alert-info">
                    <i class="fas fa-info-circle me-3"></i><span>Zatím zde nejsou žádné soubory</span>
                    </div>';
                }
            }
            else{
                echo '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
            }
                ?>
            </div>
            <div class="container">
  <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
    <p class="col-md-4 mb-0 text-body-secondary">&copy; 2023 File Library by <a href="http://davidrejzek.cz" target="_blank" rel="noopener noreferrer">David Rejzek</a> <br> Core made by <a href="http://max-online.cz" target="_blank" rel="noopener noreferrer">Max Online CZ</a></p>

    <ul class="nav col-md-4 justify-content-end">
      <li class="nav-item"><a href="." class="nav-link px-2 text-body-secondary">Home</a></li>
      <li class="nav-item"><a href="nahrat.php" class="nav-link px-2 text-body-secondary">Nahrát</a></li>
      <li class="nav-item"><a href="../terms-of-use.php" class="nav-link px-2 text-body-secondary">Podmínky použití</a></li>
    </ul>
  </footer>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>
