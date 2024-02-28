<?php
require "backend/connection.php";

$ip = $ip = $_SERVER['REMOTE_ADDR'];
$adID = mysqli_real_escape_string($conn, $_POST['ad_id']);

if(isset($_POST['adrep'])){
    $query_clicks = mysqli_query($conn, "SELECT * FROM clicks WHERE ip_address = '{$ip}'");

    if(mysqli_num_rows($query_clicks) > 0){

    }else{
        $insert_click = mysqli_query($conn, "INSERT INTO clicks (ad_id, ip_address) values ({$adID}, '{$ip}') LIMIT 1");

        $query_clicks_2 =  mysqli_query($conn, "SELECT * FROM clicks");

        $num_clicks = mysqli_num_rows($query_clicks_2);

        $update_ad = mysqli_query($conn, "UPDATE advert SET clicks = {$num_clicks} WHERE ad_id = $adID");
    }
}
