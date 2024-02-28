<?php
// ####### REM TO FIX DELETE INFO FOR SUCCESS DLEIVERY
require_once "../assets/access.php";
$deleteID = $conn->real_escape_string($_POST['delete-id']);

if (!isInDb($deleteID, 'user_id', 'creators')) {
    $response = ['status' => 'error', 'message' => 'User not found'];
    outputInJSON($response);
    exit;
}

$query_artist_songs = $conn->query("SELECT * FROM songs WHERE creator_id = '{$deleteID}'");
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
        $conn->query("DELETE FROM songs WHERE song_id = $songid and creator_id = '$deleteID'");
        $conn->query("DELETE FROM likes WHERE song_id = $songid");
    }

    $conn->query("DELETE FROM playlists WHERE poster_id = '$deleteID'");
    $conn->query("DELETE FROM requests WHERE user_id = '$deleteID'");
    $conn->query("DELETE FROM followers WHERE user_id = '$deleteID'");

    $delete_creator = $conn->query("DELETE FROM creators WHERE user_id = '$deleteID'");

    if ($delete_creator) {
        $response = ['status' => 'success', 'message' => "Creator handle was deleted"];
    } else {
        $response = ['status' => 'error', 'message' => "Failed to delete creator"];
    }
}
outputInJSON($response);
