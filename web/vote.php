<?php
session_start();

require_once 'config.php';

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$loggedInUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';
$fileId = isset($_POST['file_id']) ? $_POST['file_id'] : 0;

if (empty($action) || empty($fileId)) {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit();
}

$checkVoteSql = "SELECT like_status FROM file_votes WHERE user_id = $loggedInUserId AND file_id = $fileId";
$checkVoteResult = $conn->query($checkVoteSql);

if ($checkVoteResult) {
    if ($checkVoteResult->num_rows > 0) {
        $row = $checkVoteResult->fetch_assoc();
        $userVote = $row['like_status'];

        if (($action === 'like' && $userVote === '1') || ($action === 'dislike' && $userVote === '0')) {
            $updateVoteSql = "UPDATE file_votes SET like_status = NULL WHERE user_id = $loggedInUserId AND file_id = $fileId";
            $updateVoteResult = $conn->query($updateVoteSql);
        } else {
            $updateVoteSql = "UPDATE file_votes SET like_status = CASE WHEN '$action' = 'like' THEN 1 ELSE 0 END
                              WHERE user_id = $loggedInUserId AND file_id = $fileId";
            $updateVoteResult = $conn->query($updateVoteSql);
        }

        if ($updateVoteResult) {
            $updateCountSql = "UPDATE file_id SET likes = (SELECT COUNT(*) FROM file_votes WHERE file_id = $fileId AND like_status = 1),
                                                dislikes = (SELECT COUNT(*) FROM file_votes WHERE file_id = $fileId AND like_status = 0)
                              WHERE id = $fileId";
            $updateCountResult = $conn->query($updateCountSql);

            if ($updateCountResult) {
                echo json_encode(['success' => true, 'likes' => $updateCountResult['likes'], 'dislikes' => $updateCountResult['dislikes'], 'userVote' => $userVote]);
                exit();
            }
        }
    } else {
        $insertVoteSql = "INSERT INTO file_votes (user_id, file_id, like_status) VALUES ($loggedInUserId, $fileId, CASE WHEN '$action' = 'like' THEN 1 ELSE 0 END)";
        $insertVoteResult = $conn->query($insertVoteSql);

        if ($insertVoteResult) {
            $updateCountSql = "UPDATE file_id SET likes = (SELECT COUNT(*) FROM file_votes WHERE file_id = $fileId AND like_status = 1),
                                                dislikes = (SELECT COUNT(*) FROM file_votes WHERE file_id = $fileId AND like_status = 0)
                              WHERE id = $fileId";
            $updateCountResult = $conn->query($updateCountSql);

            if ($updateCountResult) {
                echo json_encode(['success' => true, 'likes' => $updateCountResult['likes'], 'dislikes' => $updateCountResult['dislikes'], 'userVote' => null]);
                exit();
            }
        }
    }
}

echo json_encode(['success' => false, 'message' => 'Error updating vote.']);
exit();
?>
