<?php
session_start();
require 'connection.php';

$user_id = inputValidation($conn, $_POST['userid']);
$song_id = inputValidation($conn, $_POST['songid']);
$song_name = inputValidation($conn, $_POST['songname']);
$artist = inputValidation($conn, $_POST['artist']);

if (!empty($user_id) && !empty($song_id) && !empty($song_name) && !empty($artist)) {
    $query_delete = mysqli_query($conn, "DELETE FROM playlists WHERE user_id = $user_id AND song_id = $song_id");

    if ($query_delete) {
        $query_nums = mysqli_query($conn, "SELECT * FROM playlists WHERE song_id = $song_id");
        $nums = mysqli_num_rows($query_nums);

        $update_adds = mysqli_query($conn, "UPDATE songs SET adds = '$nums' where song_id = $song_id");

        if ($update_adds) {
            exit('removed from favourites');
        } else {
            exit('Failed to take update');
        }
    } else {
        exit('Failed to delete from playlists');
    }
}
