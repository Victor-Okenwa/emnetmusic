<?php
require "access.php";
$delete_id = $conn->real_escape_string($_POST['delete-id']);

$unique_id = $unique_id;


if (!empty($delete_id)) {
    $query = $conn->query("SELECT * from songs where song_id = $delete_id");

    $row = $query->fetch_array();

    $thumbnail = "../audio_thumbnails/" . $row['thumbnail'] . "";
    $audio = "../audios/" . $row['audio_file'] . " ";

    if (mysqli_num_rows($query) > 0) {
        if (file_exists($thumbnail)) {
            unlink($thumbnail);
            if (unlink($thumbnail)) {
            }
        }

        if (file_exists($audio)) {
            unlink($audio);
            if (unlink($audio)) {
            }
        }

        $delete_query = $conn->query("DELETE FROM songs WHERE song_id = $delete_id and creator_id = $unique_id");
        $delete_query2 = $conn->query("DELETE FROM history WHERE song_id = $delete_id");
        $delete_query3 = $conn->query("DELETE FROM playlists WHERE song_id = $delete_id");
        $sql_user_songs = mysqli_query($conn, "SELECT * FROM songs WHERE creator_id = $unique_id");
        $num_songs = mysqli_num_rows($sql_user_songs);
        mysqli_query($conn, "UPDATE creators SET songs = '{$num_songs}' where user_id = $unique_id");
        exit("Delete Successful");
    }
} else {
    exit("Input is empty");
}
