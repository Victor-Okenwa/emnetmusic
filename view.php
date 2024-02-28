<?php
session_start();
include_once "backend/connection.php";

// if (isset($_GET['song_id']) && $_GET['song_id'] !== "" && isset($_GET['artist']) && $_GET['artist'] !== "") {
//     $song_id = $_GET['song_id'];
//     $artist = $_GET['artist'];
// } else {
//     $song_id = "";
//     $artist = "";
// }

$page_url = explode("/", $_SERVER['PHP_SELF']);
// $song_id = $page_url[2] !== "view.php" ? $page_url[2] : $page_url[3];
$song_id = $_GET['song'];
$song_id = urldecode($song_id);
if (isInDb($song_id, "song_id", "songs")) {
    $query_songs = mysqli_query($conn, "SELECT * FROM songs Where song_id = $song_id");
} else {
    exit("<body style=\"background: black \">
    <h3 style=\"text-align:center; margin-top: 300px; color: white; font-family:Tahoma\">Bad request: Song not found or link no more supported</h3>
    </body>");
}

// if (isset($_SESSION["uniqueID"])) {
//     $query_playlists = mysqli_query($conn, "SELECT song_id FROM playlists WHERE user_id = '{$user_id}'");
// }



include "./recentAlgo.php";
?>

<!DOCTYPE html>
<!-- copy this IDENTIFICATION -->
<html lang="en" id="view-page">

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

<body class="paused">

    <!-- ----------------------- HEADER ------------------------ -->
    <?php require_once "navbars.php" ?>
    <!--x----------------------- HEADER ------------------------x-->
    <main id="main">
        <!-- advert -->
        <?php
        $query_ads = $conn->query("SELECT * FROM advert");

        if ($query_ads->num_rows > 0) {
            $row = $query_ads->fetch_assoc();
        ?>
            <div class="jumbotron-fluid em-ad">

                <a href="<?= $row['ad_link'] ?>" class="ad_interface" tartget="_blank" id="">
                    <div class="img">
                        <img src="./ceo 2.jpg" alt="">
                    </div>
                </a>
            </div>
        <?php
        }
        ?>

        <div class="music-layer" id="view_page">
            <form id="playlistForm">
                <input type="number" name="userid" class="userid_list" value="<?= trim(htmlspecialchars($unique_id)) ?>">
                <input type="number" name="songid" class="songid_list" value="">
                <input type="text" name="songname" class="songname_list" value="">
                <input type="text" name="posterid" class="poster_list" value="">
                <input type="text" name="artist" class="artist_list" value="">
            </form>

            <div class="all-songs mt-5">
                <div class="songs">
                    <?php
                    $query_songs = mysqli_query($conn, "SELECT * FROM songs WHERE song_id = {$song_id}");
                    $song_data = mysqli_fetch_assoc($query_songs);
                    ?>
                    <div class="song-item ambientDiv" id="<?= $song_data['song_id'] ?>">
                        <div class="img-play">
                            <img src="./audio_thumbnails/<?= $song_data['thumbnail'] ?>" alt="<?= $song_data['song_name'] ?>">
                            <i class="fa playlistPlay fa-play-circle" id="<?= $song_data['song_id'] ?>"></i>
                            <i class="fa fa-pause-circle"></i>
                            <audio class="audio-item" src="./audios/<?= $song_data['audio_file'] ?>"></audio>
                        </div>
                        <div class="mk-flex justify-content-between">
                            <h5>
                                <div class="song-name text-capitalize mk-abel"><?= shortenTextName($song_data['song_name']) ?></div>
                                <div class="artist text-capitalize"><?= shortenTextArtist($song_data['artist']) ?></div>
                            </h5>

                            <div class="xb42dee">
                                <?= "$domain/view.php?song=" . trim($song_data['song_id']) ?>
                            </div>

                            <div class="xb42dee2">
                                <?= "$domain/artist.php?artist=" . trim($song_data['admin_name'] == "" ? $song_data['creator_name'] : $song_data['admin_name']) ?>
                            </div>

                            <small class="text-light" style="font-size: 10px"><small class="fa fa-eye text-success"></small> <?= subNumber($song_data['streams'])  ?></small>
                        </div>
                        <?= ($song_data['admin_id'] != 0) ? "<span class='from-emnet'></span>" : "" ?>
                        <?= ($song_data['admin_id'] != 0) ? "<span class='poster_id'> " . $song_data['admin_id'] . " </span>" : "<span class='poster_id'>" . $song_data['creator_id'] . " </span>" ?>

                        <div class="genre"><?= $song_data['genre'] ?></div>
                    </div>

                </div>
            </div>

        </div>

    </main>

    <!--x----------------------- MAIN ------------------------x-->

    <!------------- Modals ---------->

    <?php include "modals.php" ?>

    <!------------- Modals ---------->
    </script>
    <!-- ----- jQuery(insert for web letter) ----- -->
    <script src="./js/jquery3.6.0.js"></script>

    <!-- ----- popper(insert for web letter) ----- -->
    <script src="./js/popper1.160.min.js"></script>

    <script src="./js/bootstrap4.js"></script>

    <!-- ----- custom ----- -->
    <script src="./js/slider.js"></script>
    <script src="./js/main.js"></script>



    <script>
        <?php if (isset($_SESSION['uniqueID'])) { ?>
            const toRecent = [...document.querySelectorAll('.song-item')];
            const metaID = document.querySelector("meta.user_id");
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

        <?php if (mysqli_num_rows($query_ads) > 0) { ?>
            const adInterface = document.querySelector(".ad_interface");
            adInterface.addEventListener("click", () => {
                let ad_id = adInterface.id;
                let ad_link = adInterface.href;
                alert(ad_link)
                $.ajax({
                    type: 'POST',
                    url: 'clickApi.php',
                    data: {
                        adrep: 1,
                        ad_id: ad_id,
                    }
                });
            });
        <?php } ?>
    </script>
</body>

</html>