<?php
require "../assets/access.php";
$songID = inputValidation($conn, $_POST['songid']);
$fiedType = inputValidation($conn, $_POST['type']);

if (isset($_POST['value'])) {
    $value = strtolower(inputValidation($conn, $_POST['value']));
}

function updateField($field, $value)
{
    global $conn, $songID, $admin_unique_id;
    $update = $conn->query("UPDATE songs SET $field = '{$value}' WHERE song_id = {$songID} and admin_id = '$admin_unique_id'");
    if ($update) return true;
    else return false;
}

if (isInDb($songID, 'song_id', 'songs')) {
    if ($fiedType == 'songname') {
        if (updateField('song_name', $value)) {
            $response = ['status' => 'success', 'message' => "Updated song name"];
        } else {
            $response = ['status' => 'error', 'message' => "Song name update failed"];
        }
    } elseif ($fiedType == 'artist') {
        if (updateField('artist', $value)) {
            $response = ['status' => 'success', 'message' => "Updated artist name"];
        } else {
            $response = ['status' => 'error', 'message' => "Artist name update failed"];
        }
    } elseif ($fiedType == 'genre') {
        if (updateField('genre', $value)) {
            $response = ['status' => 'success', 'message' => "Updated genre"];
        } else {
            $response = ['status' => 'error', 'message' => "Genre update failed"];
        }
    } elseif ($fiedType == 'limits') {
        $agelimit = inputValidation($conn, $_POST['ageLimit']);
        $remix = inputValidation($conn, $_POST['remix']);

        $updateAgeLimit = updateField('age_limit', $agelimit);
        $updateRemix = updateField('remix', $remix);
        if ($updateAgeLimit && $updateRemix) {
            $response = ['status' => 'success', 'message' => "Updated Age limit and remix"];
        } else {
            $response = ['status' => 'error', 'message' => "Age limit and remix update failed"];
        }
    } elseif ($fiedType == 'image') {

        function generateFilename()
        {
            global $admin_name;
            $filename = uniqid() . random_int(100, 9999);
            return $filename . $admin_name . '.' . 'jpg';
        }

        if ($value !== 'data:,') {
            $thumbnail = base64_decode(str_replace('data:image/jpeg;base64,', '', $value));
            $image_name = generateFilename();
            $image_folder = '../../audio_thumbnails/';
            if (file_put_contents($image_folder . $image_name, $thumbnail)) {
                if (updateField('thumbnail', $image_name)) {
                    $response = ['status' => 'success', 'message' => '<span class="text-success">Update taken</span>'];
                } else {
                    if (file_exists($image_folder . $thumbnail)) {
                        unlink($image_folder . $thumbnail);
                    }
                    $response = ['status' => 'error', 'message' => 'Thumbnail update failed'];
                }
            } else {
                $response = ['status' => 'error', 'message' => 'Thumbnail upload failed'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Thumbnail is empty'];
        }
    } elseif ($fiedType == 'audio') {
        $audio = $_FILES['audio'];
        $audio_name = $_FILES['audio']['name'];
        $audio_folder = '../../audios/';

        if (!isInDb($audio_name, 'audio_file', 'songs')) {
            $query_audios = $conn->query("SELECT audio_file FROM songs WHERE song_id = {$songID} AND admin_id = '$admin_unique_id'");
            $previous_audio = $query_audios->fetch_assoc()['audio_file'];

            if (is_file($audio_folder . $previous_audio)) {
                unlink($audio_folder . $previous_audio);
            }

            if (move_uploaded_file($_FILES['audio']['tmp_name'], $audio_folder . $audio_name)) {
                if (updateField('audio_file', $audio_name)) {
                    $response = ['status' => 'success', 'message' => 'Audio updated'];
                } else {
                    if (is_file($audio_folder . $audio_name)) {
                        unlink($audio_folder . $audio_name);
                    }
                    $response = ['status' => 'error', 'message' => 'Audio update failed'];
                }
            } else {
                $response = ['status' => 'error', 'message' => 'Audio upload failed'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Looks like the audio file exists on emnet'];
        }
    } else {
        $response = ['status' => 'error', 'message' => 'Request not found'];
    }
} else {
    $response = ['status' => 'error', 'message' => 'Song does not exist'];
}
outputInJSON($response);
