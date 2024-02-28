<?php
session_start();
require "connection.php";

$user_id = inputValidation($conn, $_POST['userid']);
$song_id = inputValidation($conn, $_POST['songid']);
$song_name = inputValidation($conn, $_POST['songname']);
$poster_id = inputValidation($conn, $_POST['posterid']);
$artist = inputValidation($conn, $_POST['artist']);

$ip = $_SERVER['REMOTE_ADDR'];

if (!empty($user_id) && !empty($song_id) && !empty($song_name) && !empty($artist) && !empty($poster_id)) {
    $query_exists = mysqli_query($conn, "SELECT * FROM playlists WHERE user_id = $user_id AND song_id = $song_id");
    // exit(mysqli_num_rows($query_exists));
    if (mysqli_num_rows($query_exists) <= 0) {

        $insert_query = mysqli_query($conn, "INSERT INTO playlists (user_id, song_id, song_name, poster_id, artist, ip_address) VALUES ({$user_id}, {$song_id}, '{$song_name}', {$poster_id}, '{$artist}', '{$ip}') LIMIT 1");

        // exit(var_dump($insert_query));

        if ($insert_query) {
            $query_adds = mysqli_query($conn, "SELECT * from playlists WHERE song_id = $song_id");

            $data_adds =   mysqli_fetch_assoc($query_adds);

            $number_of_adds = mysqli_num_rows($query_adds);
            $update_Adds = mysqli_query($conn, "UPDATE songs set adds = $number_of_adds where song_id = $song_id");

            if ($update_Adds) {
                exit("added to playlist");
            } else {
                exit("Not added to playlist");
            }
        } else {
            exit("failed to add to playlist");
        }
    } else {
        exit("You have already added into playlist");
    }
}
