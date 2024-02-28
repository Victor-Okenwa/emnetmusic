<?php
session_start();
require "../backend/connection.php";

$user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
$nickname = mysqli_real_escape_string($conn, $_POST['nickname']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$ip = $_SERVER['REMOTE_ADDR'];

if (!empty($user_id) && !empty($nickname) && !empty($email)) {
    $query_before = mysqli_query($conn, "SELECT * FROM requests WHERE user_id = '$user_id'");
    $query_setting = mysqli_query($conn, "SELECT * FROM setting");
    $fetch_setting = mysqli_fetch_assoc($query_setting);
    $auto_val = $fetch_setting['auto_acct'];

    if (mysqli_num_rows($query_before) < 1 && $auto_val == 0) {
        $insert_query = mysqli_query($conn, "INSERT INTO requests (user_id, user_name, email, ip_address, status) 
    VALUES ('{$user_id}', '{$nickname}', '{$email}', '{$ip}', '{0}')");

        if ($insert_query) {
            exit("Your request has been received!");
        } else {
            exit("Failed to send request!");
        }
    } elseif(mysqli_num_rows($query_before) < 1 && $auto_val == 1){
         $insert_query = mysqli_query($conn, "INSERT INTO creators (user_id, nickname, email) 
    VALUES ('{$user_id}', '{$nickname}', '{$email}')");

        if ($insert_query) {
            exit("Your request has been received!");
        } else {
            exit("Failed to send request!");
        }
    } else {
        exit("You have already sent a request or your are not logged in");
    }
}
