<?php
session_start();

require_once 'web/config.php';

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
                $fileType = $fileRow['file_type'];
                $imagePath = htmlspecialchars($fileRow['image_path']);
                $downloadFileName = htmlspecialchars($fileRow['title']);
                $likes = $fileRow['likes'];
                $dislikes = $fileRow['dislikes'];
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
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
      body {
        background: #007bff;
        background: linear-gradient(to right, #0062E6, #33AEFF);
        }

        .btn-login {
        font-size: 0.9rem;
        letter-spacing: 0.05rem;
        padding: 0.75rem 1rem;
        }

        .btn-outline-secondary {
        color: white !important;
        background-color: #ea4335;
        }
    </style>
  </head>
  <body>    
  <div class="px-4 py-5 my-5 mx-5 text-center bg-white">
    <div class="col-lg-6 mx-auto">
        
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


        
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto w-75">
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
                <span><?php echo $fileType;?></span>
            </div>
          <div class="card-body d-flex">
            <div class="mx-auto">
            <?php
                        if (in_array($fileExtension, ['mp4', 'webm', 'mkv', 'flv', 'wmv', 'mov']) && strpos($fileType, 'video') !== false) {
                            echo '<video controlslist="nodownload noplaybackrate" disablepictureinpicture="" controls=""><source src="web/' . $filePath . '" type="' . $fileType . '">Your browser does not support the video tag.</video>';
                        } elseif (in_array($fileExtension, ['mp3', 'wav', 'ogg']) && strpos($fileType, 'audio') !== false) {
                            echo '<audio controls=""><source src="web/' . $filePath . '" type="' . $fileType . '">Your browser does not support the audio tag.</audio>';
                        } elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']) && strpos($fileType, 'image') !== false) {
                            echo '<img src="web/' . ($filePath ? $filePath : $imagePath) . '" alt="Image" style="max-width: 100%;">';
                        } else {
                            echo 'File type not supported.';
                        }
                    ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    </div>
  </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>