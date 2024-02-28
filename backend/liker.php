<?php
session_start();
require("connection.php");

$user_id = inputValidation($conn, $_POST['userid']);
$song_id = inputValidation($conn, $_POST['songid']);
$poster_id = inputValidation($conn, $_POST['posterid']);
$song_name = inputValidation($conn, $_POST['songname']);


if (!empty($user_id) && !empty($song_id) && !empty($poster_id) && !empty($song_name)) {

    $validation_check_user = $conn->query("SELECT * FROM users WHERE user_id = '{$user_id}' ");
    $validation_check_poster = $conn->query("SELECT * FROM songs WHERE creator_id = '{$poster_id}' OR admin_id = '{$user_id}'");
    $validation_check_song = $conn->query("SELECT * FROM songs WHERE song_id = '{$song_id}' AND song_name = '{$song_name}'");

    if ($validation_check_user->num_rows > 0 && $validation_check_poster->num_rows > 0 && $validation_check_song->num_rows > 0) {

        if (isset($_POST['like'])) {

            $sql_likes = $conn->query("SELECT * FROM likes WHERE user_id = '$user_id' AND poster_id = '$poster_id'");
            if ($sql_likes->num_rows > 0) {
                return;
            } else {

                $input_like = $conn->query("INSERT INTO likes (song_id, user_id, poster_id, song_name) VALUES($song_id, '$user_id', '$poster_id', '$song_name') LIMIT 1");

                if ($input_like) {
                    $sql_likes = $conn->query("SELECT * FROM likes WHERE user_id = '$user_id' AND poster_id = '$poster_id'");


                    $num_likes = $sql_likes->num_rows;

                    $update_likes = $conn->query("UPDATE songs SET likes = {$num_likes} WHERE song_id = {$song_id} AND song_name = '{$song_name}' ");
                    if ($update_likes) {
                        echo $num_likes;
                    }
                }
            }
        } elseif (isset($_POST['unlike'])) {

            $exists_query = $conn->query("SELECT * FROM likes WHERE user_id = '$user_id' AND poster_id = '$poster_id' ");

            if ($exists_query->num_rows > 0) {

                $delete_like = $conn->query("DELETE FROM likes WHERE user_id = '$user_id' AND song_id = $song_id");

                if ($delete_like) {
                    $sql_likes = $conn->query("SELECT * FROM likes WHERE user_id = '$user_id' AND poster_id = '$poster_id'");
                    $num_likes = $sql_likes->num_rows;

                    $update_likes = $conn->query("UPDATE songs SET likes = {$num_likes} WHERE song_id = {$song_id} AND song_name = '{$song_name}' LIMIT 1");
                    if ($update_likes) {
                        echo $num_likes;
                    }
                }
            }
        }
    }
}
