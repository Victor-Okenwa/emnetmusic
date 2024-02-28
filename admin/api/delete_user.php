<?php
require_once "../assets/access.php";
$deleteID = $conn->real_escape_string($_POST['delete-id']);
$response = [];

if (!isInDb($deleteID, 'id', 'users')) {
    $response = ['status' => 'error', 'message' => 'User not found'];
    outputInJSON($response);
    exit;
}

$query_user = $conn->query("SELECT * from users where id = $deleteID")->fetch_assoc();
$userID = $query_user['user_id'];

if (isInDb($userID, 'user_id', 'creators')) {
    $query_artist_songs = $conn->query("SELECT * FROM songs WHERE creator_id = '{$userID}'");
    if ($query_artist_songs->num_rows > 0) {
        while ($row_creator = $query_artist_songs->fetch_assoc()) {
            $songid = $row_creator['song_id'];
            $songname = $row_creator['song_name'];
            $thumbnail = "../../audio_thumbnails/{$row_creator['thumbnail']}";
            $audio = "../../audios/{$row_creator['audio_file']}";

            file_exists($thumbnail) ? unlink($thumbnail) : '';
            file_exists($audio) ? unlink($audio) : '';

            $conn->query("DELETE FROM streams WHERE song_id = $songid");
            $conn->query("DELETE FROM history WHERE song_id = $songid and song_name = '$songname'");
            $conn->query("DELETE FROM songs WHERE song_id = $songid and creator_id = '$userID'");
            $conn->query("DELETE FROM likes WHERE song_id = $songid");
        }

        $conn->query("DELETE FROM playlists WHERE poster_id = '$userID'");
        $conn->query("DELETE FROM requests WHERE user_id = '$userID'");

        $delete_creator = $conn->query("DELETE FROM creators WHERE user_id = '$userID'");
    }
}

$user_profile = "../../userprofiles/{$query_user['user_profile']}";

file_exists($user_profile) ? unlink($user_profile) : '';

$conn->query("DELETE FROM chats WHERE sender_id = '$userID' OR receiver_id = '$userID'");
$conn->query("DELETE FROM followers WHERE user_id = '$deleteID' OR poster_id = '$deleteID'");

$delete_user = $conn->query("DELETE FROM users WHERE user_id = '$userID'");

if ($delete_user) {
    $response = ['status' => 'success', 'message' => "User cleared from records"];
} else {
    $response = ['status' => 'error', 'message' => "Failed to delete this user"];
}
outputInJSON($response);
