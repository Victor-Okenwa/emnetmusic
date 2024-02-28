<?php
require "../assets/access.php";
if ($admin_rank == 'super admin') {
    $deleteID = inputValidation($conn, $_POST['delete-id']);

    if (isInDb($deleteID, 'id', 'admin')) {
        $query_admin = mysqli_query($conn, "SELECT * FROM admin WHERE id = $deleteID");
        $data_admin_delete = mysqli_fetch_assoc($query_admin);

        if (file_exists("./admin_profile/" . $data_admin_delete['admin_profile'])) {
            unlink("./admin_profile/" . $data_admin_delete['admin_profile']);
        }

        $query_posts = mysqli_query($conn, "SELECT * FROM songs WHERE admin_id = {$data_admin_delete['admin_id']}");
        $num_posts = mysqli_num_rows($query_posts);
        if ($num_posts > 0) {
            while ($rows_posts = mysqli_fetch_assoc($query_posts)) {
                if (file_exists("../../audio_thumbnails" . $rows_posts['thumbnail'])) {
                    unlink("../../audio_thumbnails" . $rows_posts['thumbnail']);
                }
                if (file_exists("../../audios" . $rows_posts['audio_file'])) {
                    unlink("../../audios" . $rows_posts['audio_file']);
                }
                $query_del_history = mysqli_query($conn, "DELETE from history WHERE song_name = '{$rows_posts['song_name']}' and song_id = {$rows_posts['song_id']}");
                $query_del_playlists = mysqli_query($conn, "DELETE from playlists WHERE poster_id = {$rows_posts['admin_id']}");
            }
            $query_del_posts = mysqli_query($conn, "DELETE from songs WHERE admin_id = {$data_admin_delete['admin_id']}");
        }
        $query_delete = mysqli_query($conn, "DELETE FROM admin WHERE id = $deleteID");

        if ($query_delete) {
            $response = ['status' => 'success', 'message' => "Admin Deleted"];
        } else {
            $response = ['status' => 'error', 'message' => "Failed to delete"];
        }
    } else {
        $response = ['status' => 'error', 'message' => "Admin not found"];
    }
} else {
    $response = ['status' => 'error', 'message' => "Request blocked"];
}
outputInJSON($response);
