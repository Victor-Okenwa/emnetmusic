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

    $query_playlists = mysqli_query($conn, "SELECT song_id FROM playlists WHERE user_id = '{$_SESSION['uniqueID']}'");
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

    <!-- -------------- Font awesome --------- -->
    <link rel="stylesheet" href="./css/all.css">


    <!-- -------------- Google material icons --------- -->
    <link rel="stylesheet" href="./iconfont/material-icons.css">


    <!-- -------------- boostrap CSS and JS --------- -->
    <link rel="stylesheet" href="./css/boot4.css">

    <script defer src="./js/jquery3.6.0.js"></script>
    <script defer src="./js/bootstrap4.js"></script>
    <!-- ----- custom ----- -->

    <!-- -------------- custom --------- -->
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./temp.css">

    <title>Emnet music</title>
</head>

<body class="paused" id="artist_page">

    <!-- ----------------------- HEADER ------------------------ -->
    <header>
        <!-- -------- upper navigation ------ -->
        <nav class="upper-navigation mk-flex navbar ambientDiv">
            <div class="first mk-flex">
                <div class="menu-btn btn mr-2 navbar-toggler">
                    <i class="material-icons menu-icon">menu</i>
                    <i class="material-icons cancel-icon">cancel</i>
                </div>

                <a href="" class=" navbar-brand logo mk-flex">
                    <img src="./logos/Emnet_Logo2.png" alt="emnet">
                    <span>emNet</span>
                </a>

            </div>

            <div class="last mk-flex">
                <!-- add ambient selector -->
                <div class="em-mode mr-3 btn" title="theme mode">
                    <i class="fa fa-moon"></i>
                </div>

                <div class="em-sort">
                    <div class="dropdown" id="sort-menu">

                    </div>
                </div>

                <!-- user profile -->

                <?php
                if (!isset($_SESSION['ID'])) {
                ?>
                    <a href="./login.php" class="login-signin btn text-white-50 border-success rounded-pill">Login /
                        Sigup</a>

                <?php } else { ?>
                    <div class="em-user nav-item dropdown">
                        <a class="nav-link mk-flex btn text-light" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small user-name mk-text-color2 text-lowercase"><?php (strlen($nickname) > 20) ? $subname = substr($nickname, 0, 20) . '...' : $subname = $nickname;
                                                                                                                                echo $subname; ?>
                            </span>
                            <?php if ($img == 0) { ?>
                                <img class="user-icon rounded-circle" src="./images/profile.png">
                            <?php } else { ?>
                                <img class="user-icon rounded-circle" width="40px" height="30px" src="./userprofiles/<?php echo $img ?>">
                            <?php } ?>
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in mk-bg-dark" aria-labelledby="userDropdown">
                            <a class="dropdown-item text-light" href="" data-toggle="modal" data-target="#userProfile">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-light" href="" data-toggle="modal" data-target="#logout">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout
                            </a>
                        </div>
                    </div>
                <?php } ?>
                <!-- user profile -->

            </div>
        </nav>


    </header>
    <!--X------ upper navigation --------X-->

    <!-- -------- side navigation ------ -->
    <div class="side-navigation">
        <ul class="navbar-nav">

            <li class="nav-item" title="Home">
                <a href="/" class="mk-flex">
                    <i class="fa fa-home"></i>
                    <span>Home</span>
                </a>
            </li>

            <li class="nav-item" title="Search">
                <a class="mk-flex searchLink">
                    <i class="fa fa-search"></i>
                    <span>Search</span>
                </a>
            </li>


            <?php if (isset($_SESSION['ID'])) { ?>
                <li class="nav-item" title="Playlists">
                    <a href="/playlists" class="mk-flex active">
                        <i class="fa fa-music"></i>
                        <span>Playlists</span>
                    </a>
                </li>
            <?php } ?>

            <?php if (isset($_SESSION['ID'])) { ?>
                <li class="nav-item" title="My dashboard">
                    <a href="/dashboard" class="mk-flex">
                        <i class="material-icons">dashboard</i>
                        <span>Dashboard</span>
                    </a>
                </li>
            <?php } ?>


            <?php if (isset($_SESSION['ID'])) { ?>
                <li class="nav-item" title="emnet studios">
                    <a href="/studio" class="mk-flex">
                        <i class="fa fa-microphone-alt"></i>
                        <span>Emnet Studios</span>
                    </a>
                </li>
            <?php } ?>


            <hr class="my-4">

            <div class="em-about mk-flex">
                <h4 class="text-light">About Us</h4>

                <p class="text-grey">
                    Emnet is a music streaming platform designed by emark ultimate to promote young talented artists.
                </p>
                <a href="/developers">Developer Team</a>
                <a href="" class="mt-2 mb-3">Policy</a>
                <span class="right">&copy; emark ultimate 2023</span>
            </div>
        </ul>

    </div>
    <!--X------ side navigation --------X-->
    <!--x----------------------- HEADER ------------------------x-->

    <!-- ----------------------- MAIN ------------------------ -->

    <main id="main">
        <!-- advert -->

        <?php
        if (isset($_SESSION['uniqueID'])) {
            if (isset($_GET['poster_id']) || $_GET['poster_id'] !== "") {
                $artist_id = $_GET['poster_id'];
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

                $query_followers = mysqli_query($conn, "SELECT * FROM followers WHERE poster_id = '{$artist_id}'");
                $query_following = mysqli_query($conn, "SELECT * FROM followers WHERE user_id = '{$unique_id}' AND poster_id = '$artist_id'");

                $num_followers = $query_followers->num_rows;
                $num_following = $query_following->num_rows;


        ?>
                <div class="music-layer">

                    <div class="container mk-flex justify-content-center">
                        <div class="artist-profile ambientDiv">
                            <div class="artist-img">
                                <?php
                                // to correct
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
                                        Fullname:
                                        <?= $artist_lname . " " . $artist_fname . " " . ($artist_type ? $artist_oname : "")  ?>
                                    </div>
                                    <div class="text-capitalize">
                                        Stage name: <?= $artist_nname ?>
                                    </div>
                                    <div class="">
                                        Email: <?= $artist_email ?>
                                    </div>
                                    <?php if ($artist_type) { ?>
                                        <div class="text-capitalize">
                                            Gender: <?= $artist_gender ?>
                                        </div>
                                        <div class="text-capitalize">
                                            Nationality: <?= $artist_nation ?>
                                        </div>
                                    <?php } ?>
                                </h5>

                                <div class="follow mk-flex justify-content-between">
                                    <div class="followers text-success">
                                        <?= ($unique_id == $artist_id ? "You have " : "") ?>
                                        <b id="numFollow"><?= subNumber($num_followers)  ?></b>
                                        <span class="text-light"> follower(s)</span>
                                    </div>
                                    <div class="follow-btn">
                                        <?php if ($unique_id == $artist_id) {
                                        } else {
                                        ?>

                                            <button class="btn <?= $num_following < 1 ? "btn-success em1" : "btn-warning em2" ?>" id="followstate" value="<?= $unique_id ?>+.+<?= $artist_id ?>+.+<?= $artist_nname ?>+.+<?= $nickname ?>"> <i class="fa  <?= $num_following < 1 ? "fa-user-plus" : "fa-user-minus" ?>"></i>
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

                    <form id="playlistForm">
                        <input type="number" name="userid" class="userid_list" value="<?= trim(htmlspecialchars($unique_id)) ?>">
                        <input type="number" name="songid" class="songid_list" value="">
                        <input type="text" name="songname" class="songname_list" value="">
                        <input type="text" name="posterid" class="poster_list" value="">
                        <input type="text" name="artist" class="artist_list" value="">
                    </form>


                    <div class="recent-playlist mt-5">
                        <div class="head-list">

                            <h3 class="text-light">Top songs </h3>
                            <div class="scroll-buttons d-none d-lg">
                                <button class="btn slide-left" id="recentscrollL"><i class="fa fa-angle-left"></i></button>
                                <button class="btn slide-right" id="recentscrollR"><i class="fa fa-angle-right"></i></button>
                            </div>
                        </div>

                        <div class="songs mk-flex">

                            <div class="song-list">
                                <?php
                                $query_artist_from1 = mysqli_query($conn, "SELECT * from songs WHERE creator_id = '{$artist_id}'");
                                $query_artist_from2 = mysqli_query($conn, "SELECT * from songs WHERE admin_id = '{$artist_id}'");

                                $artist_type = 0;
                                if ($query_artist_from1->num_rows > 0) {
                                    $query_artists_top_songs = mysqli_query($conn, "SELECT * FROM songs WHERE creator_id = '{$artist_id}' order by streams and adds desc Limit 10");
                                    $artist_type = 0;
                                } elseif ($query_artist_from2->num_rows > 0) {
                                    $query_artists_top_songs = mysqli_query($conn, "SELECT * FROM songs WHERE admin_id = '{$artist_id}' order by streams and adds desc Limit 10");
                                    $artist_type = 1;
                                } else {
                                    exit("Artist not found");
                                }
                                while ($top_song_data = mysqli_fetch_assoc($query_artists_top_songs)) {
                                    $query_history = mysqli_query($conn, "SELECT * FROM songs WHERE song_id = {$top_song_data['song_id']}");
                                    $fetch_history_join = mysqli_fetch_assoc($query_history);
                                ?>
                                    <div class="song-item ambientDiv" id="<?= $top_song_data['song_id'] ?>">
                                        <div class="img-play">
                                            <img src="./audio_thumbnails/<?= $top_song_data['thumbnail'] ?>" alt="">
                                            <i class="fa playlistPlay fa-play-circle"></i>
                                            <i class="fa fa-pause-circle"></i>
                                            <audio class="audio-item" src="./audios/<?= $fetch_history_join['audio_file'] ?>"></audio>
                                        </div>

                                        <h5>
                                            <div class="song-name"><?= $fetch_history_join['song_name'] ?></div>
                                            <div class="artist"><?= $fetch_history_join['artist'] ?></div>
                                        </h5>

                                        <div class="xb42dee">
                                            <?= "$domain/view.php?song=" . trim($fetch_history_join['song_id']) ?>
                                        </div>

                                        <div class="xb42dee2">
                                            <?= "$domain/view.php?song=" . trim($fetch_history_join['song_id']) ?>
                                        </div>

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


                    <hr class="my-4">
                    <br>

                    <div class="all-songs">
                        <h3 class="mk-flex text-light">Trending songs</h3>

                        <div class="songs">
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
                                        <img src="./audio_thumbnails/<?= $song_data['thumbnail'] ?>" alt="<?= $song_data['song_name'] ?>">
                                        <i class="fa playlistPlay fa-play-circle" id="<?= $song_data['song_id'] ?>"></i>
                                        <i class="fa fa-pause-circle"></i>
                                        <audio class="audio-item" src="./audios/<?= $song_data['audio_file'] ?>"></audio>
                                    </div>
                                    <div class="mk-flex justify-content-between">
                                        <h5>
                                            <div class="song-name text-capitalize mk-abel">
                                                <?= shortenTextName($song_data['song_name']) ?></div>
                                            <div class="artist text-capitalize"><?= shortenTextArtist($song_data['artist']) ?></div>
                                        </h5>
                                        <small class="text-light" style="font-size: 10px"><small class="fa fa-eye text-success"></small>
                                            <?= subNumber($song_data['streams']) ?></small>
                                    </div>

                                    <div class="xb42dee">
                                        <?= "$domain/view.php?song=" . trim($fetch_history_join['song_id']) ?>
                                    </div>

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


                    <hr class="my-4">
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
                                        <img src="./audio_thumbnails/<?= $song_data['thumbnail'] ?>" alt="<?= $song_data['song_name'] ?>">
                                        <i class="fa playlistPlay fa-play-circle" id="<?= $song_data['song_id'] ?>"></i>
                                        <i class="fa fa-pause-circle"></i>
                                        <audio class="audio-item" src="./audios/<?= $song_data['audio_file'] ?>"></audio>
                                    </div>
                                    <div class="mk-flex justify-content-between">
                                        <h5>
                                            <div class="song-name text-capitalize mk-abel">
                                                <?= shortenTextName($song_data['song_name']) ?></div>
                                            <div class="artist text-capitalize"><?= shortenTextArtist($song_data['artist']) ?></div>
                                        </h5>


                                        <div class="xb42dee">
                                            <?= "$domain/view.php?song=" . trim($song_data['song_id']) ?>
                                        </div>

                                        <div class="xb42dee2">
                                            <?= "$domain/artist.php?artist=" . trim($song_data['admin_name'] == "" ? $song_data['creator_name'] : $song_data['admin_name']) ?>
                                        </div>

                                        <small class="text-light" style="font-size: 10px"><small class="fa fa-eye text-success"></small>
                                            <?= subNumber($song_data['streams']) ?></small>
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

            <?php  } else {
            ?>
                <h2 class="text-light text-center">Could not view Artist base via link</h2>
                <h5 class="text-light text-center">Reason: link is incorrect or expired</h5>
            <?php
            }
        } else {
            ?>
            <h2 class="text-light text-center mt-5 pt-5">This page cannot be accessed while not logged in</h2>
        <?php
        } ?>


    </main>


    <!--x----------------------- MAIN ------------------------x-->
    <!------------- Modals ---------->

    <?php include "modals.php" ?>

    <!------------- Modals ---------->

    <script src="./js/slider.js"></script>
    <script src="./js/main.js"></script>



    <script>
        const toRecent = [...document.querySelectorAll('.song-item')];
        const metaID = document.querySelector("meta.user_id");
        const clear_history = document.getElementById("clear_history");


        toRecent.forEach((item) => {
            item.addEventListener("click", () => {
                $.ajax({
                    type: 'POST',
                    url: '',
                    data: {
                        history: 1,
                        user_id: metaID.id,
                        song_id: item.id,
                        song_name: item.querySelector(".song-name").textContent,
                    },
                });
            })
        })


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
    </script>
</body>

</html>