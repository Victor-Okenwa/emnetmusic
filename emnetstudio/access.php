<?php
session_start();
include_once "../backend/connection.php";
if (isset($_SESSION["uniqueID"])) {
    $query_request = mysqli_query($conn, "SELECT * FROM requests WHERE user_id = '{$_SESSION["uniqueID"]}' ");
    // $query_all_songs = mysqli_query($conn, "SELECT * FROM requests WHERE user_id = '{$_SESSION["uniqueID"]}' ");
    $nums_request = mysqli_num_rows($query_request);
    if ($nums_request > 0) {
        $fetch_request = mysqli_fetch_assoc($query_request);
    } else {
        $fetch_request['status'] = "";
    }
    $query_creator = mysqli_query($conn, "SELECT * FROM creators WHERE user_id = '{$_SESSION["uniqueID"]}' ");
    $nums_creators = mysqli_num_rows($query_creator);

   if($nums_creators > 0){
    $data_creator = mysqli_fetch_assoc($query_creator);
        if ($data_creator['suspension'] !== 0 && $nums_request > 0) {
            function suspensionExpiration($start_date, $suspension, $period)
            {
                if ($suspension == 2) {
                    $expire = "{$start_date} + {$period} days";
                    $expire_period = date_create($expire);
                    $today =  new DateTime();
                    $num_days = date_diff($today, $expire_period);
                    $num_days_day = $num_days->days;
                    if ($num_days_day > 0) {
                        die("
                        <body style='background: rgba(231, 115, 54, 0.935); font-family: Arial; width: 100%; height: 100%; overflow-x: hidden !important; box-sizing: border-box !important; display:flex; flex-direction: column; justify-content: center'>
                        <h2 style='text-align: center; margin: 15vh 0 0 0' align='center'>💄 You were suspended from accessing emnet studio 💄</h2>
                        <h3 style='text-align: center; ' align='center'>Status:  {$num_days_day} days of suspension left</h3>
                        </body>
                            ");
                    } else {
                    }
                } elseif ($suspension == 1) {
                    die("
                        <body style='background: rgba(231, 115, 54, 0.935); font-family: Arial; width: 100%; height: 100%; overflow-x: hidden !important; box-sizing: border-box !important; display:flex; flex-direction: column; justify-content: center'>
                        <h2 style='text-align: center; margin: 15vh 0 0 0' align='center'>💄 You were suspended from accessing emnet studio 💄</h2>
                        <h3 style='text-align: center; ' align='center'>Status: Suspended indefinately </h3>
                        </body>
                        ");
                }
            }
            if($nums_creators > 0){
                suspensionExpiration($data_creator['created_on'], $data_creator['suspension'], $data_creator['period']);
            }
        }
    }

    $query_user = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '{$_SESSION["uniqueID"]}' ");
    if (mysqli_num_rows($query_user) > 0) {
        $fetch_user = mysqli_fetch_assoc($query_user);
        $userid = $fetch_user['user_id'];
        $unique_id = $fetch_user['user_id'];
        $email = $fetch_user['email'];
        $nickname = $fetch_user['nickname'];
        $_SESSION['nickname'] = $fetch_user['nickname'];
        $img = $fetch_user['user_profile'];
    }


    function requestExpiration($request_date)
    {
        $start_date = $request_date;
        $active_days = 30;
        // calculating the expiration date
        $request_expire = "$start_date + $active_days days";
        $expire = date_create($request_expire);

        $today = new DateTime();

        $days_left = date_diff($expire, $today);
        $num_days = $days_left->days;

        if ($num_days > 0) {
            echo "<p class='text-danger' style='font-weight: 800'> Your access expires in {$num_days} days </p>";
        }
    }

function getDateTimeDiff($date)
{
    $now_timestamp = strtotime(date(('Y-m-d H:i:s')));
    $diff_timestamp = $now_timestamp - strtotime($date);

    if ($diff_timestamp < 60) {
        return "few seconds ago";
    } elseif ($diff_timestamp >= 60 && $diff_timestamp < 3600) {
        return round($diff_timestamp / 60) . ' mins ago';
    } elseif ($diff_timestamp >= 3600 && $diff_timestamp < 86400) {
        return round($diff_timestamp / 3600) . ' hours ago';
    } elseif ($diff_timestamp >= 86400 && $diff_timestamp < (86400 * 30)) {
        $round = round($diff_timestamp / 86400);
        if ($round == 1) {
            return round($diff_timestamp / 86400)  . ' day ago';
        } else {
            return round($diff_timestamp / 86400)  . ' days ago';
        }
    } elseif ($diff_timestamp >= (86400 / 30) && $diff_timestamp < (86400 * 365)) {
        $round = round($diff_timestamp / (86400 * 30));
        if ($round == 1) {
            return round($diff_timestamp / (86400 * 30)) . ' month ago';
        } else {
            return round($diff_timestamp / (86400 * 30)) . ' months ago';
        }
    } else {
        $round = round($diff_timestamp / (86400 * 365));
        if ($round == 1) {
            return round($diff_timestamp / (86400 * 365)) . ' year ago';
        } else {
            return round($diff_timestamp / (86400 * 365)) . ' years ago';
        }
    }
}
} else {
}
