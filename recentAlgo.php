<?php
function recentExpiration($request_date, $history_delete_id)
{
    global $conn;
    $start_date = $request_date;

    $active_days = 2;

    // calculating the expiration date
    $request_expire = "$start_date + $active_days days";

    $expire = date_create($request_expire);

    $today = new DateTime();

    $days_left = date_diff($expire, $today);
    $num_days = $days_left->days;

    if ($num_days > 0) {
    } else {
        mysqli_query($conn, "DELETE from history WHERE history_id = '{$history_delete_id}'");
    }
}
