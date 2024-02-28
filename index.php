<?php
session_start();
include_once "backend/connection.php";

if (isset($_SESSION["uniqueID"])) {
    $query_playlists = mysqli_query($conn, "SELECT * FROM playlists WHERE user_id = '{$user_id}'");
    $query_following = mysqli_query($conn, "SELECT * FROM followers WHERE user_id = '$user_id'");
    $num_following = $query_following->num_rows;
}

$query_songs = mysqli_query($conn, "SELECT * FROM songs order BY rand() desc");

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

    <link rel="stylesheet" href="./css/material-icons.css">

    <!-- -------------- Google material icons REM(insert for web letter) --------- -->
    <link rel="stylesheet" href="./iconfont/material-icons.css">

    <!-- -------------- boostrap REM(insert for web letter) --------- -->
    <link rel="stylesheet" href="./css/boot4.css">

    <!-- -------------- custom --------- -->
    <link rel="stylesheet" href="./css/style.css">

    <title>Emnet music </title>
</head>

<body class="paused" id="homepg" onscroll="bodyScroll(this)">
    <!-- ----------------------- HEADER ------------------------ -->
    <?php require "./navbars.php" ?>
    <!--x----------------------- HEADER ------------------------x-->

    <!-- ----------------------- MAIN ------------------------ -->


    <main id="main">

        <!-- advert -->
        <?php
        $query_ads = $conn->query("SELECT * FROM advert");

        if (mysqli_num_rows($query_ads) > 0) {
            $row = $query_ads->fetch_assoc();
        ?>
            <div class="jumbotron-fluid em-ad">

                <a href="<?= $row['ad_link'] ?>" class="ad_interface" tartget="_blank" id="<?= $row['ad_id'] ?>">
                    <div class="img">
                        <img src="ad_thumbnail/<?= $row['ad_img'] ?>" alt="<?= $row['name'] ?>">
                    </div>
                </a>
            </div>
        <?php
        }
        ?>
        <!-- advert -->
        <div class="music-layer">

            <form id="playlistForm">
                <input type="number" name="userid" class="userid_list" value="<?= trim(htmlspecialchars($user_id)) ?>">
                <input type="number" name="songid" class="songid_list">
                <input type="text" name="songname" class="songname_list">
                <input type="text" name="posterid" class="poster_list">
                <input type="text" name="artist" class="artist_list">
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
                                <?php $query_history_songs = mysqli_query($conn, "SELECT * FROM history WHERE user_id = $user_id order BY history_id desc Limit 10");
                                while ($history_data = mysqli_fetch_assoc($query_history_songs)) {
                                    $query_history = mysqli_query($conn, "SELECT * FROM songs WHERE song_id = {$history_data['song_id']}");
                                    $fetch_history_join = mysqli_fetch_assoc($query_history);

                                    recentExpiration($history_data['date'], $history_data['history_id']);

                                ?>
                                    <div class="song-item ambientDiv" id="<?= $history_data['song_id'] ?>">


                                        <div class="img-play">
                                            <div class="load-item-img"></div>
                                            <img src="./audio_thumbnails/<?= $fetch_history_join['thumbnail'] ?>" alt="">
                                            <i class="fa playlistPlay fa-play-circle"></i>
                                            <i class="fa fa-pause-circle"></i>
                                            <audio class="audio-item" src="./audios/<?= $fetch_history_join['audio_file'] ?>"></audio>
                                        </div>

                                        <h5>

                                            <div class="load-item-text">
                                                <div class="load-text-item"></div>
                                                <div class="load-text-item"></div>
                                            </div>

                                            <div class="song-name"><?= $fetch_history_join['song_name'] ?></div>
                                            <div class="artist"><?= $fetch_history_join['artist'] ?></div>
                                        </h5>

                                        <div class="xb42dee">
                                            <?php echo "$domain/view.php?song=" . trim($fetch_history_join['song_id'])  ?>
                                        </div>

                                        <div class="xb42dee2">
                                            <?php echo "$domain/artist.php?artist=" . trim($fetch_history_join['admin_name'] == "" ? $fetch_history_join['creator_name'] : $fetch_history_join['admin_name'])  ?>
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
                    <br>
            <?php  }
            } ?>

            <div class="top-playlist">
                <div class="head-list">
                    <h3 class="text-light">Top Songs</h3>
                    <div class="scroll-buttons d-none d-lg-block d-md-block">
                        <button class="btn slide-left" id="topscrollL"><i class="fa fa-angle-left"></i></button>
                        <button class="btn slide-right" id="topscrollR"><i class="fa fa-angle-right"></i></button>
                    </div>

                </div>


                <div class="songs mk-flex">

                    <div class="song-list top-songs">
                        <?php $query_top_songs = mysqli_query($conn, "SELECT * FROM songs order BY streams AND adds desc Limit 10");
                        while ($top_data = mysqli_fetch_assoc($query_top_songs)) {
                        ?>
                            <div class="song-item ambientDiv" id="<?= $top_data['song_id'] ?>" onclick="selectSong(this)">
                                <div class="img-play">
                                    <div class="load-item-img"></div>
                                    <img src="./audio_thumbnails/<?= $top_data['thumbnail'] ?>" alt="">
                                    <i class="fa playlistPlay fa-play-circle"></i>
                                    <i class="fa fa-pause-circle"></i>
                                    <audio class="audio-item" src="./audios/<?= $top_data['audio_file'] ?>"></audio>
                                </div>

                                <div class="mk-flex justify-content-between">
                                    <h5>
                                        <div class="load-item-text">
                                            <div class="load-text-item"></div>
                                            <div class="load-text-item"></div>
                                        </div>
                                        <div class="song-name mk-abel"><?= $top_data['song_name'] ?></div>
                                        <div class="artist"><?= $top_data['artist'] ?></div>
                                    </h5>
                                    <small class="text-light" style="font-size: 10px"><small class="fa fa-eye text-success"></small> <?= subNumber($top_data['streams'])  ?></small>
                                </div>

                                <div class="xb42dee">
                                    <?php echo  "$domain/view.php?song=" . trim($top_data['song_id']) ?>
                                </div>

                                <div class="xb42dee2">
                                    <?php echo "$domain/artist.php?artist=" . trim($top_data['admin_name'] == 0 ? $top_data['creator_name'] : $top_data['admin_name'])  ?>
                                </div>

                                <?= ($top_data['admin_id'] != 0) ? "<span class='poster_id'> " . $top_data['admin_id'] . " </span>" : "<span class='poster_id'>" . $top_data['creator_id'] . " </span>" ?>
                                <?= ($top_data['admin_id'] != 0) ? "<span class='from-emnet'></span>" : "" ?>
                            </div>
                        <?php
                        }
                        ?>

                    </div>
                </div>

                <br>
            </div>

            <?php $query_top_songs = mysqli_query($conn, "SELECT * FROM songs order BY created_on AND streams desc Limit 24");
            if (mysqli_num_rows($query_top_songs) > 0) {
            ?>
                <div class="trending-list mt-5">

                    <div class="head-list mk-flex justify-content-between">
                        <h3 class="text-light">Trending songs</h3>

                        <div class="scroll-buttons d-none d-lg-block d-md-block">
                            <button class="btn slide-left" id="tredscrollL"><i class="fa fa-angle-left"></i></button>
                            <button class="btn slide-right" id="tredscrollR"><i class="fa fa-angle-right"></i></button>
                        </div>

                    </div>

                    <div class="songs">

                        <div class="song-list">
                            <div class="tred-song-list">
                                <?php
                                while ($top_data = mysqli_fetch_assoc($query_top_songs)) {
                                ?>
                                    <div class="song-item ambientDiv" id="<?= $top_data['song_id'] ?>">
                                        <div class="img-play">
                                            <div class="load-item-img"></div>
                                            <img src="./audio_thumbnails/<?= $top_data['thumbnail'] ?>" alt="">
                                            <i class="fa playlistPlay fa-play-circle"></i>
                                            <i class="fa fa-pause-circle"></i>
                                            <audio class="audio-item" src="./audios/<?= $top_data['audio_file'] ?>"></audio>
                                        </div>

                                        <div class="mk-flex justify-content-between">
                                            <h5>
                                                <div class="load-item-text">
                                                    <div class="load-text-item"></div>
                                                    <div class="load-text-item"></div>
                                                </div>
                                                <div class="song-name mk-abel"><?= $top_data['song_name'] ?></div>
                                                <div class="artist mk-abel"><?= $top_data['artist'] ?></div>
                                            </h5>

                                            <div class="xb42dee">
                                                <?php echo "$domain/view.php?song=" . trim($top_data['song_id']) ?>
                                            </div>

                                            <div class="xb42dee2">
                                                <?php echo "$domain/artist.php?artist=" . trim($top_data['admin_name'] == 0 ? $top_data['creator_name'] : $top_data['admin_name']) ?>
                                            </div>

                                        </div>
                                        <?= ($top_data['admin_id'] != 0) ? "<span class='from-emnet'></span>" : "" ?>
                                        <?= ($top_data['admin_id'] != 0) ? "<span class='poster_id d-none' hidden> " . $top_data['admin_id'] . " </span>" : "<span class='poster_id d-none' hidden>" . $top_data['creator_id'] . " </span>" ?>
                                    </div>

                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

            <?php } ?>

            <?php $query_top_songs = mysqli_query($conn, "SELECT * FROM songs order BY streams desc Limit 10");
            if (mysqli_num_rows($query_top_songs) > 0) {
            ?>
                <div class="recent-playlist mt-5 tps2">

                    <div class="head-list">
                        <h3 class="text-light">Top streams</h3>
                        <div class="scroll-buttons d-none d-lg-block d-md-block">
                            <button class="btn slide-left" id="afropopscrollL"><i class="fa fa-angle-left"></i></button>
                            <button class="btn slide-right" id="afropopscrollR"><i class="fa fa-angle-right"></i></button>
                        </div>
                    </div>

                    <div class="songs mk-flex">

                        <div class="song-list">


                            <div class="song-list afro-pop">
                                <?php
                                while ($top_data = mysqli_fetch_assoc($query_top_songs)) {
                                ?>
                                    <div class="song-item ambientDiv" id="<?= $top_data['song_id'] ?>">
                                        <div class="img-play">
                                            <div class="load-item-img"></div>
                                            <img src="./audio_thumbnails/<?= $top_data['thumbnail'] ?>" alt="">
                                            <i class="fa playlistPlay fa-play-circle"></i>
                                            <i class="fa fa-pause-circle"></i>
                                            <audio class="audio-item" src="./audios/<?= $top_data['audio_file'] ?>"></audio>

                                        </div>
                                        <div class="mk-flex justify-content-between">
                                            <h5>
                                                <div class="load-item-text">
                                                    <div class="load-text-item"></div>
                                                    <div class="load-text-item"></div>
                                                </div>
                                                <div class="song-name mk-abel"><?= $top_data['song_name'] ?></div>
                                                <div class="artist mk-abel"><?= $top_data['artist'] ?></div>
                                            </h5>
                                            <div class="xb42dee">
                                                <?php echo "$domain/view.php?song=" . trim($top_data['song_id']) ?>
                                            </div>

                                            <div class="xb42dee2">
                                                <?php echo "$domain/artist.php?artist=" . trim($top_data['admin_name'] == "" ? $top_data['creator_name'] : $top_data['admin_name']) ?>
                                            </div>
                                        </div>
                                        <?= ($top_data['admin_id'] != 0) ? "<span class='from-emnet'></span>" : "" ?>
                                        <?= ($top_data['admin_id'] != 0) ? "<span class='poster_id d-none' hidden> " . $top_data['admin_id'] . " </span>" : "<span class='poster_id d-none' hidden>" . $top_data['creator_id'] . " </span>" ?>
                                    </div>

                                <?php
                                }
                                ?>
                            </div>

                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

            <?php $query_top_songs = mysqli_query($conn, "SELECT * FROM songs order BY adds desc Limit 10");
            if (mysqli_num_rows($query_top_songs) > 0) {
            ?>
                <div class="recent-playlist mt-5 tps2">
                    <div class="head-list">
                        <h3 class="text-light">Top playlist</h3>

                        <div class="scroll-buttons d-none d-lg-block d-md-block">
                            <button class="btn slide-left" id="afrobeatscrollL"><i class="fa fa-angle-left"></i></button>
                            <button class="btn slide-right" id="afrobeatscrollR"><i class="fa fa-angle-right"></i></button>
                        </div>
                    </div>

                    <div class="songs mk-flex">

                        <div class="song-list">
                            <div class="song-list afro-beat">
                                <?php
                                while ($top_data = mysqli_fetch_assoc($query_top_songs)) {
                                ?>
                                    <div class="song-item ambientDiv" id="<?= $top_data['song_id'] ?>">
                                        <div class="img-play">
                                            <div class="load-item-img"></div>
                                            <img src="./audio_thumbnails/<?= $top_data['thumbnail'] ?>" alt="">
                                            <i class="fa playlistPlay fa-play-circle"></i>
                                            <i class="fa fa-pause-circle"></i>
                                            <audio class="audio-item" src="./audios/<?= $top_data['audio_file'] ?>"></audio>

                                        </div>
                                        <div class="mk-flex justify-content-between">
                                            <h5>
                                                <div class="load-item-text">
                                                    <div class="load-text-item"></div>
                                                    <div class="load-text-item"></div>
                                                </div>
                                                <div class="song-name mk-abel"><?= $top_data['song_name'] ?></div>
                                                <div class="artist mk-abel"><?= $top_data['artist'] ?></div>
                                            </h5>

                                            <div class="xb42dee">
                                                <?php echo "$domain/view.php?song=" . trim($top_data['song_id']) ?>
                                            </div>

                                            <div class="xb42dee2">
                                                <?php echo "$domain/artist.php?artist=" . trim($top_data['admin_name'] == "" ? $top_data['creator_name'] : $top_data['admin_name']) ?>
                                            </div>

                                        </div>
                                        <?= ($top_data['admin_id'] != 0) ? "<span class='from-emnet'></span>" : "" ?>
                                        <?= ($top_data['admin_id'] != 0) ? "<span class='poster_id d-none' hidden> " . $top_data['admin_id'] . " </span>" : "<span class='poster_id d-none' hidden>" . $top_data['creator_id'] . " </span>" ?>
                                    </div>
                                <?php
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                </div>

            <?php } ?>

            <?php $query_top_songs = mysqli_query($conn, "SELECT * FROM songs order BY adds AND streams desc Limit 10");
            if (mysqli_num_rows($query_top_songs) > 0) {
            ?>
                <div class="recent-playlist mt-5 tps2">

                    <div class="head-list">
                        <h3 class="text-light">Top artists <small>with song</small></h3>
                        <div class="scroll-buttons d-none d-lg-block d-md-block">
                            <button class="btn slide-left" id="amapianoscrollL"><i class="fa fa-angle-left"></i></button>
                            <button class="btn slide-right" id="amapianoscrollR"><i class="fa fa-angle-right"></i></button>
                        </div>
                    </div>

                    <div class="songs mk-flex">

                        <div class="song-list">


                            <div class="song-list amapiano">
                                <?php
                                while ($top_data = mysqli_fetch_assoc($query_top_songs)) {
                                    if ($top_data['admin_id'] != 0) {
                                        $query_admin_info = mysqli_query($conn, "SELECT * FROM admin WHERE admin_id = {$top_data['admin_id']}");
                                        $data_admin = mysqli_fetch_assoc($query_admin_info);
                                    } else {
                                        $query_creator_info = mysqli_query($conn, "SELECT * FROM users WHERE user_id = {$top_data['creator_id']}");
                                        $data_creator = mysqli_fetch_assoc($query_creator_info);
                                    }
                                ?>
                                    <div class="song-item ambientDiv" id="<?= $top_data['song_id'] ?>">
                                        <div class="img-play">
                                            <div class="load-item-img"></div>

                                            <img src="
                            <?=
                                    $top_data['admin_id'] != 0 ? "./admin/adminprofile/" . $data_admin['admin_profile'] . " " : "./userprofiles/" . $data_creator['user_profile'] . " "
                            ?>
                            " alt="">
                                            <i class="fa playlistPlay fa-play-circle"></i>
                                            <i class="fa fa-pause-circle"></i>
                                            <audio class="audio-item" src="./audios/<?= $top_data['audio_file'] ?>"></audio>
                                        </div>
                                        <span class="text-light text-lowercase mb-2 mk-josefin"> <?= $top_data['admin_id'] != 0 ? "{$data_admin['nickname']}" : "{$data_creator['nickname']}" ?></span>
                                        <h5>
                                            <div class="load-item-text">
                                                <div class="load-text-item"></div>
                                                <div class="load-text-item"></div>
                                            </div>
                                            <div class="xb42dee">
                                                <?= "$domain/view.php?song=" . trim($top_data['song_id']) ?>
                                            </div>

                                            <div class="xb42dee2">
                                                <?= "$domain/artist.php?artist=" . trim($top_data['admin_name'] == "" ? $top_data['creator_name'] : $top_data['admin_name']) ?>
                                            </div>
                                            <div class="song-name mk-itim"><?= $top_data['song_name'] ?></div>
                                            <div class="artist mk-abel"><?= $top_data['artist'] ?></div>
                                        </h5>
                                        <?= ($top_data['admin_id'] != 0) ? "<span class='from-emnet'></span>" : "" ?>

                                        <?= ($top_data['admin_id'] != 0) ? "<span class='poster_id d-none' hidden> " . $top_data['admin_id'] . " </span>" : "<span class='poster_id d-none' hidden>" . $top_data['creator_id'] . " </span>" ?>

                                    </div>
                                <?php
                                }
                                ?>

                            </div>

                        </div>
                    </div>
                </div>

            <?php
            }
            ?>
            <div class="all-songs mt-5 pt-5">

                <div class="head-list">
                    <h3 class="text-light">Recomendations</h3>
                </div>

                <div class="songs">


                </div>
            </div>

        </div>

        <div id="loadMore" class="mt-5" style="display: none; width: 100%; place-items: center;" onclick="loadMoreSongs(this)">
            <button class="btn btn-dark">Load More <span class="spinner spinner-grow spinner-grow-sm d-none"></span> </button>
        </div>
    </main>



    <!--x----------------------- MAIN ------------------------x-->


    <!------------- Modals ---------->

    <?php include "modals.php" ?>

    <!------------- Modals ---------->

    <!-- ----- jQuery(insert for web letter) ----- -->
    <script src="./js/jquery3.6.0.js"></script>

    <!-- ----- popper(insert for web letter) ----- -->
    <script src="./js/popper1.160.min.js"></script>

    <!-- ----- boostrap(insert for web letter) ----- -->
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