<?php
session_start();
require "../backend/connection.php";

if (!isset($_SESSION['uniqueID']))
    header("Location: ../index.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=yes">
    <link rel="shortcut icon" sizes="392x6592" href="../logos/Emnet_Logo2.png" type="image/x-icon">
    <title>Emnet Inbox</title>
    
    <link rel="stylesheet" href="../css/all.css">
    <link rel="stylesheet" href="../css/boot4.css">
    <link rel="stylesheet" href="../css/fonts.css">
    <link rel="stylesheet" href="../iconfont/material-icons.css">
    <link rel="stylesheet" href="chat.css">

    <script src="../js/jquery3.6.0.js"></script>
    <script src="../js/popper1.160.min.js"></script>
    <script src="../js/bootstrap4.js"></script>

</head>

<body>

    <div class="wrapper">
        <div id="loader-clear" style="display: none;">
            <span class="spinner spinner-border"></span>
            <p>Clearing ...</p>
        </div>

        <?php
        include_once "../connectivity.php";
        $auth_id = $_SESSION['uniqueID'];
        $query_auth_user = $conn->query("SELECT * FROM users WHERE user_id = '{$auth_id}' ");

        if ($query_auth_user->num_rows < 1)
            header("Location: ../index.php");
        else
            $row = $query_auth_user->fetch_assoc();
        $auth_name = $row['nickname'];
        $auth_image = $row['user_profile'];
        ?>

        <div class="inbox-area">

            <header>
                <div class="user">
                    <img src="<?= $auth_image !== '' ? "../userprofiles/{$auth_image}" : "../images/profile.png"  ?>" alt="Profile">

                    <div class="details mk-abel">
                        <span><?= $auth_name ?></span>
                        <p>online</p>
                    </div>
                </div>

                <a href="/" class="back btn btn-dark" title="Back to home page"><i class="fa fa-home"></i></a>
            </header>

            <div class="main-area">
                <form class="search">
                    <input type="text" class="mk-roboto em-search-box" placeholder="Type to search...">
                    <button class="search-btn nullValue" type="button" title="Type to search"><i class="fas fa-search"></i></button>
                </form>

                <?php
                $query_auth_blocks = $conn->query("SELECT * FROM blocks WHERE blocker_id = '{$auth_id}'");
                if ($query_auth_blocks->num_rows > 0) {
                ?>
                    <div class="block-link">
                        <a href="./blocks.php" class="text-center">View blocked users</a>
                    </div>
                <?php
                }
                ?>
                <p id="loadermore" class="no-01 text-center" style="display:none; margin-bottom: 4px; color: #787 !important;">
                    Loading <span class='spinner-grow spinner-grow-sm'></span>
                </p>
                <div id="infoText" style="display:none; position:absolute; text-align: center; z-index: 15; width:90%; left:5%; top:15%; opacity: .8;" class="alert alert-info"></div>

                <div class="inbox">


                </div>

            </div>

        </div>
    </div>


    <script src="../connectivity.js"></script>
    <script src="./inbox.js"></script>
</body>

</html>