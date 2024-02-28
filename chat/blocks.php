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
    <title>Emnet Blocks</title>
    <link rel="stylesheet" href="../css/all.css">
    <link rel="stylesheet" href="../css/boot4.css">
    <link rel="stylesheet" href="../css/fonts.css">
    <link rel="stylesheet" href="../iconfont/material-icons.css">
    <link rel="stylesheet" href="chat.css">

    <script src="../js/jquery3.6.0.js"></script>
    <script src="../js/bootstrap4.js"></script>
</head>

<body>

    <div class="wrapper">

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

        <div class="inbox-area block-page">

            <header>

                <div class="user">
                    <img src="<?= $auth_image !== '' ? "../userprofiles/{$auth_image}" : "../images/profile.png"  ?>" alt="Profile">

                    <div class="details mk-abel">
                        <span><?= $auth_name ?></span>
                        <p>online</p>
                    </div>
                </div>

                <a href="./inbox.php" class="back btn btn-dark" title="Back to inbox page"><i class="fa fa-long-arrow-alt-left"></i></a>
            </header>

            <div class="main-area position-relative">

                <form class="search">
                    <input type="text" class="mk-roboto em-search-box" placeholder="Type to search...">
                    <button class="search-btn" type="button"><i class="fas fa-search"></i></button>
                </form>
                <p id="loadermore" class="no-01 text-center" style="display:none; margin-bottom: 4px; color: #787 !important;">
                    Loading <span class='spinner-grow spinner-grow-sm'></span>
                </p>
                <div id="infoText" style="display:none;text-align: center; position: absolute; z-index: 15; width:90%; left:5%; top: 0; opacity: .8;" class="alert alert-danger"></div>

                <div class="inbox">

                </div>


            </div>

        </div>
    </div>

    <script src="./blocked.js"></script>
</body>

</html>