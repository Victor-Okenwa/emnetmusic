<?php
/*________________-_____________________|
# Denying a non-admin access if he is not an admin
|_________________-_____________________*/
session_start();
require "admin_conn.php";

if (isset($_SESSION['adminID'])) {
    $admin_id = $_SESSION['adminID'];
    $query_admin = $conn->query("SELECT * FROM admin where id = {$admin_id}");
    $data_admin = mysqli_fetch_assoc($query_admin);
    $admin_unique_id = $data_admin['admin_id'];
    $admin_name = $data_admin['nickname'];
    $admin_image = $data_admin['admin_profile'];
    $admin_rank = $data_admin['admin_rank'];

    if ($data_admin['block_tag'] == 1) {
        die("
        <body style='background: rgba(241, 45, 45, 0.925); font-family: Arial'>
        <h1 style='text-align: center; margin: 35vh 0 0 0' align='center'>!✋ You were blocked from accessing admin studio !</h2>
        </body>
        ");
    } else {
        $query_setting = mysqli_query($conn, "SELECT * from setting");
        $data_setting = mysqli_fetch_assoc($query_setting);

        function inputValidation($conn, $input)
        {
            $data = trim($input);
            $data = stripcslashes($data);
            $data = mysqli_real_escape_string($conn, $data);
            return $data;
        }
        function isInDb($field, $column, $table)
        {
            global $conn;
            $queryDb = $conn->query("SELECT $column from $table WHERE $column = '{$field}'")->num_rows;
            if ($queryDb > 0) return true;
            else return false;
        }

        function existsWith($field1, $field2, $column1, $column2, $table)
        {
            global $conn;
            $queryDb = $conn->query("SELECT $column1 from $table WHERE $column1 = '{$field1}' AND $column2 = '{$field2}'")->num_rows;
            if ($queryDb > 0) return true;
            else return false;
        }

        function outputInJSON($array)
        {
            header("Content-Type: application/json");
            echo json_encode($array);
        }


        function shortenDigits($numb)
        {
            if ($numb <= 999) {
                echo $numb;
            } elseif ($numb <= 999000) {
                echo floatval($numb / 1000) . "k";
            } elseif ($numb <= 999000000) {
                echo floatval($numb / 1000000) . "m";
            } elseif ($numb <= 999000000000) {
                echo floatval($numb / 1000000000) . "B";
            } elseif ($numb <= 999000000000000) {
                echo floatval($numb / 1000000000000) . "T";
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

        function suspensionExpiration($start_date, $period, $suspension, $creator_id)
        {
            global $conn;
            if ($suspension == 2) {
                $expire = "{$start_date} + {$period} days";
                $expire_period = date_create($expire);

                $today =  new DateTime();
                $num_days = date_diff($today, $expire_period);
                $num_days_day = $num_days->days;
                // var_export($num_days_day);
                if ($num_days_day > 0) {
                    echo ("{$num_days_day} days of suspension left");
                } else {
                    mysqli_query($conn, "UPDATE creators SET suspension = 0, period = 0 WHERE artist_id = $creator_id");
                }
            } elseif ($suspension == 1) {
                echo "Suspended indefinately";
            }
        }
    }
} else {
    header("location: ../index.php");
}
