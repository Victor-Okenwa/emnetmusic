<!-- upload new profile -->
<div class="modal fade" id="userProfile" tabindex="" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content active">
            <div class="modal-header">
                <?php echo (!empty($img)) ? "<i class='material-icons' style='cursor: pointer' data-toggle='modal' data-target='#deleteProfile'>delete</i>" : "" ?>

                <button type="button" class="btn btn-close" data-toggle="modal" data-target="#userProfile" data-bs-dismiss="modal" aria-label="Close"> <i class="fa fa-times"></i> </button>
            </div>


        </div>
    </div>
</div>
<!-- upload new profile -->


<div class="modal fade" id="logout" tabindex="" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index:10000">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-close" data-toggle="modal" data-target="#logout" data-bs-dismiss="modal" aria-label="Close"> <i class="fa fa-times"></i> </button>
            </div>

            <form id="logoutForm">
                <div class="modal-body">
                    <h4> Do you want to logout?</h4>
                    <input type="text" name="userID" id="logoutID" value="<?php echo $userid ?>" hidden>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#logout" data-bs-dismiss="modal">Close</button>
                    <button type="button" name="delete" class="btn btn-primary material-icons" id="logoutBtn">logout
                        <span class="spinner-grow spinner-grow-sm" style="display: none;" id="loaderLogout"></span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- logout modal -->

<div class="searchModal card mk-bg-dark">
    <div class="card-header border-bottom-light border-bottom-1 w-100" align="right">
        <div class="mr-auto"></div>
        <button class="btn border border-1 text-light button-dismiss">
            <div class="fa fa-times"></div>
        </button>
    </div>
    <div class="card-body" align="center">
        <form action="search.php" method="POST" id="searchForm" class="middle mk-flex row">

            <select name="selection" id="searchSelect" class="btn btn-light mr-1 mt-3">
                <option class="searchOption" value="">Song</option>
                <option class="searchOption" value="artist">Artist</option>
                <option class="searchOption" value="genre">Genre</option>
                <option class="searchOption" value="remix">Remix</option>
            </select>

            <div class="search mk-flex col-md-9 w-100 col-sm-12 rounded rounded-pill mt-3">
                <input type="search" name="search" id="search" class="em-search-box form-control py-2 px-3" placeholder="Search here ...">
                <button type="submit" class="btn search-icon1 material-icons" id="searchBtn" name="searchBtn">search</button>
            </div>
        </form>
    </div>
</div>

<div id="overlay-tooltip"></div>
<div class="mytooltip small animated--grow-in" id="tooltip">
    <input type="" disabled class="tooltip-text" id="tooltip-text" value="">
    <span class="fa fa-times" id="close" title="close"></span>
    <div class="tooltip-icons px-2 mk-flex">
        <h5>Share This music:</h5>
        <div class="share-icons">
            <button type="button" id="facebook" data-sharer="facebook" data-hashtag="emnetmusic" data-url="" title="Share via Facebook"><i class="fab fa-facebook-square f1"></i></button>

            <button type="button" id="twitter" data-sharer="twitter" data-title="" data-hashtags="emnetmusic, emarkultimate" data-url="" title="Share to twitter"><i class="fab fa-twitter f2"></i></button>

            <button type="button" id="whatsapp" data-sharer="whatsapp" data-title="Checkout this music i found on emnet, stream and follow" data-url="" title="Share via Whatsapp"><i class="fab fa-whatsapp f3"></i></button>

            <button type="button" id="linkedin" data-sharer="linkedin" data-url="" title="Share via Linkedin"><i class="fab fa-linkedin f4"></i></button>

            <button type="button" id="reddit" data-sharer="reddit" data-url="" title="Share via Reddit"><i class="fab fa-reddit-alien f5"></i></button>

        </div>

        <div class="copy-icon mk-flex">
            <div>OR</div>
            <span class="fa fa-copy d-block mb-2 btn" id="copy_link" title="copy link"></span>
        </div>

    </div>
</div>
<!--x----------------------- FOOTER MASTER PLAY ------------------------x-->

<footer id="play-footer" class="ambientDiv d-none">
    <audio id="audio_player" src=""></audio>
    <audio src="./tone/click_sound.mp3" id="tone-like"></audio>

    <div class="master-play text-light" id="">
        <div class="playingID" id=""></div>
        <button class="btn text-light resizer"><i class="fas fa-compress-alt"></i></button>
        <button class="btn text-light mobile-view"><i class="fa fa-angle-up"></i></button>
        <!-- TO WORK ON REAL TIME AUDIO WAVES -->
        <!-- <canvas id="beat-wave">

            </canvas> -->

        <div class="wave">
            <div class="wave1"></div>
            <div class="wave1"></div>
            <div class="wave1"></div>
        </div>

        <div class="image-container">
            <img src="" alt="Image" id="main-img" class="master-img">
        </div>

        <h5 id="title">
            <div class="artist"></div>
            <div class="song-name"></div>
        </h5>

        <div class="audio-contol">
            <div class="icon">
                <div class="first-icon-set">

                    <?php if (isset($_SESSION['uniqueID'])) {
                    ?>

                        <div class='set1' id="liker">
                            <i class='fa fa-heart btn' title='Like song'></i>
                            <span>Like</span>
                        </div>

                        <a class="set1 playlist-adder">
                            <i class="fa fa-plus-square btn" title="Add to playlist" id='addToList'></i>
                            <span class="">Add</span>
                        </a>

                        <a class="set1" id="share" title="share">
                            <i class="material-icons btn">share</i>
                            <span class="">Share</span>
                        </a>

                        <a class="set1" id="about_artist" title="About artist">
                            <i class="fa fa-user btn"></i>
                            <span class="">Artist</span>
                        </a>


                    <?php  } ?>
                </div>

                <div class="bar">
                    <input type="range" id="seek" min="0" value="0" max="100">
                    <div class="durations">
                        <span id="currentStart">0:00</span>
                        <span id="currentEnd">0:00</span>
                    </div>
                </div>

                <div class="second-icon-set">
                    <i class="material-icons repeat btn" id="main-audio-loop" title="loop song">loop</i>
                    <div class="main-player">
                        <i class="fa fa-step-backward home-play" id="back"></i>
                        <i class="fa fa-play" id="masterPlay"></i>
                        <i class="fa fa-step-forward home-play" id="next"></i>
                    </div>
                </div>


            </div>

        </div>


        <div class="vol">
            <div class="vol-main mk-flex">
                <i class="material-icons" id="vol_icon">volume_down</i>
                <input type="range" class="py-1" id="vol" min="0" step="0.1" value="0.5" max="1">
            </div>
            <div id="vol-txt" class="text-center small badge badge-pill">50%</div>
        </div>
    </div>

</footer>

<!--x----------------------- FOOTER MASTER PLAY  ------------------------x-->


<script src="./js/sharer.min.js"></script>
<script src="./js/jquery3.6.0.js"></script>

<script>
    // \\ LOGOUT SCRIPT // \\
    $(document).ready(function() {
        $('#logoutBtn').click(function() {
            var logoutID = $('#logoutID').val();
            if (logoutID !== "") {
                $.ajax({
                    type: "POST",
                    url: "backend/logout.php",
                    timeout: 30000,
                    data: {
                        logoutID: logoutID,
                    },
                    dataType: "text",
                    beforeSend: function() {
                        $('#loaderLogout').show();
                    },
                    success: function(data) {
                        if (data == "error") {
                            alert("Logout attempt failed");
                        } else if (data == "success") {
                            alert("Logged out!");
                            location.reload();
                        } else {
                            alert(data);
                        }
                    },

                    error: function(xhr, status, error) {
                        if (status === 'timeout') {
                            // Handle timeout error
                            alert('Request timed out!', 'error');
                            $('#loaderLogout').hide();
                        } else {
                            alert('Error:' + error, 'error');
                            $('#loaderLogout').hide();
                        }
                    },

                    complete: function() {
                        // Hide loaderLogout indicator if shown
                        $('#loaderLogout').hide();
                    }

                });
            }

        });
    });
</script>

<script>
    let xhr = new XMLHttpRequest();


    // For Playlist adder
    const addToList = document.getElementById("addToList");
    const like = document.getElementById("liker");
    const formList = document.getElementById("playlistForm");

    formList.onsubmit = (e) => {
        e.preventDefault();
    }

    addToList.onclick = () => {
        xhr.open('POST', 'backend/playlistAdd.php', true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    let data = xhr.response;
                    if (data == "added to playlist") {
                        addToList.title = data;
                        alert(data);
                    } else {
                        if (data != "") {
                            addToList.title = data;
                        }
                    }
                }
            }
        }
        xhr.onerror = function() {
            messageInfo("Network error...");
        }
        // we have to send the form data through ajax to php
        let formData = new FormData(formList);
        xhr.send(formData); //sending the form to php
    }


    liker.onclick = function() {
        let likeCondition = liker.querySelector("i").classList.contains("active")
        let userID = formList.querySelector(".userid_list").value;
        let songID = formList.querySelector(".songid_list").value;
        let posterID = formList.querySelector(".poster_list").value;
        let songName = formList.querySelector(".songname_list").value;

        if (likeCondition) {
            $.ajax({
                type: 'POST',
                url: 'backend/liker.php',
                data: {
                    like: 1,
                    userid: userID,
                    songid: songID,
                    posterid: posterID,
                    songname: songName
                },

                success: function(response) {
                    liker.querySelector("i").title = `${response} like(s)`;
                }
            })
        } else {
            $.ajax({
                type: 'POST',
                url: 'backend/liker.php',
                data: {
                    unlike: 0,
                    userid: userID,
                    songid: songID,
                    posterid: posterID,
                    songname: songName
                },

                success: function(response) {
                    liker.querySelector("i").title = `${response} like(s)`;
                }
            })
        }
    }
</script>