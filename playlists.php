<?php
session_start();
include_once "backend/connection.php";

if (isset($_SESSION["uniqueID"])) {
    $unique_id = $_SESSION["uniqueID"];
    $query_playlists = mysqli_query($conn, "SELECT * FROM playlists WHERE user_id = {$user_id}");
    $num_in_playlists = $query_playlists->num_rows;
} else {
    header("Location: index.php");
}

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
    <style>
        .re-pos {
            top: 50% !important;
        }
    </style>

    <title>Emnet music</title>
</head>

<body class="favList">

    <!-- ----------------------- HEADER ------------------------ -->
    <header>
        <!-- -------- upper navigation ------ -->
        <nav class="upper-navigation mk-flex navbar ambientDiv searchPage">
            <div class="first mk-flex pt-2">
                <div class="menu-btn btn mr-2 navbar-toggler" onclick="navView(this)">
                    <i class="material-icons menu-icon">menu</i>
                    <i class="material-icons cancel-icon">cancel</i>
                </div>
            </div>

            <div class="middle mk-flex">
                <div class="search">
                    <input type="search" id="myInput" class="em-search-box" onkeydown="searchFavs()" placeholder="Search here ...">
                </div>
            </div>

            <div class="last mk-flex">
                <!-- add ambient selector -->
                <div class="em-mode mr-3 btn" title="theme mode" onclick="ambient(this)">
                    <i class="fa fa-moon"></i>
                </div>

                <div class="em-sort">
                    <div class="dropdown" id="sort-menu">

                    </div>
                </div>

                <!-- user profile -->
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
                        <a href="./chat/inbox.php" class="dropdown-item text-light d-flex align-center">
                            <small id="unread_count" class="mr-2 d-inline-block" style="height: 100%;font-size: 100%; font-weight: 400; color: #fff;padding: 2px 5px;border-radius: 30px;"></small>
                            Inbox
                        </a>
                        <a class="dropdown-item text-light" href="profile.php">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-light" href="#" data-toggle="modal" data-target="#logout">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout
                        </a>
                    </div>
                </div>
                <!-- user profile -->
            </div>
        </nav>

        <!--X------ upper navigation --------X-->
    </header>
    <div class="side-navigation">
        <ul class="navbar-nav">

            <li class="nav-item" title="Home">
                <a href="index.php" class="mk-flex">
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
                <li class="nav-item active" title="Playlists">
                    <a href="<?= $dir_lvl ?>playlists.php" class="mk-flex">
                        <i class="fa fa-music"></i>
                        <span>Playlists</span>
                    </a>
                </li>
            <?php } ?>

            <?php if (isset($_SESSION['ID'])) { ?>
                <li class="nav-item" title="My dashboard">
                    <a href="<?= $dir_lvl ?>dashboard.php" class="mk-flex">
                        <i class="material-icons">dashboard</i>
                        <span>Dashboard</span>
                    </a>
                </li>
            <?php } ?>

            <?php if (isset($_SESSION['ID'])) { ?>
                <?php if ($user_type == 'team') { ?>
                    <li class="nav-item" title="Source and find the artist you are looking for">
                        <a href="<?= $dir_lvl ?>find-artist.php" class="mk-flex">
                            <i class="fa fa-user-tag"></i>
                            <span>Find artists</span>
                        </a>
                    </li>
                <?php } else {
                ?>
                    <li class="nav-item" title="emnet studios">
                        <a href="./emnetstudio/index.php" class="mk-flex">
                            <i class="fa fa-microphone-alt"></i>
                            <span>Emnet Studios</span>
                        </a>
                    </li>
                <?php
                } ?>
            <?php } ?>


            <hr class="my-4">

            <div class="em-about mk-flex">
                <h4 class="text-light">About Us</h4>

                <p class="text-grey"> Emnet is a music streaming platform designed by emark ultimate to promote young talented artists.
                </p>
                <a href="https://www.emnetmusic.com/emnetdevelopers.html">Developer Team</a>
                <span class="right">&copy; emnet ultimate 2023</span>
            </div>
        </ul>

    </div>
    <!--X------ side navigation --------X-->

    <?php if (isset($_SESSION['uniqueID'])) { ?>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                var unReadIndcator = document.getElementById("unread_count");
                setInterval(() => {
                    $.ajax({
                        type: 'POST',
                        url: 'backend/inboxUpdate.php',
                        dataType: 'json',
                        success: function(data) {
                            unReadIndcator.textContent = data.count;
                            if (data.count == 0) {
                                if (unReadIndcator.classList.contains("bg-danger")) {
                                    unReadIndcator.classList.replace("bg-danger", "bg-success");
                                } else {
                                    unReadIndcator.classList.add("bg-success");
                                }
                            } else {
                                if (unReadIndcator.classList.contains("bg-success")) {
                                    unReadIndcator.classList.replace("bg-success", "bg-danger");
                                } else {
                                    unReadIndcator.classList.add("bg-danger");
                                }
                            }
                        }
                    });
                }, 1000)
            })
        </script>
    <?php } ?>
    <!--x----------------------- HEADER ------------------------x-->



    <!-- ----------------------- MAIN ------------------------ -->

    <main id="main" class="favourites">
        <div class="folder-playlist">
            <div class="music-layer">
                <div class="text-light my-4 text-center mk-abel" style="font-size: 113%">You have <span class="text-success"><?= subNumber($num_in_playlists) ?> song(s) </span>on your playlist</div>
                <form id="playlistForm">
                    <input type="number" name="userid" class="userid_list" value="<?= trim(htmlspecialchars($unique_id)) ?>">
                    <input type="number" name="songid" class="songid_list" value="">
                    <input type="text" name="songname" class="songname_list" value="">
                    <input type="text" name="artist" class="artist_list" value="">
                    <input type="text" name="posterid" class="poster_list" value="">
                </form>

                <div class="sort-button mb-4">
                    <div class="text-light">Sort By:
                        <input type="checkbox" name="" id="check_arr">
                        <button class="btn btn-dark arrangeBtn" value="">None</button>
                    </div>
                </div>


                <div class="all-songs">

                    <div class="songs" id="favoriteSongs">
                        <?php
                        while ($playlist_data = mysqli_fetch_assoc($query_playlists)) {
                            $query_others = mysqli_query($conn, "SELECT * FROM songs WHERE song_id = {$playlist_data['song_id']} ");
                            $fetch_others = mysqli_fetch_assoc($query_others);
                        ?>
                            <div class="song-item ambientDiv" index="" id="<?= $fetch_others['song_id'] ?>">
                                <div class="img-play">
                                    <img src="./audio_thumbnails/<?= $fetch_others['thumbnail'] ?>" alt="">
                                    <i class="fa playlistPlay fa-play-circle" id="<?= $playlist_data['song_id'] ?>"></i>
                                    <i class="fa fa-pause-circle"></i>
                                    <audio class="audio-item" src="./audios/<?= $fetch_others['audio_file'] ?>"></audio>
                                </div>
                                <h5>
                                    <div class="song-name mk-abel text-lowercase"><?= $playlist_data['song_name'] ?></div>
                                    <div class="artist mk-itim text-lowercase"><?= $playlist_data['artist'] ?></div>
                                </h5>

                                <div class="audio-duration text-success"></div>

                                <div class="em-remove-icon text-light p-4 btn">
                                    <i class="fa fa-dumpster"></i>
                                </div>


                                <div class="xb42dee">
                                    <?php echo "$domain/view.php?song=" . trim($playlist_data['song_id']) ?> </div>

                                <div class="xb42dee2">
                                    <?php echo "https://emnetmusic.com/artist_page.php?poster_id=" . htmlspecialchars(trim($playlist_data['poster_id'])) ?>
                                </div>
                                <!-- 
                                ************
                                ************  TO COPY
                                ************
                             -->
                                <?= "<span class='poster_id'> " . $playlist_data['poster_id'] . " </span>" ?>

                                <!-- 
                                ************
                                ************ TO COPY
                                ************
                             -->
                            </div>
                        <?php } ?>

                    </div>
                </div>

            </div>


    </main>
    <!-- ---------Modals---- -->
    <?php include "modals.php" ?>
    <!-- ---------Modals---- -->


    <!--x----------------------- FOOTER MASTER PLAY  ------------------------x-->

    <!-- ----- jQuery(insert for web letter) ----- -->
    <script src="./js/jquery3.6.0.js"></script>

    <!-- ----- popper(insert for web letter) ----- -->
    <script src="./js/popper1.160.min.js"></script>

    <!-- ----- Owl carousel(insert for web letter) ----- -->

    <!-- ----- boostrap(insert for web letter) ----- -->
    <script src="./js/bootstrap4.js"></script>

    <!-- ----- custom ----- -->

    <script src="./js/main.js"></script>
    <script src="./js/playlist.js"></script>

    <script>
        function searchFavs() {
            var input, filter, songItemsFilter, a, i;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            songItemsFilter = document.getElementsByClassName('song-item');

            // Loop through all list items, and hide those who don't match the search query

            for (i = 0; i < songItemsFilter.length; i++) {
                a = songItemsFilter[i].getElementsByTagName('h5')[0];

                if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    songItemsFilter[i].style.display = "";
                    songItemsFilter[i].style.marginTop = "0";
                } else {
                    songItemsFilter[i].style.display = "none";
                }
            }
        }

        var removeBtn = document.querySelectorAll(".em-remove-icon");
        var formRemove = document.getElementById("playlistForm");
        var songIdRemove = document.querySelector(".songid_list");
        var songnameRemove = document.querySelector(".songname_list");
        var artistRemove = document.querySelector(".artist_list");

        formRemove.onsubmit = (e) => {
            e.preventDefault();
        }
        for (let i = 0; i < removeBtn.length; i++) {
            removeBtn[i].onclick = function() {
                songIdRemove.value = removeBtn[i].parentElement.id;
                songnameRemove.value = removeBtn[i].parentElement.querySelector(".song-name").innerText;
                artistRemove.value = removeBtn[i].parentElement.querySelector(".artist").innerText;

                let conf = confirm(`Are you sure you want to remove ${songnameRemove.value} from favourites`);

                if (conf) {
                    let xhr = new XMLHttpRequest();
                    xhr.open('POST', 'backend/playlistRemove.php', true);
                    xhr.onload = () => {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                let data = xhr.response;
                                if (data == "removed from favourites") {
                                    alert(data);
                                    location.href = window.location;
                                } else {
                                    if (data != "") {
                                        alert(data)
                                    }
                                }
                            }
                        }
                    }
                    xhr.onerror = function() {
                        messageInfo("Request error...");
                    }
                    // we have to send the form data through ajax to php
                    let formData = new FormData(formRemove);
                    xhr.send(formData); //sending the form to php
                } else {
                    console.log(false);
                }
            }
        }
    </script>

</body>