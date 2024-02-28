<?php

require "connection.php";
$userID = trim(mysqli_real_escape_string($conn, $_POST['userId']));
$artistID = trim(mysqli_real_escape_string($conn, $_POST['artistId']));
$artistName = trim(mysqli_real_escape_string($conn, $_POST['artistName']));
$userName = trim(mysqli_real_escape_string($conn, $_POST['userName']));

if(!empty($userID) && !empty($artistID) && !empty($artistName)){

    $query_exists = $conn->query("SELECT * FROM followers where user_id = '{$userID}' AND poster_id = '$artistID'");

    if($query_exists->num_rows < 1){

        $query_admin = $conn->query("SELECT * FROM admin WHERE admin_id = '$artistID'");
        $query_user = $conn->query("SELECT * FROM users WHERE user_id = '$artistID'");

        if($query_admin->num_rows > 0){
            $artist_type = 0;
        }else {
            $artist_type = 1;
        }

        $query_insert = $conn->query("INSERT INTO followers (user_id, poster_id, user_name, poster_name) VALUES('{$userID}', '{$artistID}', '{$userName}', '{$artistName}') LIMIT 1");

        $query_num_insert = $conn->query("SELECT * FROM followers WHERE poster_id = '{$artistID}'");
        $num_followers = $query_num_insert->num_rows; 
        
        if($query_insert && $artist_type == 0){
            $update = $conn->query("UPDATE admin set followers = {$num_followers} WHERE admin_id = '{$artistID}'");

            if($update){
                exit("success follow");
            }
        }elseif ($query_insert && $artist_type == 1) {
            $update = $conn->query("UPDATE users set followers = {$num_followers} WHERE user_id = '{$artistID}'");

            if($update){
                exit("success follow");
            }
        }
    }else {
        $query_admin = $conn->query("SELECT * FROM admin WHERE admin_id = '$artistID'");
        $query_user = $conn->query("SELECT * FROM users WHERE user_id = '$artistID'");

        if($query_admin->num_rows > 0){
            $artist_type = 0;
        }else {
            $artist_type = 1;
        }

        $query_delete = $conn->query("DELETE FROM followers WHERE user_id = '$userID' AND poster_id = '$artistID' LIMIT 1");

        $query_num_insert = $conn->query("SELECT * FROM followers WHERE poster_id = '{$artistID}'");
        $num_followers = $query_num_insert->num_rows; 

        if($query_delete && $artist_type == 0){
            $update = $conn->query("UPDATE admin set followers = {$num_followers} WHERE admin_id = '{$artistID}'");

            if($update){
                exit("success unfollow");
            }
        }elseif ($query_delete && $artist_type == 1) {
            $update = $conn->query("UPDATE users set followers = {$num_followers} WHERE user_id = '{$artistID}'");

            if($update){
                exit("success unfollow");
            }
        }
    }

}