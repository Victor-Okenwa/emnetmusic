<?php
require '../assets/access.php';

$deleteID = inputValidation($conn, $_POST['delete-id']);

if (isInDb($deleteID, 'song_id', 'songs')) {
    $query_song = $conn->query("SELECT * FROM songs WHERE song_id = $deleteID")->fetch_assoc();
    $thumbnail = $query_song['thumbnail'];
    $audio = $query_song['audio_file'];

    $image_path = "../../audio_thumbnails/$thumbnail";
    $audio_path = "../../audios/$audio";

    if (file_exists($image_path)) {
        unlink($image_path);
    }

    if (file_exists($audio_path)) {
        unlink($audio_path);
    }

    $delete_song = $conn->query("DELETE FROM songs WHERE song_id = $deleteID");

    if ($delete_song) {
        $response = ['status' => 'success', 'message' => 'Song has been deleted'];
    } else {
        $response = ['status' => 'error', 'message' => 'Delete request failed'];
    }
} else {
    $response = ['status' => 'error', 'message' => 'Song not found'];
}

outputInJSON($response);
