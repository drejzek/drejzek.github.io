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
$titleParam = isset($_GET['key']) ? htmlspecialchars($_GET['key']) : '';

if (empty($titleParam)) {
    echo 'Username or file title is not provided.';
    exit();
}

$userSql = "SELECT id FROM users WHERE username = '$usernameParam'";
$userResult = $conn->query($userSql);

if ($userResult) {
    $fileSql = "SELECT file_id.*, users.username FROM file_id
                    LEFT JOIN users ON file_id.user_id = users.id
                    WHERE file_id.access_key = '$titleParam'";
        $fileResult = $conn->query($fileSql);

        if ($fileResult) {
            if ($fileResult->num_rows > 0) {
                $fileRow = $fileResult->fetch_assoc();
                $filePath = htmlspecialchars($fileRow['file_path']);
                $akey = htmlspecialchars($fileRow['access_key']);
                $fileType = $fileRow['file_type'];
                $fileSize = $fileRow['file_size'];
                $imagePath = htmlspecialchars($fileRow['image_path']);
                $downloadFileName = htmlspecialchars($fileRow['title']);
                $hideView = $fileRow['hideView'];
                $passRequired = $fileRow['passRequired'];
                $pass = $fileRow['pass'];
                $likes = $fileRow['likes'];
                $dislikes = $fileRow['dislikes'];

                if($fileRow['ViewFile'] == 0 && $fileRow['privacy'] < 3){
                    header('location: .');
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

$fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

if (empty($downloadFileName)) {
    echo 'File title is empty in the database.';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titleParam); ?></title>
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
    <div class="modal" id="mView">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <?php
                    if (in_array($fileExtension, ['mp4', 'webm', 'mkv', 'flv', 'wmv', 'mov']) && strpos($fileType, 'video') !== false) {
                        echo '<i class="far fa-file-video"></i>';
                    } elseif (in_array($fileExtension, ['mp3', 'wav', 'ogg']) && strpos($fileType, 'audio') !== false) {
                        echo '<i class="far fa-file-audio"></i>';
                    } elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']) && strpos($fileType, 'image') !== false) {
                        echo '<i class="far fa-file-image"></i>';
                    } else {
                        echo '<i class="far fa-file"></i>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>


    <?php 
    
        if($pass){
            if(!isset($_SESSION['file_unlocked']) && !isset($_SESSION[$akey])){
                header('location: file-unlock.php?file=' . $akey);
            }
        }
    
    ?>

        
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card border rounded-3">
            <div class="card-body bg-secondary">
                <div class="d-flex">
                    <div class="me-auto">
                    <div class="d-flex">
                    <?php
                        if (in_array($fileExtension, ['mp4', 'webm', 'mkv', 'flv', 'wmv', 'mov']) && strpos($fileType, 'video') !== false) {
                            echo '<i class="far fa-file-video"></i>';
                        } elseif (in_array($fileExtension, ['mp3', 'wav', 'ogg']) && strpos($fileType, 'audio') !== false) {
                            echo '<i class="far fa-file-audio"></i>';
                        } elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']) && strpos($fileType, 'image') !== false) {
                            echo '<i class="far fa-file-image"></i>';
                        } else {
                            echo '<i class="far fa-file"></i>';
                        }
                    ?>
                    <h2 class="ms-3"><?php echo $downloadFileName;?></h2>
                    </div>
                    </div>
                    <a class="btn btn-primary p-3" href="<?php echo $filePath; ?>" download="<?php echo $downloadFileName; ?>"><i class="fas fa-download me-3"></i> Stáhnout</a>
                </div>
                <span><?php echo $fileType . ' • ' . (str_starts_with($fileSize, '0') ? ($fileSize / 1000000) . ' MB' : ($fileSize / 1000) . ' KB') ?></span>
            </div>
          <div class="card-body d-flex">
            <div class="mx-auto">
            <?php
                    if($hideView == 0){
                        if (in_array($fileExtension, ['mp4', 'webm', 'mkv', 'flv', 'wmv', 'mov']) && strpos($fileType, 'video') !== false) {
                            echo '<video controlslist="nodownload noplaybackrate" disablepictureinpicture="" controls=""><source src="' . $filePath . '" type="' . $fileType . '">Your browser does not support the video tag.</video>';
                        } elseif (in_array($fileExtension, ['mp3', 'wav', 'ogg']) && strpos($fileType, 'audio') !== false) {
                            echo '<audio controls=""><source src="' . $filePath . '" type="' . $fileType . '">Your browser does not support the audio tag.</audio>';
                        } elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']) && strpos($fileType, 'image') !== false) {
                            echo '<img src="' . ($filePath ? $filePath : $imagePath) . '" alt="Image" style="max-width: 100%;">';
                        } else {
                            echo '<div class="alert alert-info"><i class="fas fa-info-circle me-3"></i><span>Typ souboru není podporrován.</span><div>';
                        }
                    }
                    else{
                        echo '<div class="alert alert-info"><i class="fas fa-info-circle me-3"></i><span>Vlastník tohoto souboru náhled skryl.</span><div>';
                    }
                    ?>
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
