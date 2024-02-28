<?php
session_start();
require "./connection.php";
if (isset($_SESSION["uniqueID"])) {
    $auth_user = $_SESSION['uniqueID'];

    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    }

    if (isset($_POST['history'])) {
        $song_id = inputValidation($conn, $_POST['song_id']);
        $songname = inputValidation($conn, $_POST['song_name']);

        $query_history_list = mysqli_query($conn, "SELECT * FROM history WHERE user_id = '{$auth_user}' AND song_id = $song_id");

        if (mysqli_num_rows($query_history_list) < 1) {
            $insert_to_history = mysqli_query($conn, "INSERT INTO history (user_id, song_id, song_name) VALUES ('{$auth_user}', $song_id, '$songname')");
        } else {
            mysqli_query($conn, "DELETE FROM history WHERE user_id = $auth_user AND song_id = $song_id");
            mysqli_query($conn, "INSERT INTO history (user_id, song_id, song_name) VALUES ('{$auth_user}', $song_id, '$songname')");
        }

        $song_id = mysqli_real_escape_string($conn, $_POST['song_id']);

        $query_history_list = mysqli_query($conn, "SELECT * FROM streams WHERE song_id = $song_id AND ip_address = '$ip_address'");

        if (mysqli_num_rows($query_history_list) < 1) {
            $insert_to_streams = mysqli_query($conn, "INSERT INTO streams (song_id, ip_address) VALUES ($song_id, '$ip_address')");

            $query_streams = mysqli_query($conn, "SELECT * FROM streams WHERE song_id = $song_id");
            $num_streams = mysqli_num_rows($query_streams);

            $update_song_stream = $conn->query("UPDATE songs SET streams = $num_streams WHERE song_id = $song_id");
        }
    }

    if (isset($_POST['deleteHistory'])) {

        $query_history_del_list = mysqli_query($conn, "SELECT * FROM history WHERE user_id = '{$auth_user}'
        ");

        if (mysqli_num_rows($query_history_del_list) > 0) {
            mysqli_query($conn, "DELETE FROM history WHERE user_id = '{$auth_user}'");
        }
    }
}
