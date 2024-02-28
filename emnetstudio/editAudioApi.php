<?php

/*________________-_____________________|
# Here an admin can Edit his own uploaded audio
* This is the API/Backend file
|_________________-_____________________*/

include "access.php";
$user_id = $fetch_user['user_id'];
$songid = inputValidation($conn, $_POST['songid']);
$response = [];
if (!empty($_POST['songid'])) {
    if (isset($_POST['songname'])) {
        $songname = strtolower(inputValidation($conn, $_POST['songname']));
        $update = $conn->query("UPDATE songs SET song_name = '{$songname}' WHERE song_id = {$songid} and creator_id = '{$user_id}'");
        if ($update) {
            $response = array('status' => 'success', 'message' => "Updated song name");
        } else {
            $response = array('status' => 'error', 'message' => "Song name update failed");
        }
    }

    if (isset($_POST['artist'])) {
        $artist = strtolower(inputValidation($conn, $_POST['artist']));
        $update = $conn->query("UPDATE songs SET artist = '{$artist}' WHERE song_id = {$songid} and creator_id = '{$user_id}'");
        if ($update) {
            $response = array('status' => 'success', 'message' => "Updated artist name");
        } else {
            $response = array('status' => 'error', 'message' => "Artist name update failed");
        }
    }

    if (isset($_POST['genre'])) {
        $genre = inputValidation($conn, $_POST['genre']);
        $update = $conn->query("UPDATE songs SET genre = '{$genre}' WHERE song_id = {$songid} and creator_id = '{$user_id}'");
        if ($update) {
            $response = array('status' => 'success', 'message' => "Updated genre name");
        } else {
            $response = array('status' => 'error', 'message' => "Genre name update failed");
        }
    }

    if (isset($_POST['agelimit']) && isset($_POST['remix'])) {
        $agelimit = inputValidation($conn, $_POST['agelimit']);
        $remix = inputValidation($conn, $_POST['remix']);

        $update = $conn->query("UPDATE songs SET age_limit = $agelimit, remix = $remix WHERE song_id = {$songid} and creator_id = '{$user_id}'");
        if ($update) {
            $response = array('status' => 'success', 'message' => "Updated name");
        } else {
            $response = array('status' => 'error', 'message' => "Genre update failed");
        }
    }

    if (isset($_POST['thumbnail'])) {
        $thumbnail = inputValidation($conn, $_POST['thumbnail']);
        $userName = $_SESSION['nickname'];
        function generateFilename($extension)
        {
            global $userName;
            $filename = uniqid() . random_int(100, 9999);
            return $filename . $userName . '.' . $extension;
        }

        $thumbnail = str_replace('data:image/jpeg;base64,', '', $thumbnail);
        $thumbnail = base64_decode($thumbnail);
        $newImageName = generateFilename('jpg');
        $thumbnail_folder = '../audio_thumbnails/';

        $query_images = $conn->query("SELECT thumbnail FROM songs WHERE song_id = {$songid} AND creator_id = '{$user_id}'");
        $previous_thumbnail = $query_images->fetch_assoc()['thumbnail'];

        if (file_exists($thumbnail_folder . $previous_thumbnail)) {
            unlink($thumbnail_folder . $previous_thumbnail);
        }

        if (file_put_contents($thumbnail_folder . $newImageName, $thumbnail)) {
            $update = $conn->query("UPDATE songs SET thumbnail = '{$newImageName}' WHERE song_id = {$songid} and creator_id = '{$user_id}'");

            if ($update) {
                $response = array('status' => 'success', 'message' => "Update successful");
            } else {
                if (file_exists($thumbnail_folder . $newImageName)) {
                    unlink($thumbnail_folder . $newImageName);
                }
                $response = array('status' => 'error', 'message' => "Update failed");
            }
        }
    }

    if (isset($_FILES['audio'])) {
        $audio = $_FILES['audio']['name'];
        $audio_folder = '../audios/';

        $query_audio = $conn->query("SELECT * FROM songs WHERE audio_file = '$audio' ");

        if ($query_audio->num_rows == 0) {
            $query_audios = $conn->query("SELECT audio_file FROM songs WHERE song_id = {$songid} AND creator_id = '{$user_id}'");
            $previous_audio = $query_audios->fetch_assoc()['audio_file'];
            if (is_file($audio_folder . $previous_audio)) {
                unlink($audio_folder . $previous_audio);
            }
            if (move_uploaded_file($_FILES['audio']['tmp_name'], $audio_folder . $audio)) {
                $update = $conn->query("UPDATE songs SET audio_file = '{$audio}' WHERE song_id = {$songid} and creator_id = '{$user_id}'");

                if ($update) {
                    $response = array('status' => 'success', 'message' => "Update successful");
                } else {
                    if (file_exists($audio_folder . $audio)) {
                        unlink($audio_folder . $audio);
                    }
                    $response = array('status' => 'error', 'message' => "Update failed");
                }
            }
        } else {
            $response = array('status' => 'error', 'message' => "Audio file already exists");
        }
    }
} else {
    $response = array('status' => 'error', 'message' => "Song ID not found");
}

header('Content-Type: application/json');
echo json_encode($response);
exit();
