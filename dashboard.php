<?php
session_start();
include_once "backend/connection.php";


if (isset($_SESSION["uniqueID"])) {
    $unique_id = $_SESSION['uniqueID'];

    $query_user = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '{$_SESSION["uniqueID"]}' ");

    if (mysqli_num_rows($query_user) > 0) {
        $fetch_user = mysqli_fetch_assoc($query_user);
        $userid = $fetch_user['user_id'];
        $email = $fetch_user['email'];
        $nickname = $fetch_user['nickname'];
        $_SESSION['nickname'] = $fetch_user['nickname'];
        $img = $fetch_user['user_profile'];
    }
}

include "./recentAlgo.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=top">
    <meta id="<?= isset($_SESSION['uniqueID']) ? $_SESSION['uniqueID'] : "" ?>" class="user_id">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=yes">
    <link rel="shortcut icon" sizes="392x6592" href="./logos/Emnet_Logo2.png" type="image/x-icon">

    <!-- -------------- Font awesome REM(insert for web letter) --------- -->
    <link rel="stylesheet" href="./css/all.css">

    <!-- -------------- Google material icons REM(insert for web letter) --------- -->
    <link rel="stylesheet" href="./iconfont/material-icons.css">


    <!-- -------------- boostrap REM(insert for web letter) --------- -->
    <link rel="stylesheet" href="./css/boot4.css">

    <!-- -------------- custom --------- -->
    <link rel="stylesheet" href="./css/style.css">

    <title>Emnet music</title>
</head>

<body class="paused" id="dashboard">

    <!-- ----------------------- HEADER ------------------------ -->
    <?php require "./navbars.php" ?>
    <!--x----------------------- HEADER ------------------------x-->


    <!-- ----------------------- MAIN ------------------------ -->
    <main id="main">
        <div class="music-layer">

            <form id="playlistForm">
                <input type="number" name="userid" class="userid_list" value="<?= trim(htmlspecialchars($unique_id)) ?>">
                <input type="number" name="songid" class="songid_list" value="">
                <input type="text" name="songname" class="songname_list" value="">
                <input type="text" name="posterid" class="poster_list" value="">
                <input type="text" name="artist" class="artist_list" value="">
            </form>

            <?php if (isset($_SESSION["uniqueID"])) {
                $query_history_nums = mysqli_query($conn, "SELECT * FROM history WHERE user_id = {$_SESSION['uniqueID']}");
                if (mysqli_num_rows($query_history_nums) > 0) { ?>
                    <div class="recent-playlist tps">

                        <div class="head-list">
                            <h3 class="text-light">Recent <small id="clear_history" class="material-icons" style=" cursor: pointer" title="Clear my history">delete</small></h3>
                            <div class="scroll-buttons d-none d-lg-block d-md-block">
                                <button class="btn slide-left" id="recentscrollL"><i class="fa fa-angle-left"></i></button>
                                <button class="btn slide-right" id="recentscrollR"><i class="fa fa-angle-right"></i></button>
                            </div>
                        </div>

                        <div class="songs mk-flex">

                            <div class="song-list">
                                <?php $query_history_songs = mysqli_query($conn, "SELECT * FROM history WHERE user_id = '$userid' order BY history_id desc Limit 15");
                                while ($history_data = mysqli_fetch_assoc($query_history_songs)) {
                                    $query_history = mysqli_query($conn, "SELECT * FROM songs WHERE song_id = {$history_data['song_id']}");
                                    $fetch_history_join = mysqli_fetch_assoc($query_history);

                                    recentExpiration($history_data['date'], $history_data['history_id']);

                                ?>
                                    <div class="song-item ambientDiv" id="<?= $history_data['song_id'] ?>">
                                        <div class="img-play">
                                            <img src="./audio_thumbnails/<?= $fetch_history_join['thumbnail'] ?>" alt="">
                                            <i class="fa playlistPlay fa-play-circle"></i>
                                            <i class="fa fa-pause-circle"></i>
                                            <audio class="audio-item" src="./audios/<?= $fetch_history_join['audio_file'] ?>"></audio>

                                        </div>
                                        <h5>
                                            <div class="song-name"><?= $fetch_history_join['song_name'] ?></div>
                                            <div class="artist"><?= $fetch_history_join['artist'] ?></div>
                                        </h5>

                                        <div class="xb42dee">
                                            <?php echo "$domain/view.php?song=" . trim($fetch_history_join['song_id']) ?> </div>

                                        <div class="xb42dee2">
                                            <?= "$domain/artist.php?artist=" . trim($fetch_history_join['admin_name'] == "" ? $fetch_history_join['creator_name'] : $fetch_history_join['admin_name']) ?>
                                        </div>

                                        <?= ($fetch_history_join['admin_id'] != 0) ? "<span class='poster_id'> " . $fetch_history_join['admin_id'] . " </span>" : "<span class='poster_id'>" . $fetch_history_join['creator_id'] . " </span>" ?>
                                        <?= ($fetch_history_join['admin_id'] != 0) ? "<span class='from-emnet'></span>" : "" ?>
                                    </div>
                                <?php
                                }
                                ?>


                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <br>

                    <div class="followings">
                        <h4 class="text-light">Following artists</h4>

                        <div class="following-artists ambientDiv">

                            <?php
                            $query_following = mysqli_query($conn, "SELECT * FROM followers WHERE user_id = '$unique_id'");
                            $num_following = $query_following->num_rows;

                            while ($follow_data = $query_following->fetch_assoc()) {
                                $artist_id = $follow_data['poster_id'];
                                $query_artist1 = $conn->query("SELECT * from users WHERE user_id = '{$artist_id}' ");
                                $query_artist2 = $conn->query("SELECT * from admin WHERE admin_id = '{$artist_id}' ");


                                if ($query_artist1->num_rows > 0) {
                                    $fetch_info = $query_artist1->fetch_assoc();

                                    $artist_fname = $fetch_info['firstname'];
                                    $artist_lname = $fetch_info['surname'];
                                    $artist_oname = $fetch_info['othername'];
                                    $artist_nname = $fetch_info['nickname'];
                                    $artist_email = $fetch_info['email'];
                                    $artist_nation = $fetch_info['country'];
                                    $artist_gender = $fetch_info['gender'];
                                    $artist_img = $fetch_info['user_profile'];

                                    $artist_type = true;
                                } else {
                                    $fetch_info = $query_artist2->fetch_assoc();

                                    $artist_fname = $fetch_info['firstname'];
                                    $artist_lname = $fetch_info['lastname'];
                                    $artist_nname = $fetch_info['nickname'];
                                    $artist_email = $fetch_info['email'];
                                    $artist_img = $fetch_info['admin_profile'];

                                    $artist_type = false;
                                }
                            ?>

                                <a href="<?= "$domain/artist.php?artist=" . trim($follow_data['admin_name'] == "" ? $follow_data['creator_name'] : $follow_data['admin_name']) ?>" class="artist-profile">
                                    <div class="artist-img">
                                        <?php

                                        if ($artist_img != "") {  ?>
                                            <img src="<?= $artist_type ? "./userprofiles/{$artist_img}" : "./admin/adminprofile/{$artist_img}" ?>" alt="<?= $artist_nname ?>">
                                        <?php } else {
                                        ?>

                                            <img src="images/profile.png" alt="<?= $artist_nname ?>">
                                        <?php
                                        } ?>
                                    </div>

                                    <div class="artist-info">
                                        <h5 class="text-light">
                                            <div class="text-capitalize">
                                                Name:
                                                <?= $artist_lname . " " . $artist_fname . " " . ($artist_type ? $artist_oname : "")  ?>
                                            </div>
                                            <div class="text-capitalize">
                                                Artist: <?= $artist_nname ?>
                                            </div>
                                        </h5>
                                    </div>
                                </a>

                            <?php
                            }
                            ?>
                        </div>
                    </div>


            <?php  }
            } ?>

        </div>
    </main>

    <?php include "modals.php" ?>

    <!------------- Modals ---------->

    <!-- ----- jQuery(insert for web letter) ----- -->
    <script src="./js/jquery3.6.0.js"></script>

    <!-- ----- popper(insert for web letter) ----- -->
    <script src="./js/popper1.160.min.js"></script>

    <!-- ----- Owl carousel(insert for web letter) ----- -->

    <!-- ----- boostrap(insert for web letter) ----- -->
    <script src="./js/bootstrap4.js"></script>

    <!-- ----- custom ----- -->
    <script src="./js/slider.js"></script>
    <script src="./js/main.js"></script>



    <script>
        <?php if (isset($_SESSION['uniqueID'])) { ?>
            const toRecent = [...document.querySelectorAll('.song-item')];
            const clear_history = document.getElementById("clear_history");


            toRecent.forEach((item) => {
                item.addEventListener("click", () => {
                    $.ajax({
                        type: 'POST',
                        url: 'backend/toRecent.php',
                        data: {
                            history: 1,
                            song_id: item.id,
                            song_name: item.querySelector(".song-name").textContent,
                        },
                    });
                })
            });


            clear_history.addEventListener("click", () => {
                $.ajax({
                    type: 'POST',
                    url: 'backend/toRecent.php',
                    data: {
                        deleteHistory: 1,
                    },
                    success: function(data) {
                        window.location = window.location;
                    }
                });
            });
        <?php } ?>
    </script>
</body>

</html>