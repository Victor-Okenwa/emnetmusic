<?php

/*________________-_____________________|
# Here a creator can upload audio
* This is the API/Backend file
|_________________-_____________________*/

include "access.php";

$user_id = $fetch_user['user_id'];
$nickname = strtolower($fetch_user['nickname']);
$songname = strtolower(inputValidation($conn, $_POST['songname']));
$artist = strtolower(inputValidation($conn, $_POST['artist']));
$genre = inputValidation($conn, $_POST['genre']);
$agelimit = inputValidation($conn, $_POST['agelimit']);
$remix = inputValidation($conn, $_POST['remix']);
$thumbnail = $_POST['thumbnail'];
$audio = $_FILES['audio']['name'];

$response = [];
$userName = $_SESSION['nickname'];
function generateFilename($extension)
{
    global $userName;
    $filename = uniqid() . random_int(100, 9999);
    return $filename . $userName . '.' . $extension;
}

if (!empty($songname) && !empty($artist) && !empty($genre) && !empty($audio)) {

    $query_songname = $conn->query("SELECT * from songs WHERE song_name = '$songname' AND artist = '$artist' and remix = {$remix}");
    $query_audio = $conn->query("SELECT * FROM songs WHERE audio_file = '$audio' ");

    if ($query_songname->num_rows == 0) {

        if ($query_audio->num_rows == 0) {

            if ($thumbnail !== 'data:,') {
                $thumbnail = str_replace('data:image/jpeg;base64,', '', $thumbnail);
                $thumbnail = base64_decode($thumbnail);
                $imagename = generateFilename('jpg');


                $imagefolder = "../audio_thumbnails/";
                $audiofolder = "../audios/";

                $audio_extension = strtolower(pathinfo($audio, PATHINFO_EXTENSION));
                $extensions = ["wav", "mp3"];
                if (!in_array($audio_extension, $extensions)) {
                    $response = array('status' => 'error', 'message' => "Only MP3 and WAV files supported");
                    flush();
                } else {
                    if (file_exists($audiofolder . $audio)) {
                        $response = array('status' => 'error', 'message' => "The audio file sent already exists");
                        flush();
                    } else {
                        if (move_uploaded_file($_FILES['audio']['tmp_name'], $audiofolder . $audio)) {

                            if (file_put_contents($imagefolder . $imagename, $thumbnail)) {
                                $insert_song = mysqli_query($conn, "INSERT INTO songs (song_name, artist, creator_id, creator_name, thumbnail, audio_file, genre, age_limit, remix) values ('{$songname}', '{$artist}' ,'{$user_id}','{$nickname}', '{$imagename}', '{$audio}', '{$genre}', {$agelimit}, {$remix})");

                                if ($insert_song) {
                                    $sql_user_songs = mysqli_query($conn, "SELECT * FROM songs WHERE creator_id = '$user_id' AND creator_name = '$nickname'");

                                    $num_songs = mysqli_num_rows($sql_user_songs);

                                    mysqli_query($conn, "UPDATE creators SET songs = '{$num_songs}' where user_id = '$user_id'");

                                    $response = array('status' => 'success', 'message' => "Upload Successful");
                                } else {
                                    $response = array('status' => 'error', 'message' => "Upload Failed");
                                    if (file_exists($audiofolder . $audio)) {
                                        unlink($audiofolder . $audio);
                                    }
                                    if (file_exists($imagefolder . $thumbnail)) {
                                        unlink($imagefolder . $thumbnail);
                                    }
                                }
                            } else {
                                $response = array('status' => 'error', 'message' => "Thumbnail upload failed");
                                if (file_exists($imagefolder . $thumbnail)) {
                                    unlink($imagefolder . $thumbnail);
                                }
                                if (file_exists($audiofolder . $audio)) {
                                    unlink($audiofolder . $audio);
                                }
                            }
                        } else {
                            if (file_exists($audiofolder . $audio)) {
                                unlink($audiofolder . $audio);
                            }
                            $response = array('status' => 'error', 'message' => "Audio upload failed");
                        }
                    }
                }
            } else {
                $response = array('status' => 'error', 'message' => "Thumbnail is empty");
            }
        } else {
            $response = array('status' => 'error', 'message' => "Audio file alredy exists on emnet");
        }
    } else {
        $response = array('status' => 'error', 'message' => "Song and artist(s) name already taken");
    }
} else {
    $response = array('status' => 'error', 'message' => "No field is allowed to be empty");
}

header('Content-Type: application/json');
echo json_encode($response);
