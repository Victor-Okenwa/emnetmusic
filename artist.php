<?php
session_start();
include_once "backend/connection.php";
// $page_url = explode("/", $_SERVER['PHP_SELF']);
if (isset($_SESSION["uniqueID"])) {
    $artist_name = $_GET['artist'];
    if (isInDb($artist_name, "nickname", "creators")) {
        $fetch_user = $conn->query("SELECT * FROM users WHERE nickname = '$artist_name'")->fetch_assoc();
        $fetch_creator = $conn->query("SELECT * FROM creators WHERE nickname = '$artist_name'")->fetch_assoc();
        $query_artists_top_songs = mysqli_query($conn, "SELECT * FROM songs WHERE creator_name = '{$artist_name}' order by streams desc Limit 10");
        $artist_id = $fetch_user['user_id'];
        $artist_fname = $fetch_user['firstname'];
        $artist_lname = $fetch_user['surname'];
        $artist_oname = $fetch_user['othername'];
        $artist_nname = $fetch_user['nickname'];
        $artist_email = $fetch_user['email'];
        $artist_nation = $fetch_user['country'];
        $artist_gender = $fetch_user['gender'];
        $artist_img = $fetch_user['user_profile'] !== "" ? "{$dir_lvl}userprofiles/{$fetch_user['user_profile']}" : "";
        $recordLabel = $fetch_creator['record_label'];

        $artist_type = true;
    } elseif (isInDb($artist_name, "nickname", "admin")) {
        $fetch_user = $conn->query("SELECT * FROM admin WHERE nickname = '$artist_name'")->fetch_assoc();
        $query_artists_top_songs = mysqli_query($conn, "SELECT * FROM songs WHERE admin_name = '{$artist_name}' order by streams desc Limit 10");
        $artist_id = $fetch_user['admin_id'];
        $artist_fname = $fetch_user['firstname'];
        $artist_lname = $fetch_user['lastname'];
        $artist_nname = $fetch_user['nickname'];
        $artist_email = $fetch_user['email'];
        $artist_img = $fetch_user['admin_profile'] !== "" ? "{$dir_lvl}adminprofile/{$fetch_user['admin_profile']}" : "";
        $recordLabel = "";
        $artist_type = false;
    } else {
        exit("<body style=\"background: black \">
        <h3 style=\"text-align:center; margin-top: 300px; color: white; font-family:Tahoma\">Bad request: Artist not found</h3>
        </body>");
    }
} else {
    header("Location: /");
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
    <link rel="shortcut icon" sizes="392x6592" href="/logos/Emnet_Logo2.png" type="image/x-icon">

    <!-- -------------- Font awesome REM(insert for web letter) --------- -->
    <link rel="stylesheet" href="./css/all.css">

    <!-- -------------- Google material icons REM(insert for web letter) --------- -->
    <link rel="stylesheet" href="./iconfont/material-icons.css">


    <!-- -------------- boostrap REM(insert for web letter) --------- -->
    <link rel="stylesheet" href="css/boot4.css">

    <!-- -------------- custom --------- -->
    <link rel="stylesheet" href="./css/style.css">

    <title>Emnet music</title>
</head>

<body class="paused" id="artist_page">

    <!-- ----------------------- HEADER ------------------------ -->
    <?php include "./navbars.php"  ?>
    <!--x----------------------- HEADER ------------------------x-->
    <!-- ----------------------- MAIN ------------------------ -->
    <main id="main">
        <?php
        $query_followers = mysqli_query($conn, "SELECT * FROM followers WHERE poster_name = '$artist_name'");
        $query_following = mysqli_query($conn, "SELECT * FROM followers WHERE user_id = '$artist_id' AND poster_name = '$artist_name' ");

        $num_followers = $query_followers->num_rows;
        $num_following = $query_following->num_rows;
        ?>
        <div class="music-layer">
            <div class="container">
                <div class="artist-profile ambientDiv">
                    <div class="main-info">

                        <div class="artist-img">
                            <?php
                            if ($artist_img != "") {  ?>
                                <img src="<?= $artist_img ?>" alt="<?= $artist_nname ?>">
                            <?php } else {
                            ?>
                                <img src="./images/profile.png" alt="<?= $artist_nname ?>">
                            <?php
                            } ?>


                        </div>

                        <div class="options">

                            <div class="name">
                                <div class="text-capitalize real-name">
                                    <?= $artist_lname . " " . $artist_fname . " " . ($artist_type ? $artist_oname : "")  ?>
                                </div>
                                <div class="stage-name">
                                    <?= $artist_nname ?>
                                </div>
                            </div>

                            <div class="record-label" title="Record label status">
                                <span>Record Label:</span>
                                <div>
                                    <?php if ($recordLabel == '') { ?>
                                        <i class="fa fa-search"></i>
                                        <p>Searching</p>
                                    <?php } else { ?>
                                        <i class="fa fa- text-dark"></i>
                                        <p class="text-center bg-light text-dark" style="width: 100%"><?= $recordLabel ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php if (trim(strtolower($user_type)) == 'team' || isInDb($_SESSION['uniqueID'], "user_id", 'creators')) { ?>
                                <div class="chat">
                                    <a href="./chat/chat.php?receiverID=<?= $artist_id ?>" title="Message artist">
                                        <span>CHAT</span>
                                        <i class="material-icons">message</i>
                                    </a>
                                </div>
                            <?php } ?>
                            <div class="followers text-success">
                                <?= ($_SESSION['uniqueID'] == $artist_id ? "You have " : "") ?>
                                <b id="numFollow"><?= subNumber($num_followers)  ?></b>
                                <span class="text-light"> follower(s)</span>
                            </div>

                            <div class="follow-btn">
                                <?php if ($unique_id == $artist_id) {
                                } else {
                                ?>
                                    <button type="button" class="btn <?= $num_following < 1 ? "btn-success em1" : "btn-warning em2" ?>" id="followstate" value="<?= $unique_id ?>+.+<?= $artist_id ?>+.+<?= $artist_nname ?>+.+<?= $nickname ?>"> <i class="fa  <?= $num_following < 1 ? "fa-user-plus" : "fa-user-minus" ?>"></i>
                                        <?= $num_following < 1 ? "Follow" : "Unfollow" ?>
                                    </button>
                                <?php
                                }
                                ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?php
            if (isInDb($artist_name, "creator_name", "songs") || isInDb($artist_name, "admin_name", "songs")) {
            ?>
                <form id="playlistForm">
                    <input type="number" name="userid" class="userid_list" value="<?= trim(htmlspecialchars($unique_id)) ?>">
                    <input type="number" name="songid" class="songid_list" value="">
                    <input type="text" name="songname" class="songname_list" value="">
                    <input type="text" name="posterid" class="poster_list" value="">
                    <input type="text" name="artist" class="artist_list" value="">
                </form>


                <div class="top-playlist">
                    <div class="head-list">
                        <h3 class="text-light">Most streams </h3>
                        <div class="scroll-buttons d-none d-lg-block d-md-block">
                            <button class="btn slide-left" id="recentscrollL"><i class="fa fa-angle-left"></i></button>
                            <button class="btn slide-right" id="recentscrollR"><i class="fa fa-angle-right"></i></button>
                        </div>
                    </div>

                    <div class="songs mk-flex">
                        <div class="song-list top-songs">
                            <?php
                            while ($top_song_data = mysqli_fetch_assoc($query_artists_top_songs)) {
                                $query_history = mysqli_query($conn, "SELECT * FROM songs WHERE song_id = {$top_song_data['song_id']}");
                                $fetch_history_join = mysqli_fetch_assoc($query_history);
                            ?>
                                <div class="song-item ambientDiv" id="<?= $top_song_data['song_id'] ?>">
                                    <div class="img-play">
                                        <div class="load-item-img"></div>
                                        <img src="./audio_thumbnails/<?= $top_song_data['thumbnail'] ?>" alt="">
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
                                        <?= "$domain/view.php?song=" . trim($fetch_history_join['song_id']) ?>
                                    </div>

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

                <div class="trending-list">
                    <div class="head-list">
                        <h3 class="text-light">Trending songs </h3>
                        <div class="scroll-buttons d-none d-lg-block d-md-block">
                            <button class="btn slide-left" id="tredscrollL"><i class="fa fa-angle-left"></i></button>
                            <button class="btn slide-right" id="tredscrollR"><i class="fa fa-angle-right"></i></button>
                        </div>
                    </div>

                    <div class="songs">
                        <div class="song-list">
                            <div class="tred-song-list">
                                <?php
                                $query_artist_from1 = mysqli_query($conn, "SELECT * from songs WHERE creator_id = '{$artist_id}'");
                                $query_artist_from2 = mysqli_query($conn, "SELECT * from songs WHERE admin_id = '{$artist_id}'");

                                $artist_type = 0;
                                if ($query_artist_from1->num_rows > 0) {
                                    $query_artists_tred_songs = mysqli_query($conn, "SELECT * FROM songs WHERE creator_id = '{$artist_id}' order by created_on and streams  desc Limit 5");
                                    $artist_type = 0;
                                } elseif ($query_artist_from2->num_rows > 0) {
                                    $query_artists_tred_songs = mysqli_query($conn, "SELECT * FROM songs WHERE admin_id = '{$artist_id}' order by streams desc Limit 5");
                                    $artist_type = 1;
                                }
                                while ($song_data = mysqli_fetch_assoc($query_artists_tred_songs)) {
                                ?>
                                    <div class="song-item ambientDiv" id="<?= $song_data['song_id'] ?>">
                                        <div class="img-play">
                                            <div class="load-item-img"></div>
                                            <img src="./audio_thumbnails/<?= $song_data['thumbnail'] ?>" alt="<?= $song_data['song_name'] ?>">
                                            <i class="fa playlistPlay fa-play-circle" id="<?= $song_data['song_id'] ?>"></i>
                                            <i class="fa fa-pause-circle"></i>
                                            <audio class="audio-item" src="./audios/<?= $song_data['audio_file'] ?>"></audio>
                                        </div>
                                        <div class="mk-flex justify-content-between">
                                            <h5>
                                                <div class="load-item-text">
                                                    <div class="load-text-item"></div>
                                                    <div class="load-text-item"></div>
                                                </div>
                                                <div class="song-name text-capitalize mk-abel">
                                                    <?= shortenTextName($song_data['song_name']) ?></div>
                                                <div class="artist text-capitalize"><?= shortenTextArtist($song_data['artist']) ?></div>
                                            </h5>
                                            <small class="text-light" style="font-size: 10px"><small class="fa fa-eye text-success"></small>
                                                <?= subNumber($song_data['streams']) ?></small>
                                        </div>

                                        <div class="xb42dee">
                                            <?php echo "$domain/view.php?song=" . trim($song_data['song_id']) ?> </div>

                                        <div class="xb42dee2">
                                            <?php echo "https://www.emnetmusic.com/artist_page.php?poster_id=" . htmlspecialchars(trim($song_data['admin_id'] == 0 ? $song_data['creator_id'] : $song_data['admin_id'])) ?>
                                        </div>

                                        <?= ($song_data['admin_id'] != 0) ? "<span class='from-emnet'></span>" : "" ?>
                                        <?= ($song_data['admin_id'] != 0) ? "<span class='poster_id'> " . $song_data['admin_id'] . " </span>" : "<span class='poster_id'>" . $song_data['creator_id'] . " </span>" ?>

                                        <div class="genre"><?= $song_data['genre'] ?></div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>


                <br>

                <div class="all-songs">
                    <h3 class="mk-flex text-light"> Newest songs</h3>

                    <div class="songs">
                        <?php

                        $query_artist_from1 = mysqli_query($conn, "SELECT * from songs WHERE creator_id = '{$artist_id}'");
                        $query_artist_from2 = mysqli_query($conn, "SELECT * from songs WHERE admin_id = '{$artist_id}'");
                        $artist_type = 0;
                        if ($query_artist_from1->num_rows > 0) {
                            $query_artists_tred_songs = mysqli_query($conn, "SELECT * FROM songs WHERE creator_id = '{$artist_id}' order by created_on desc Limit 5");
                            $artist_type = 0;
                        } elseif ($query_artist_from2->num_rows > 0) {
                            $query_artists_tred_songs = mysqli_query($conn, "SELECT * FROM songs WHERE admin_id = '{$artist_id}' order by created_on desc Limit 5");
                            $artist_type = 1;
                        }
                        while ($song_data = mysqli_fetch_assoc($query_artists_tred_songs)) {
                        ?>
                            <div class="song-item ambientDiv" id="<?= $song_data['song_id'] ?>">
                                <div class="img-play">
                                    <div class="load-item-img"></div>
                                    <img src="./audio_thumbnails/<?= $song_data['thumbnail'] ?>" alt="<?= $song_data['song_name'] ?>">
                                    <i class="fa playlistPlay fa-play-circle" id="<?= $song_data['song_id'] ?>"></i>
                                    <i class="fa fa-pause-circle"></i>
                                    <audio class="audio-item" src="./audios/<?= $song_data['audio_file'] ?>"></audio>
                                </div>
                                <div class="mk-flex justify-content-between">
                                    <h5>
                                        <div class="load-item-text">
                                            <div class="load-text-item"></div>
                                            <div class="load-text-item"></div>
                                        </div>
                                        <div class="song-name text-capitalize mk-abel">
                                            <?= shortenTextName($song_data['song_name']) ?></div>
                                        <div class="artist text-capitalize"><?= shortenTextArtist($song_data['artist']) ?></div>
                                    </h5>
                                    <small class="text-light" style="font-size: 10px"><small class="fa fa-eye text-success"></small>
                                        <?= subNumber($song_data['streams']) ?></small>
                                </div>

                                <div class="xb42dee">
                                    <?= "$domain/view/" . trim($song_data['song_id']) ?> </div>

                                <div class="xb42dee2">
                                    <?= "$domain/artist.php?artist=" . trim($song_data['admin_name'] == "" ? $song_data['creator_name'] : $song_data['admin_name']) ?>
                                </div>

                                <?= ($song_data['admin_id'] != 0) ? "<span class='from-emnet'></span>" : "" ?>
                                <?= ($song_data['admin_id'] != 0) ? "<span class='poster_id'> " . $song_data['admin_id'] . " </span>" : "<span class='poster_id'>" . $song_data['creator_id'] . " </span>" ?>

                                <div class="genre"><?= $song_data['genre'] ?></div>
                            </div>

                        <?php
                        }
                        ?>
                    </div>
                </div>
            <?php
            } else {
                echo "<h4 class=\"text-light text-center mk-abel\">No music record found under this artist</h4>";
            }
            ?>
        </div>
    </main>

    <!--x----------------------- MAIN ------------------------x-->
    <!------------- Modals ---------->

    <?php include "modals.php" ?>

    <!------------- Modals ---------->

    <!-- ----- custom ----- -->

    <script src="./js/jquery3.6.0.js"></script>

    <script src="./js/popper1.160.min.js"></script>

    <script src="./js/bootstrap4.js"></script>
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


            formList.onsubmit = (e) => {
                e.preventDefault();
            }

            const followstate = document.getElementById("followstate");
            const numFollow = document.getElementById("numFollow");

            followstate.addEventListener("click", () => {
                let followData = followstate.value.split("+.+");

                let userId = followData[0];
                let artistId = followData[1];
                let artistName = followData[2];
                let userName = followData[3];

                $.ajax({
                    url: "backend/followstate.php",
                    method: "POST",

                    data: {
                        userId: userId,
                        artistId: artistId,
                        artistName: artistName,
                        userName: userName,
                    },

                    success: function(response) {
                        if (response == "success follow") {
                            followstate.className = "";
                            followstate.className = "btn btn-warning em2";
                            followstate.innerHTML = "<i class='fa fa-user-minus'></i> Unfollow";
                        } else if (response == "success unfollow") {
                            followstate.className = "";
                            followstate.className = "btn btn-success em1";
                            followstate.innerHTML = "<i class='fa fa-user-plus'></i> Follow";
                        }
                    }
                })
            })

            // unfollow.addEventListener("click", ()=>{
            //     let unfollowData = unfollow.value.split("+.+");

            //     let userId = unfollowData[0];
            //     let artistId = unfollowData[1];
            //     let artistName = unfollowData[2];
            //     let userName = unfollowData[3];

            //     $.ajax({
            //         url: "backend/unfollow.php",
            //         method: "POST",

            //         data: {
            //             userId: userId,
            //             artistId: artistId,
            //             artistName: artistName,
            //             userName: userName,
            //         },

            //         success: function(response) {
            //             alert(response)

            //             if(response == "success"){
            //                 unfollow.id = "follow";
            //                 unfollow.value = "<?= $unique_id ?>+.+<?= $artist_id ?>+.+<?= $artist_nname ?>+.+<?= $nickname ?>";
            //                 unfollow.className = "";
            //                 unfollow.className = "btn btn-success";
            //                 unfollow.innerHTML = "<i class='fa fa-user-plus'></i> Follow";
            //             }
            //         }
            //     })
            // })

            // let splitNumFollow = numFollow.innerText.split("");
            // let lastIndex = splitNumFollow[splitNumFollow.length - 1]

            // // console.log(lastIndex)
            // if (isNaN(lastIndex)) {
            //     splitNumFollow.pop();
            //     let joined = splitNumFollow.join("");

            //     if(lastIndex == "k"){
            //         let joined = parseInt(joined*1000 +1);
            //         j
            //         console.log();
            //     }
            //   console.log(splitNumFollow)
            // }
        <?php } ?>
    </script>
</body>

</html>