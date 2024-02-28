<?php
require "../assets/access.php";

$admin_id = $data_admin['admin_id'];
$admin_name = strtolower($data_admin['nickname']);
$songname = strtolower(inputValidation($conn, $_POST['songname']));
$artist = strtolower(inputValidation($conn, $_POST['artist']));
$genre = inputValidation($conn, $_POST['genre']);
$agelimit = inputValidation($conn, $_POST['agelimit']);
$remix = inputValidation($conn, $_POST['remix']);
$thumbnail = $_POST['thumbnail'];
$audio = $_FILES['audio']['name'];

function generateFilename()
{
    global $admin_name;
    $filename = uniqid() . random_int(100, 9999);
    return $filename . $admin_name . '.' . 'jpg';
}

if (!empty($songname) && !empty($artist) && !empty($genre) && !empty($audio)) {
    $query_songname = $conn->query("SELECT * from songs WHERE song_name = '$songname' AND artist = '$artist' and remix = {$remix}")->num_rows;
    if ($query_songname < 1) {
        if (!isInDb($audio, 'audio_file', 'songs')) {
            if ($thumbnail !== 'data:,') {
                $thumbnail = base64_decode(str_replace('data:image/jpeg;base64,', '', $thumbnail));
                $image_name = generateFilename();

                $image_folder = '../../audio_thumbnails/';
                $audio_folder = '../../audios/';
                $audio_extension = strtolower(pathinfo($audio, PATHINFO_EXTENSION));
                $extensions = ["wav", "mp3"];
                if (in_array($audio_extension, $extensions)) {
                    if (move_uploaded_file($_FILES['audio']['tmp_name'], $audio_folder . $audio)) {
                        if (file_put_contents($image_folder . $image_name, $thumbnail)) {
                            $insert_song = mysqli_query($conn, "INSERT INTO songs (song_name, artist, admin_id, admin_name, thumbnail, audio_file, genre, age_limit, remix)
                             VALUES ('{$songname}', '{$artist}' ,'{$admin_id}','{$admin_name}', '{$image_name}', '{$audio}', '{$genre}', {$agelimit}, {$remix})");
                            if ($insert_song) {
                                $response = ['status' => 'success', 'message' => 'Upload successful'];
                            } else {
                                $response = ['status' => 'error', 'message' => 'Failed to upload fields into Emnet'];
                                if (file_exists($audio_folder . $audio)) {
                                    unlink($audio_folder . $audio);
                                }
                                if (file_exists($image_folder . $thumbnail)) {
                                    unlink($image_folder . $thumbnail);
                                }
                            }
                        } else {
                            $response = ['status' => 'error', 'message' => 'Failed to upload thumbnail'];
                        }
                    } else {
                        $response = ['status' => 'error', 'message' => 'Failed to upload audio'];
                    }
                } else {
                    $response = ['status' => 'error', 'message' => 'Only .wav and .mp3 files allowed'];
                }
            } else {
                $response = ['status' => 'error', 'message' => 'Thumbnail is empty'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Opps, looks like the audio file already exists'];
        }
    } else {
        $response = ['status' => 'error', 'message' => 'Opps, looks like the song already exists'];
    }
} else {
    $response = ['status' => 'error', 'message' => 'No field is allowed empty'];
}

outputInJSON($response);
