<?php
session_start();
require "../backend/connection.php";

function getDateTimeDiff($date)
{
    $now_timestamp = strtotime(date(('Y-m-d H:i:s')));
    $diff_timestamp = $now_timestamp - strtotime($date);

    if ($diff_timestamp < 60) {
        return "few se";
    } elseif ($diff_timestamp >= 60 && $diff_timestamp < 3600) {
        return round($diff_timestamp / 60) . ' mins';
    } elseif ($diff_timestamp >= 3600 && $diff_timestamp < 86400) {
        return round($diff_timestamp / 3600) . ' hrs';
    } elseif ($diff_timestamp >= 86400 && $diff_timestamp < (86400 * 30)) {
        $round = round($diff_timestamp / 86400);
        if ($round == 1) {
            return round($diff_timestamp / 86400)  . ' day';
        } else {
            return round($diff_timestamp / 86400)  . ' days';
        }
    } elseif ($diff_timestamp >= (86400 / 30) && $diff_timestamp < (86400 * 365)) {
        $round = round($diff_timestamp / (86400 * 30));
        if ($round == 1) {
            return round($diff_timestamp / (86400 * 30)) . ' m';
        } else {
            return round($diff_timestamp / (86400 * 30)) . ' ms';
        }
    } else {
        $round = round($diff_timestamp / (86400 * 365));
        if ($round == 1) {
            return round($diff_timestamp / (86400 * 365)) . ' yr';
        } else {
            return round($diff_timestamp / (86400 * 365)) . ' yrs';
        }
    }
}

if (isset($_SESSION['uniqueID'])) {
    $auth_user = $_SESSION['uniqueID'];
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $resultsPerPage = isset($_GET['perPage']) ? $_GET['perPage'] : 20;
    $offset = ($page - 1) * $resultsPerPage;

    $query_block_ids = $conn->query("SELECT block_id FROM blocks WHERE blocker_id = '{$auth_user}'");

    if ($query_block_ids->num_rows > 0) {

        $query_blocked = $conn->query("SELECT * FROM blocks WHERE blocker_id = '{$auth_user}' ORDER BY blocked_on desc limit $offset, $resultsPerPage");

        while ($row = $query_blocked->fetch_assoc()) {
            $blocked_user_id = $row['blocked_id'];

            $query_user = $conn->query("SELECT * FROM users WHERE user_id = '{$blocked_user_id}' LIMIT 1");
            $row_user = $query_user->fetch_assoc();

            $blocked_since = getDateTimeDiff($row['blocked_on']);
            $blocked_user_name = $row_user['nickname'];

            $profile = $row_user['user_profile'];
            ($profile !== "" ? $profile = "../userprofiles/{$profile}" : $profile = "../images/profile.png");

?>
            <div class="box" id="<?= $blocked_user_id ?>">
                <a class="message">
                    <div class="inbox-msg">
                        <img src="<?= $profile ?>" alt="">

                        <div class="user-info">
                            <span><?= shortenTextName($blocked_user_name) ?></span>
                        </div>
                    </div>
                </a>

                <span class="text-light" style="width: 20%"><?= $blocked_since ?></span>

                <button type="button" title="unblock" class="unblocker btn no-btn-shadow" data-user-id="<?= $blocked_user_id ?>" onclick="callUnBlock(this)"><i class="material-icons">remove</i></button>
            </div>
        <?php
        }

        if ($query_blocked->num_rows > 0) {
        } else {
        ?>
            <p class="no-01 no-more text-center" style="font-size:larger; margin-bottom: 200px">No More blocked users</p>

        <?php
        }
    } else {
        ?>
        <p class="no-01 text-center mt-5" style="font-size:larger">No blocked user</p>

<?php
    }
}
