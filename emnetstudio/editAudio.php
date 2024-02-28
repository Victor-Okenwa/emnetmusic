<?php

/*________________-_____________________|
# Here a creator can upload new music file
* This is the main file
|_________________-_____________________*/

include "access.php";

if (isset($_GET['songID']) && $_GET['songID'] != "") {
    $song_edit_id = $conn->real_escape_string($_GET['songID']);

    $query_edit_exists = mysqli_query($conn, "SELECT * FROM songs WHERE song_id = $song_edit_id AND creator_id = '{$_SESSION['uniqueID']}' LIMIT 1");

    if (mysqli_num_rows($query_edit_exists) > 0) {
        $fetch_edit = mysqli_fetch_assoc($query_edit_exists);
    } else {
        die("
    <body class='' style='background: rgba(196, 9, 9, 0.837);'>
        <h1 align='center' style='margin-top: 50vh'>😥 No Song found <a href='audios.php'>click here</a> to redirect</h1>
    <body>
    ");
    }
} else {
    die("
    <body class='' style='background: rgba(196, 9, 9, 0.837);'>
        <h1 align='center' style='margin-top: 50vh'>😥 No Song found <a href='audios.php'>click here</a> to redirect</h1>
    <body>
    ");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php include "./meta/header.php" ?>
    <style>
        #tags {
            max-width: 700px !important;
            overflow-x: auto;
        }

        .tagstyle {
            background-color: #333;
            padding: 3px;
            color: #fff;
            border-radius: 4px;
            margin: 2px 4px;
        }
    </style>

</head>

<body id="page-top" class="bg-light overflow-auto">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php if ($nums_creators > 0) {

            include "./meta/sidebar.php";
        ?>

        <?php  } else {
        } ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content" class="bg-dark text-light opacity-25">

                <?php include "./meta/topbar.php" ?>

                <div class="loader-div" style="position: absolute; display: none; background: rgba(0,0,0,0.88); z-index: 2000; height: 100%; width: 100%; align-items: center; flex-direction: column;  backface-visibility: blur(50px); padding-top: 20%;">

                    <div class="dots">
                        <span style="--i:1;"></span>
                        <span style="--i:2;"></span>
                        <span style="--i:3;"></span>
                        <span style="--i:4;"></span>
                        <span style="--i:5;"></span>
                        <span style="--i:6;"></span>
                        <span style="--i:7;"></span>
                        <span style="--i:8;"></span>
                        <span style="--i:9;"></span>
                        <span style="--i:10;"></span>
                    </div>

                    <div class="d-flex flex-column" style="z-index: 15;">
                        <h5 id="upload_info">Updating...</h5>
                        <progress id="uploadProgress" class="progress progress-bar animated progress-bar-animated" value="0" max="100"></progress>
                        <h5 class="progress_text text-light text-center"></h5>
                        <button id="cancel-button" class="btn btn-danger mt-3"><i class="fas fa-times-circle"></i> Cancel</button>
                    </div>
                </div>

                <div class="container-fluid mt-5 pt-5 px-0">
                    <div class="" id="result" style="display: none; transition: 1s ease;"></div>

                    <div class="tab-content rounded-bottom px-0">
                        <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-789">
                            <!-- method="POST" class="row g-3 was-validated" id="audioForm" enctype="multipart/form-data" -->

                            <form action="editAudioApi.php">
                                <input type="number" id="songid" value="<?= $song_edit_id ?>" hidden required>

                                <div class="col-12 d-flex flex-column mb-5">
                                    <label class="form-label col-12" for="songName">Song name</label>
                                    <div class="d-flex">
                                        <input class="form-control" id="songName" type="text" value="<?= $fetch_edit['song_name']  ?>" name="songname" required>
                                        <button class="btn mr-1" type="button" id="updateSongName" style="background:#333; color: white;">
                                            <i class="fa fa-upload"></i>
                                            <span class="spinner-grow spinner-grow-sm" style="display: none;"></span>
                                        </button>
                                    </div>
                                </div>

                                <div class="col-12 d-flex flex-column mb-5">
                                    <label class="form-label col-12" for="artist">Artist(s) seperate with ( x )</label>
                                    <div>
                                        <div>
                                            <input class="form-control" type="text" value="<?= $fetch_edit['artist']  ?>" id="artist" name="artist" required>
                                            <div id="tags" class="d-flex flex-row">
                                                <span class="small ms-1">Name shows here please seperate with a space then followed with an x then a space if ther are multiple artists</span>
                                            </div>
                                        </div>

                                        <button class="btn d-block mt-2" type="button" id="updateArtist" style="background:#333; color: white;">
                                            <i class="fa fa-upload"></i>
                                            <span class="spinner-grow spinner-grow-sm" style="display: none;"></span>
                                        </button>
                                    </div>

                                </div>

                                <div class="col-12 form-group">
                                    <label class="form-label" for="genre">Genre</label>

                                    <div class="mb-5">
                                        <select class="form-select form-control" id="genre" name="genre" required>
                                            <option selected="" value="<?= $fetch_edit['genre']  ?>"><?= $fetch_edit['genre']  ?></option>
                                            <option value="Afro Pop">Afro Beat</option>
                                            <option value="Alternative rock">Alternative rock</option>
                                            <option value="Amapiano">Amapiano</option>
                                            <option value="Ambient music">Ambient music</option>
                                            <option value="Blues">Blues</option>
                                            <option value="Classical music">Classical music</option>
                                            <option value="Contemporary R&B">Contemporary R&B</option>
                                            <option value="Country music">Country music</option>
                                            <option value="Dance music">Dance music</option>
                                            <option value="Disco">Disco</option>
                                            <option value="Dubstep">Dubstep</option>
                                            <option value="Easy listening">Easy listening</option>
                                            <option value="Electro">Electro</option>
                                            <option value="Electronic dance music">Electronic dance music</option>
                                            <option value="Electronic music">Electronic music</option>
                                            <option value="Emo">Emo</option>
                                            <option value="Folk music">Folk music</option>
                                            <option value="Funk">Funk</option>
                                            <option value="Gospel">Gospel</option>
                                            <option value="Grunge">Grunge</option>
                                            <option value="Heavy metal">Heavy metal</option>
                                            <option value="Hip Hop">Hip hop</option>
                                            <option value="House music">House music</option>
                                            <option value="Indie rock">Indie rock</option>
                                            <option value="Industrial music">Industrial music</option>
                                            <option value="Instrumental">Instrumental</option>
                                            <option value="Jazz">Jazz</option>
                                            <option value="K-pop">K-pop</option>
                                            <option value="Latin music">Latin music</option>
                                            <option value="Latin pop">Latin pop</option>
                                            <option value="Musical theatre">Musical theatre</option>
                                            <option value="New-age music">New-age music</option>
                                            <option value="Opera">Opera</option>
                                            <option value="Pop music">Pop music</option>
                                            <option value="Pop rock">Pop rock</option>
                                            <option value="Punk rock">Punk rock</option>
                                            <option value="Rapping">Rapping</option>
                                            <option value="Reggae">Reggae</option>
                                            <option value="Rock">Rock</option>
                                            <option value="Rhythm and blues">Rhythm and blues</option>
                                            <option value="Singing">Singing</option>
                                            <option value="Ska">Ska</option>
                                            <option value="Soul music">Soul music</option>
                                            <option value="Techno">Techno</option>
                                            <option value="Trance music">Trance music</option>
                                            <option value="Vocal music">Vocal music</option>
                                            <option value="World music">World music</option>
                                        </select>
                                        <button class="btn d-block mt-2" type="button" id="updateGenre" style="background:#333; color: white;">
                                            <i class="fa fa-upload"></i>
                                            <span class="spinner-grow spinner-grow-sm" style="display: none;"></span>
                                        </button>
                                    </div>
                                </div>


                                <div class="form-group col-md-6 col-sm-12 mb-2">
                                    <div class="form-check ">
                                        <label for="agelimit1">For all ages</label>
                                        <input class="form-check-input ml-2" style="cursor: pointer; scale: 1.2; accent-color: green" type="radio" id="agelimit1" name="agelimit" value="0" <?= $fetch_edit['age_limit'] == 0 ? "checked " : ""  ?> required>
                                    </div>

                                    <div class="form-check">
                                        <label for="agelimit2">18+</label>
                                        <input class="form-check-input ml-2" style="cursor: pointer; scale: 1.2; accent-color: green" type="radio" id="agelimit2" name="agelimit" value="1" <?= $fetch_edit['age_limit'] == 1 ? "checked " : ""  ?> required>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <div class="form-check">
                                        <label class="form-check-label" for="remix1">Not a remix</label>
                                        <input class="form-check-input ml-2" style="cursor: pointer; scale: 1.2; accent-color: green" id="remix1" type="radio" name="remix" value="0" <?= $fetch_edit['remix'] == 0 ? "checked " : ""  ?> required>
                                    </div>

                                    <div class="form-check">
                                        <label class="form-check-label" for=" remix2">Remix of a song</label>
                                        <input class="form-check-input ml-2" style="cursor: pointer; scale: 1.2; accent-color: green" id="remix2" type="radio" name="remix" value="1" <?= $fetch_edit['remix'] == 1 ? "checked " : ""  ?>required>
                                    </div>
                                </div>

                                <button class="btn mb-5" type="button" id="updateLimit" style="background:#333; color: white;">
                                    <i class="fa fa-upload"></i>
                                    <span class="spinner-grow spinner-grow-sm" style="display: none;"></span>
                                </button>

                                <div class="col-12 mb-5">
                                    <label class="form-label" for="thumbnailInput">New Thumbnail</label><br>
                                    <img id="previewImage" src="../audio_thumbnails/<?= $fetch_edit['thumbnail'] ?>" alt="" style="width: 80px; height: 80px">
                                    <input type="file" class="form-control border-2 border" id="thumbnailInput" name="thumbnail" accept="image/*" style="border: 2px dotted #fff; cursor:pointer; height: max-content;">
                                    <div class="d-block mt-2">
                                        <input type="checkbox" id="imgquality" style="accent-color:green; transform: scale(1.1);">
                                        <label for="imgquality">Reduce quality</label>
                                    </div>
                                    <div class="invalid-feedback">Only jpg, png, jpeg, jfif allowed, Maximum: 2mb </div>

                                    <div>
                                        <button class="btn" type="button" id="updateThumbnail" style="background:#333; color: white;">
                                            <i class="fa fa-upload"></i>
                                            <span class="spinner-grow spinner-grow-sm" style="display: none;"></span>
                                        </button>

                                        <span id="imageUploadTxt"></span>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label" for="audioInput">New Audio</label><br>
                                    <audio controls src="../audios/<?= $fetch_edit['audio_file'] ?>"></audio>
                                    <input type="file" class="form-control border-2 border py-2" id="audioInput" name="audio" accept="audio/*" style="border: 2px dotted #fff; cursor:pointer; height: max-content;">
                                    <div class="invalid-feedback">Only mp3, wav files allowed, Maximum: 8mb </div>

                                    <button class="btn my-2" type="button" id="updateAudio" style="background:#333; color: white;">
                                        <i class="fa fa-upload"></i>
                                        <span class="spinner-grow spinner-grow-sm" style="display: none;"></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <!-- End of Main Content -->

            <?php include "./meta/footer.php" ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php include "./meta/scripts.php" ?>
    <script>
        $(document).ready(() => {
            const audioForm = document.getElementById("audioForm"),
                loaderDiv = document.querySelector(".loader-div"),
                songId = document.getElementById("songid").value,
                info = document.getElementById("info"),
                artist = document.getElementById("artist"),
                tagsEl = document.getElementById("tags"),
                previewImg = document.getElementById("previewImage"),
                thumbnailInput = document.getElementById("thumbnailInput"),
                imgQualityInput = document.getElementById("imgquality");

            // new xhr request
            const xhr = new window.XMLHttpRequest();

            function outPutMessage(message, status) {
                result.classList = "";
                result.innerHTML = "";
                result.innerHTML = message;

                if (result.style.display == "none") {
                    result.style.display = "block";
                }

                if (status == 'error') {
                    result.classList = "alert alert-danger text-center";
                } else {
                    result.classList = "alert alert-success text-center";
                }

                setTimeout(() => {
                    result.innerHTML = "";
                    result.classList = "";
                    result.style.display = "none";
                }, 15000)
            }

            artist.addEventListener("keyup", (e) => {
                createTags(e.target.value)
            });


            function createTags(input) {
                const tags = input.split(' x ').filter(tag => tag.trim() !== ' ').map(tag => tag.trim());

                tagsEl.innerText = "";

                for (let i = 0; i < tags.length; i++) {
                    const tagEl = document.createElement('span');
                    tagEl.classList.add('tagstyle');
                    tagEl.innerText = tags[i];
                    tagsEl.appendChild(tagEl);
                }
            }
            if (artist.value.length > 0) {
                createTags(artist.value)
            }

            $("#updateSongName").click(() => {
                var songName = $('#songName').val();

                if (songName !== "") {
                    $.ajax({
                        url: 'editAudioApi.php',
                        method: "POST",
                        timeout: 50000,
                        data: {
                            songid: songId,
                            songname: songName,
                        },
                        beforeSend: function() {
                            $("#updateSongName i").hide();
                            $("#updateSongName span").show();
                        },
                        complete: function() {
                            $("#updateSongName i").show();
                            $("#updateSongName span").hide();
                        },
                        success: function(response) {
                            if (response.status == 'error') {
                                $(".scroll-to-top").click();
                                outPutMessage(response.message, response.status);
                            } else {
                                outPutMessage(response.message, response.status);
                            }
                        },
                        error: function(xhr, status, error) {
                            xhr.abort();
                            if (status == 'timeout') {
                                outPutMessage("Song name timed out", 'error');
                            } else {
                                outPutMessage("Error occured during song name update", 'error');
                                $("#updateSongName i").show();
                                $("#updateSongName span").hide();
                            }
                        },
                    });

                } else {
                    outPutMessage("Song name is empty", 'error');
                    $(".scroll-to-top").click();
                }

            });

            $("#updateArtist").click(() => {
                var artist = $('#artist').val();
                if (artist !== "") {
                    $.ajax({
                        url: 'editAudioApi.php',
                        method: "POST",
                        timeout: 50000,
                        data: {
                            songid: songId,
                            artist: artist,
                        },
                        beforeSend: function() {
                            $("#updateArtist i").hide();
                            $("#updateArtist span").show();
                        },
                        complete: function() {
                            $("#updateArtist i").show();
                            $("#updateArtist span").hide();
                        },
                        success: function(response) {
                            if (response.status == 'error') {
                                $(".scroll-to-top").click();
                                outPutMessage(response.message, response.status);
                            } else {
                                outPutMessage(response.message, response.status);
                            }
                        },
                        error: function(xhr, status, error) {
                            xhr.abort();
                            $("#updateArtist i").show();
                            $("#updateArtist span").hide();
                            if (status == 'timeout') {
                                outPutMessage("Artist name timed out", 'error');
                            } else {
                                outPutMessage("Error occured during artist update", 'error');
                            }
                        },
                    })

                } else {
                    outPutMessage("Artist name is empty", 'error');
                    $(".scroll-to-top").click();
                }
            });

            $("#updateGenre").click(() => {
                var genre = $('#genre').val();
                if (genre !== "") {
                    $.ajax({
                        url: 'editAudioApi.php',
                        method: "POST",
                        timeout: 50000,
                        data: {
                            songid: songId,
                            genre: genre,
                        },
                        beforeSend: function() {
                            $("#updateGenre i").hide();
                            $("#updateGenre span").show();
                        },
                        complete: function() {
                            $("#updateGenre i").show();
                            $("#updateGenre span").hide();
                        },
                        success: function(response) {
                            if (response.status == 'error') {
                                $(".scroll-to-top").click();
                                outPutMessage(response.message, response.status);
                            } else {
                                outPutMessage(response.message, response.status);
                            }
                        },
                        error: function(xhr, status, error) {
                            xhr.abort();
                            $("#updateGenre i").show();
                            $("#updateGenre span").hide();
                            if (status == 'timeout') {
                                outPutMessage("Genre timed out", 'error');
                            } else {
                                outPutMessage("Erroe occured during genre update", 'error');
                            }
                        },
                    });
                }
            });

            $("#updateLimit").click(() => {
                var agelimit = $('input[name=agelimit]:checked').val();
                var remix = $('input[name=remix]:checked').val();

                $.ajax({
                    url: 'editAudioApi.php',
                    method: "POST",
                    timeout: 50000,
                    data: {
                        songid: songId,
                        agelimit: agelimit,
                        remix: remix,
                    },
                    beforeSend: function() {
                        $("#updateLimit i").hide();
                        $("#updateLimit span").show();
                    },
                    complete: function() {
                        $("#updateLimit i").show();
                        $("#updateLimit span").hide();
                    },
                    success: function(response) {
                        if (response.status == 'error') {
                            $(".scroll-to-top").click();
                            outPutMessage(response.message, response.status);
                        } else {
                            outPutMessage(response.message, response.status);
                        }
                    },
                    error: function(xhr, status, error) {
                        xhr.abort();
                        $("#updateLimit i").show();
                        $("#updateLimit span").hide();
                        if (status == 'timeout') {
                            outPutMessage("Limit and remix timed out", 'error');
                        } else {
                            outPutMessage("Error Remix update", 'error');
                        }
                    },
                });
            });

            let imageWidth,
                imageHeight;

            thumbnailInput.onchange = function(e) {
                const file = e.target.files[0];
                if (!file) return;
                if (file.type.startsWith('image/')) {
                    if (file.type == "image/jpg" || file.type == "image/png" || file.type == "image/jfif" || file.type == "image/gif" || file.type == "image/jpeg") {
                        if (file.size <= 2000000) {
                            previewImg.src = URL.createObjectURL(file);
                            previewImg.addEventListener("load", () => {
                                imageWidth = previewImg.naturalWidth;
                                imageHeight = previewImg.naturalHeight;
                            })
                        } else {
                            previewImg.src = "";
                            this.files[0] = "";
                            this.value = "";
                            outPutMessage("Thumbnail is larger than 2mb", 'error');
                            $(".scroll-to-top").click();
                        }
                    } else {
                        previewImg.src = "";
                        this.files[0] = "";
                        this.value = "";
                        outPutMessage("Only JPG, PNG, JFIF, JPEG and GIF files allowed", 'error');
                        $(".scroll-to-top").click();
                    }
                } else {
                    previewImg.src = "";
                    this.files[0] = "";
                    this.value = "";
                    outPutMessage("Thumbnail is not a valid image file", 'error');
                    $(".scroll-to-top").click();
                }
            }

            const resizeImage = function() {
                if (previewImg.src !== "") {
                    const canvas = document.createElement("canvas");
                    const ctx = canvas.getContext("2d");
                    const imgQuality = imgQualityInput.checked ? 0.6 : 0.8;
                    canvas.width = imageWidth;
                    canvas.height = imageHeight;
                    ctx.drawImage(previewImg, 0, 0, canvas.width, canvas.height);
                    const compressedImage = canvas.toDataURL("image/jpeg", imgQuality);
                    return compressedImage;
                } else {
                    return;
                }
            }

            $("#updateThumbnail").click(() => {
                var imagefileValue = thumbnailInput.value;
                var compressedImage = resizeImage();

                if (imagefileValue !== "") {

                    $.ajax({
                        url: "editAudioApi.php",
                        method: "POST",
                        timeout: 100000,

                        data: {
                            songid: songId,
                            thumbnail: compressedImage,
                        },
                        xhr: function() {
                            xhr.upload.addEventListener('progress', function(e) {
                                if (e.lengthComputable) {
                                    const percent = Math.round((e.loaded / e.total) * 100);
                                    $('#imageUploadTxt').text(percent + '% uploaded');
                                }
                            });
                            return xhr;
                        },
                        beforeSend: function() {
                            $("#updateThumbnail i").hide();
                            $("#updateThumbnail span").show();
                        },
                        complete: function() {
                            $("#updateThumbnail i").show();
                            $("#updateThumbnail span").hide();
                        },
                        success: function(response) {
                            if (response.status == 'error') {
                                outPutMessage(response.message, response.status);
                                $(".scroll-to-top").click();
                            } else {
                                outPutMessage(response.message, response.status);
                                $('#imageUploadTxt').text(response.message);
                                setTimeout(() => {
                                    $('#imageUploadTxt').text("");
                                }, 5000)
                            }
                        },
                        error: function(xhr, status, error) {
                            xhr.abort();
                            if (status == 'timeout') {
                                outPutMessage("Image update timed out", 'error');
                                $("#updateThumbnail i").show();
                                $("#updateThumbnail span").hide();
                                $('#imageUploadTxt').text("");
                                $(".scroll-to-top").click();
                            } else {
                                outPutMessage("Error occured during upload", 'error');
                                $("#updateThumbnail i").show();
                                $("#updateThumbnail span").hide();
                                $('#imageUploadTxt').text("");
                                $(".scroll-to-top").click();
                            }
                        },
                    });
                } else {
                    outPutMessage("Thumbnail Input is empty", 'error');
                    $(".scroll-to-top").click();
                }
            });


            $("#updateAudio").click(() => {
                const audio = document.getElementById("audioInput");
                if (audio.value != "") {
                    if (audio.files[0].type.startsWith("audio/")) {
                        if (audio.files[0].size <= 8000000) {
                            var audio_file = audio.files[0];
                            const formData = new FormData();
                            formData.set('audio', audio.files[0]);
                            formData.set('songid', songId);
                            $.ajax({
                                url: "editAudioApi.php",
                                method: "POST",
                                processData: false,
                                contentType: false,
                                timeout: 500000,
                                data: formData,
                                xhr: function() {
                                    xhr.upload.addEventListener('progress', function(e) {
                                        if (e.lengthComputable) {
                                            const percent = Math.round((e.loaded / e.total) * 100);
                                            $('#uploadProgress').val(percent);
                                            $('.progress_text').text(percent + '% uploaded');
                                        }
                                    });
                                    return xhr;
                                },
                                beforeSend: function() {
                                    loaderDiv.style.display = 'flex';
                                    $(".scroll-to-top").click();
                                    $("#updateAudio i").hide();
                                    $("#updateAudio span").show();
                                },
                                complete: function() {
                                    loaderDiv.style.display = 'none';
                                    $('#uploadProgress').val(0);
                                    $('.progress_text').text("");
                                    $("#updateAudio i").show();
                                    $("#updateAudio span").hide();
                                },
                                success: function(response) {
                                    $(".scroll-to-top").click();
                                    if (response.status == 'error') {
                                        outPutMessage(response.message, response.status);
                                    } else {
                                        outPutMessage(response.message, response.status);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    xhr.abort();
                                    console.log(error, status);
                                    if (status == 'timeout') {
                                        outPutMessage("Audio update timed out", 'error');
                                        $("#updateAudio i").show();
                                        $("#updateAudio span").hide();
                                        $(".scroll-to-top").click();
                                    } else {
                                        outPutMessage("Error occured during upload", 'error');
                                        $("#updateAudio i").show();
                                        $("#updateAudio span").hide();
                                        $(".scroll-to-top").click();
                                    }
                                },
                            });
                        } else {
                            outPutMessage("File is larger than 8mb", 'error');
                            $(".scroll-to-top").click();
                        }

                    } else {
                        outPutMessage("File is not a valid audio", 'error');
                        $(".scroll-to-top").click();
                    }
                } else {
                    outPutMessage("Audio Input is empty", 'error');
                    $(".scroll-to-top").click();
                }
            });

            $('#cancel-button').click(function() {
                if (xhr) {
                    xhr.abort(); // Abort the ongoing upload
                    loaderDiv.style.display = 'none';
                    $('#uploadProgress').val(0);
                    $('.progress_text').text("");
                    outPutMessage('Upload aborted', 'error');
                    $(".scroll-to-top").click();
                }
            });


            function ajaxRequest() {
                const audio = document.getElementById("audioInput");
                const formData = new FormData(audioForm);
                formData.set('songname', $('input[name="songname"]').val());
                formData.set('artist', $('input[name="artist"]').val());
                formData.set('genre', $('select[name="genre"]').val());
                formData.set('agelimit', $('input[name="agelimit"]:checked').val());
                formData.set('remix', $('input[name="remix"]:checked').val());

                if (previewImg.src !== "") {
                    formData.set('thumbnail', resizeImage())
                } else {
                    formData.set('thumbnail', '');
                }

                if (audio.value != "") {
                    console.log(audio.files[0])
                    formData.set('audio', audio.files[0]);
                    if (audio.type.startsWith("audio/") == true) {
                        if (audio.size > 8000000) {
                            outPutMessage("Audio file is larger than 8mb", 'error');
                            $(".scroll-to-top").click();
                            return;
                        }
                        outPutMessage("Audio file is not a valid audio file", 'error');
                        $(".scroll-to-top").click();
                        return;
                    }
                } else {
                    formData.set('audio', '');
                }

                $.ajax({
                    url: 'editAudioApi.php',
                    type: 'POST',
                    dataType: 'json',
                    data: formData,
                    processData: false,
                    contentType: false,
                    timeout: 400000,

                    beforeSend: function() {
                        loaderDiv.style.display = 'flex';
                        $(".scroll-to-top").click();
                    },
                    xhr: function(e) {
                        xhr.upload.addEventListener('progress', function(e) {
                            if (e.lengthComputable) {
                                const percent = Math.round((e.loaded / e.total) * 100);
                                $('#uploadProgress').val(percent);
                                $('.progress_text').text(percent + '% uploaded');
                            }
                        });
                        return xhr;
                    },
                    complete: function() {
                        loaderDiv.style.display = 'none';
                        $('#uploadProgress').val(0);
                        $('.progress_text').text("");
                    },
                    success: function(response) {
                        $(".scroll-to-top").click();
                        if (response.status == 'error') {
                            outPutMessage(response.message, response.status);
                        } else {
                            outPutMessage(response.message, response.status);
                            audioForm.reset();
                            previewImg.src = "";
                        }
                    },
                    error: function(xhr, status, error) {
                        $(".scroll-to-top").click();
                        loaderDiv.style.display = 'none';
                        $('#uploadProgress').val(0);
                        $('.progress_text').text("");
                        if (status === 'timeout') {
                            xhr.abort();
                            outPutMessage('Request timed out!', 'error');
                        } else {}
                    },

                });

                $('#cancel-button').click(function() {
                    if (xhr) {
                        xhr.abort(); // Abort the ongoing upload
                        loaderDiv.style.display = 'none';
                        $('#uploadProgress').val(0);
                        $('.progress_text').text("");
                        outPutMessage('Upload aborted', 'error');
                        $(".scroll-to-top").click();
                    }
                });
            }

            // Function to handle offline status
            function handleOffline() {
                outPutMessage("Your Device is offline", 'error');
                // $(".scroll-to-top").click();
            }

            // Function to handle online status
            function handleOnline() {
                outPutMessage("Back online", 'success');
            }

            if (!navigator.onLine) {
                handleOffline();
            }

            // // Set up event listeners for online/offline events
            window.addEventListener('offline', handleOffline);
            window.addEventListener('online', handleOnline);

            $("#audioForm").submit(function(event) {
                event.preventDefault(); // Prevent the form from submitting normally
                ajaxRequest();
            })
        })
    </script>
</body>


</html>