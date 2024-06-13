<?php
session_start();

require_once 'config.php';

$likes = "";
$dislikes = "";

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$loggedInUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$usernameParam = isset($_GET['username']) ? htmlspecialchars($_GET['username']) : '';
$key = isset($_GET['key']) ? htmlspecialchars($_GET['key']) : '';

if (empty($key)) {
    echo 'Username or file title is not provided.';
    exit();
}

$userSql = "SELECT id FROM users WHERE username = '$usernameParam'";
$userResult = $conn->query($userSql);

if ($userResult) {
    $fileSql = "SELECT * FROM file_id
                WHERE access_key = '$key'";
        $fileResult = $conn->query($fileSql);

        if ($fileResult) {
            if ($fileResult->num_rows > 0) {
                $fileRow = $fileResult->fetch_assoc();
                $akey = htmlspecialchars($fileRow['access_key']);
                $passRequired = $fileRow['passRequired'];
                $pass = $fileRow['pass'];
                if($passRequired){
                    if(isset($_POST['submit'])){
                        if($pass == md5($_POST['pass'])){
                            $_SESSION['file_unlocked'] = true;
                            $_SESSION[$akey] = true;
                            header('location: player.php?key=' . $akey);
                        }
                    }
                }
            } else {
                echo 'File not found in the database. SQL: ' . $fileSql;
                if ($conn->error) {
                    echo '<br>Error: ' . $conn->error;
                }
                exit();
            }
        } else {
            echo 'Error executing query: ' . $conn->error;
            exit();
        }
} else {
    echo 'Error executing query: ' . $conn->error;
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Odemknout soubor</title>
    <script src="like.js"></script>
    <style>
        .far{
            font-size: 40px;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="player-container">
    <div class="player-box">

    </div>
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card border rounded-3">
            <div class="card-body bg-secondary">
                <div class="d-flex">
                    <div class="me-auto">
                    <div class="d-flex">
                        <h2 class="ms-3">Soubor je chráněn heslem</h2>
                    </div>
                    </div>
                </div>
            </div>
          <div class="card-body d-flex">
            <div class="mx-auto">
                <form action="" method="post">
                    <div class="form-group mb-3">
                        <input type="password" name="pass" id="">
                    </div>
                    <div class="form-group mb-3">
                        <input type="submit" name="submit" id="">
                    </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>




<!--     <div class="player-buttons">
        <form method="post" action="">
            <input type="hidden" name="action" value="like">
            <button type="button" class="btn" id="green" onclick="updateVote('like', <?php echo $fileRow['id']; ?>)">
                <i class="fa fa-thumbs-up fa-lg" aria-hidden="true"></i> 
                <span id="likesCount"><?php echo $likes; ?></span>
            </button>
        </form>
        <form method="post" action="">
            <input type="hidden" name="action" value="dislike">
            <button type="button" class="btn" id="red" onclick="updateVote('dislike', <?php echo $fileRow['id']; ?>)">
                <i class="fa fa-thumbs-down fa-lg" aria-hidden="true"></i> 
                <span id="dislikesCount"><?php echo $dislikes; ?></span>
            </button>
        </form>
    </div>
 -->
    <script>
        function updateVote(action, fileId) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'vote.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        document.getElementById('likesCount').textContent = response.likes;
                        document.getElementById('dislikesCount').textContent = response.dislikes;
                    } else {
                        console.error(response.message);
                    }
                }
            };
            xhr.send('action=' + action + '&file_id=' + fileId);
        }
    </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
