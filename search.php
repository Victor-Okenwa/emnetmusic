<?php
session_start();
include_once "backend/connection.php";

$unique_id = "";

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
$query_songs = mysqli_query($conn, "SELECT * FROM songs order BY rand() desc");

include "./recentAlgo.php";

?>
<!DOCTYPE html>
<html lang="en" id="playlistPage">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta id="<?= isset($_SESSION['uniqueID']) ? $_SESSION['uniqueID'] : "" ?>" class="user_id">
    <link rel="shortcut icon" href="./logos/Emnet_Logo2.png" type="image/x-icon">


    <!-- -------------- Font awesome REM(insert for web letter) --------- -->
    <link rel="stylesheet" href="./css/all.css">

    <!-- -------------- Google material icons REM(insert for web letter) --------- -->
    <link rel="stylesheet" href="./iconfont/material-icons.css">

    <!-- -------------- owl carousel REM(insert for web letter) --------- -->

    <!-- -------------- boostrap REM(insert for web letter) --------- -->
    <link rel="stylesheet" href="./css/boot4.css">

    <!-- -------------- custom --------- -->
    <link rel="stylesheet" href="./css/style.css">

    <title>Emnet music</title>
</head>

<body class="paused">

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


            <div class="all-songs">

                <div class="songs">
                    <?php

                    if (isset($_POST['searchBtn'])) {
                        $selection = mysqli_real_escape_string($conn, $_POST['selection']);
                        $search = mysqli_real_escape_string($conn, $_POST['search']);

                        $output = "";

                        if ($selection == "") {

                            $search_sql = mysqli_query($conn, "SELECT * from songs WHERE song_name like '%$search%' order by rand() desc ");

                            while ($row = mysqli_fetch_assoc($search_sql)) {
                    ?>
                                <div class="song-item ambientDiv" id="<?= $row['song_id'] ?>">
                                    <div class='img-play'>
                                        <img src='./audio_thumbnails/<?= $row['thumbnail'] ?>'>
                                        <i class='fa playlistPlay fa-play-circle'></i>
                                        <i class='fa fa-pause-circle'></i>
                                        <audio class='audio-item' src='./audios/<?= $row['audio_file'] ?>'></audio>
                                    </div>
                                    <div class="mk-flex justify-content-between">
                                        <h5>
                                            <div class="song-name text-capitalize"><?= shortenTextName($row['song_name']) ?></div>
                                            <div class="artist text-capitalize"><?= shortenTextArtist($row['artist']) ?></div>
                                        </h5>


                                        <div class="xb42dee">
                                            <?php echo "$domain/view.php?song=" . trim($row['song_id']) ?> </div>

                                        <div class="xb42dee2">
                                            <?= "$domain/artist.php?artist=" . trim($row['admin_name'] == "" ? $row['creator_name'] : $row['admin_name']) ?>
                                        </div>

                                        <small class="text-light" style="font-size: 10px"><small class="fa fa-eye text-success"></small> <?= subNumber($row['streams'])  ?></small>
                                    </div>
                                    <?= ($row['admin_id'] != 0) ? "<span class='from-emnet'></span>" : "" ?>
                                    <?= ($row['admin_id'] != 0) ? "<span class='poster_id'> " . $row['admin_id'] . " </span>" : "<span class='poster_id'>" . $row['creator_id'] . " </span>" ?>

                                </div>
                        <?php
                            }
                        }
                        ?>

                        <?php
                        if ($selection == "artist") {

                            $search_sql = mysqli_query($conn, "SELECT * from songs WHERE artist like '%$search%' order by rand() desc ");

                            while ($row = mysqli_fetch_assoc($search_sql)) {
                        ?>

                                <div class='song-item ambientDiv' id='<?= $row['song_id'] ?>'>
                                    <div class='img-play'>
                                        <img src='./audio_thumbnails/<?= $row['thumbnail'] ?>'>
                                        <i class='fa playlistPlay fa-play-circle'></i>
                                        <i class='fa fa-pause-circle'></i>
                                        <audio class='audio-item' src='./audios/<?= $row['audio_file'] ?>'></audio>
                                    </div>

                                    <h5>
                                        <div class='song-name'><?= $row['song_name'] ?></div>
                                        <div class='artist'><?= $row['artist'] ?></div>
                                    </h5>

                                    <div class="xb42dee">
                                        <?php echo "$domain/view.php?song=" . trim($row['song_id']) ?> </div>

                                    <div class="xb42dee2">
                                        <?= "$domain/artist.php?artist=" . trim($row['admin_name'] == "" ? $row['creator_name'] : $row['admin_name']) ?>
                                    </div>

                                    <?= ($row['admin_id'] != 0) ? "<span class='from-emnet'></span>" : "" ?>
                                    <?= ($row['admin_id'] != 0) ? "<span class='poster_id'> " . $row['admin_id'] . " </span>" : "<span class='poster_id'>" . $row['creator_id'] . " </span>" ?>
                                </div>
                        <?php
                            }
                        }
                        ?>

                        <?php
                        if ($selection == "genre") {

                            $search_sql = mysqli_query($conn, "SELECT * from songs WHERE genre like '%$search%' order by rand() desc ");

                            while ($row = mysqli_fetch_assoc($search_sql)) {
                        ?>

                                <div class='song-item ambientDiv' id='<?= $row['song_id'] ?>'>
                                    <div class='img-play'>
                                        <img src='./audio_thumbnails/<?= $row['thumbnail'] ?>'>
                                        <i class='fa playlistPlay fa-play-circle'></i>
                                        <i class='fa fa-pause-circle'></i>
                                        <audio class='audio-item' src='./audios/<?= $row['audio_file'] ?>'></audio>
                                    </div>

                                    <h5>
                                        <div class='song-name'><?= $row['song_name'] ?></div>
                                        <div class='artist'><?= $row['artist'] ?></div>
                                    </h5>

                                    <div class="xb42dee">
                                        <?= "$domain/view.php?song=" . trim($row['song_id']) ?>
                                    </div>

                                    <div class="xb42dee2">
                                        <?= "$domain/artist.php?artist=" . trim($row['admin_name'] == "" ? $row['creator_name'] : $row['admin_name']) ?>
                                    </div>

                                    <?= ($row['admin_id'] != 0) ? "<span class='from-emnet'></span>" : "" ?>
                                    <?= ($row['admin_id'] != 0) ? "<span class='poster_id'> " . $row['admin_id'] . " </span>" : "<span class='poster_id'>" . $row['creator_id'] . " </span>" ?>
                                </div>
                            <?php
                            }
                        }


                        if ($selection == "remix") {

                            $search_sql = mysqli_query($conn, "SELECT * from songs WHERE song_name like '%$search%' AND remix = '1' order by adds and streams desc");

                            while ($row = mysqli_fetch_assoc($search_sql)) {
                            ?>

                                <div class='song-item ambientDiv' id='<?= $row['song_id'] ?>'>
                                    <div class='img-play'>
                                        <img src='./audio_thumbnails/<?= $row['thumbnail'] ?>'>
                                        <i class='fa playlistPlay fa-play-circle'></i>
                                        <i class='fa fa-pause-circle'></i>
                                        <audio class='audio-item' src='./audios/<?= $row['audio_file'] ?>'></audio>
                                    </div>

                                    <h5>
                                        <div class='song-name'><?= $row['song_name'] ?></div>
                                        <div class='artist'><?= $row['artist'] ?></div>
                                    </h5>

                                    <div class="xb42dee">
                                        <?php echo "$domain/view.php?song=" . trim($row['song_id']) ?>
                                    </div>

                                    <div class="xb42dee2">
                                        <?php echo "https://emnetmusic.com/artist_page.php?poster_id=" . htmlspecialchars(trim($row['admin_id'] == 0 ? $row['creator_id'] : $row['admin_id'])) ?>
                                    </div>

                                    <?= ($row['admin_id'] != 0) ? "<span class='from-emnet'></span>" : "" ?>
                                    <?= ($row['admin_id'] != 0) ? "<span class='poster_id'> " . $row['admin_id'] . " </span>" : "<span class='poster_id'>" . $row['creator_id'] . " </span>" ?>
                                </div>
                    <?php
                            }
                        }
                    }

                    ?>
                </div>
            </div>


        </div>

    </main>

    <!--x----------------------- MAIN ------------------------x-->


    <!-- ---------Modals---- -->
    <?php include "modals.php" ?>
    <!-- ---------Modals---- -->

    <!-- ----- jQuery(insert for web letter) ----- -->
    <script src="./js/jquery3.6.0.js"></script>

    <!-- ----- popper(insert for web letter) ----- -->
    <script src="./js/popper1.160.min.js"></script>

    <!-- ----- Owl carousel(insert for web letter) ----- -->

    <!-- ----- boostrap(insert for web letter) ----- -->
    <script src="./js/bootstrap4.js"></script>

    <!-- ----- custom ----- -->
    <script src="./js/music-array.js"></script>
    <script src="./js/search.js"></script>
    <script src="./js/main.js"></script>

    <script>
        var playlistPage = document.getElementById("playlistPage"),
            playListFooter = playlistPage.querySelector(".playList-player"),
            playListItem = Array.from(playlistPage.getElementsByClassName("song-item"));

        if (playListItem.length <= 5) {
            playListFooter.classList.add("few");
        } else {
            playListFooter.classList.remove("few")
        }

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

        <?php } ?>
    </script>


</body>
</html>