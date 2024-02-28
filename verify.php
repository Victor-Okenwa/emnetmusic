<?php
require_once './backend/connection.php';

function expirationTime($email, $initial_date, $valid_time)
{
    global $conn;
    $today = new DateTime();
    $initial_date = date_create("$initial_date + 3 days");
    $difference = date_diff($initial_date, $today)->days;

    if ($difference > $valid_time) {
        if (isInDb($email, 'email', 'unverified_users')) :
            $conn->query("DELETE from unverified_users where email = '$email' ");
            header("Location: signup.php");
        endif;
        echo "This account expired in $difference days(s) ago";
    } else {
        echo "This account expires in $difference days(s)";
    }
}

if (isset($_COOKIE['unverifed_id']) && !empty($_COOKIE['unverifed_id'])) {
    if (isset($_COOKIE['unverifed_email']) && !empty($_COOKIE['unverifed_email'])) {
        if (existsWith($_COOKIE['unverifed_id'], $_COOKIE['unverifed_email'], 'user_id', 'email', 'unverified_users')) {
            $user_id = $_COOKIE['unverifed_id'];
            $email = $_COOKIE['unverifed_email'];
            $account_date = $conn->query("SELECT acct_date from unverified_users WHERE email = '{$email}'")->fetch_assoc()['acct_date'];
        } else {
            header("location: login.php");
        }
    } else {
        header("location: login.php");
    }
} else {
    header("location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en" class="user_getting">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./logos/Emnet_Logo2.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/all.css">
    <link rel="stylesheet" href="./css/boot4.css">
    <link rel="stylesheet" href="./css/style.css">
    <title>Emnet music </title>
</head>

<body id="loginpage">
    <div id="loader-container">
        <div id="loader"></div>
    </div>
    <div class="wrapper bg-gray-300">
        <div class="text-center py-3">
            <i class="fa fa-envelope-open-text fa-2x"></i>
        </div>
        <div class="text-center p-2">
            <div id="output"></div>
            <h5 class="text-success mk-josefin">A verification code has been sent to <?= $_COOKIE['unverifed_email'] ?> </h5>
            <p class="text-capitalize mk-abel">Request will expire after <?= expirationTime($_COOKIE['unverifed_email'], $account_date, 3) ?></p>
        </div>

        <form id="tokenform">
            <label for="token"> Your token</label>
            <div class="mk-flex btn-icon-split">
                <input type="email" name="type" class="field_1" value="verify" hidden>
                <input type="email" name="email" class="field_1" value="<?= $_COOKIE['unverifed_email'] ?>" hidden>
                <input type="number" name="token" id="token" class="form-control" max="7" required>
            </div>
            <button type="button" class="btn btn-success" name="submitToken" id="submitToken">
                Verifiy email
                <span class="spinner spinner-grow spinner-grow-sm d-none"></span>
            </button>
        </form>

        <form id="resendForm">
            <input type="email" name="type" class="field_1" value="resend" hidden>
            <input type="number" name="user_id" class="field_1" value="<?= $_COOKIE['unverifed_id'] ?>" hidden>
            <input type="email" name="email" class="field_1" value="<?= $_COOKIE['unverifed_email'] ?>" hidden>
            <button type="button" name="resend" id="resend" class="btn btn-info d-block ml-auto">
                Resend <span class="spinner spinner-grow spinner-grow-sm d-none"></span>
            </button>
        </form>
    </div>
    <script src="./js/jquery3.6.0.js"></script>
    <script src="./js/login-signin.js"></script>


    <script>
        $(document).ready(() => {
            document.getElementById('submitToken').onclick = function() {
                sendToBackend(this, $('#tokenform'), 'backend/verifypage.php', 15000, document.getElementById('output'), 'login.php');
            }
        });

        $(document).ready(() => {
            document.getElementById('resend').onclick = function() {
                sendToBackend(this, $('#resendForm'), 'backend/verifypage.php', 15000, document.getElementById('output'), '');
            }
        });
    </script>

    
</body>
</html>