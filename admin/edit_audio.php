<?php
require "./assets/access.php";
$songID = trim(strip_tags(stripslashes($_GET['songID'])));

if (empty($songID)) {
    echo "<h1>Not found</h1>";
    die(header("Location: audios.php"));
} elseif (!isInDb($songID, 'song_id', 'songs')) {
    echo "<h1>Not found</h1>";
    die(header("Location: audios.php"));
} elseif (!existsWith($songID, $admin_unique_id, 'song_id', 'admin_id', 'songs')) {
    echo "<h1>This post does not belong to you</h1>";
    die(header("Location: audios.php"));
} else {
    $query_song = $conn->query("SELECT * FROM songs WHERE song_id = $songID AND admin_id = '$admin_unique_id'");
    $data_song = $query_song->fetch_assoc();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "./assets/vendors.html" ?>
    <?php require_once "./assets/datatables.html" ?>
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
    <script>
        function scrollToPosition() {
            document.getElementById("top_page").scrollIntoView({
                behavior: "smooth"
            });
        }
    </script>
</head>

<body>
    <?php require_once "./assets/navbar.php" ?>
    <div id="top_page"></div>

    <div class="loader-div">

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

        <div class="details">
            <h5 class="text-light">Uploading...</h5>
            <progress id="uploadProgress" class="progress progress-bar" value="0" max="100"></progress>
            <p class="progress_text text-light mt-1"></p>
            <button id="cancel-button" class="btn btn-danger mt-3"><i class="fas fa-times-circle"></i> Cancel</button>
        </div>
    </div>

    <main class="container-fluid">
        <div class="card">
            <div class="card-header bg-secondary text-light">
                <h4>Post an audio</h4>
            </div>
            <div class="card-body card-header">
                <div id="output" class="d-none" style="font-size: 17px"></div>
                <?php if ($data_setting['crt_audio'] == 1 || $data_admin['admin_rank'] == "super admin") { ?>

                    <form action="editAudioApi.php">
                        <input type="number" class="field_1" id="songid" value="<?= $songID ?>" hidden required>

                        <div class="col-12 d-flex flex-column mb-5">
                            <label class="form-label col-12" for="songname">Song name</label>
                            <div class="d-flex">
                                <input class="form-control" id="songname" type="text" value="<?= $data_song['song_name']  ?>" name="songname" required>
                                <button class="btn mr-1" type="button" id="updateSongName" style="background:#333; color: white;" onclick="simpleUpdate(this, $('#songid').val(), 'songname', $('#songname').val(), document.getElementById('output'))">
                                    <i class="fa fa-upload"></i>
                                    <span class="spinner-grow spinner-grow-sm d-none"></span>
                                </button>
                            </div>
                        </div>

                        <div class="col-12 d-flex flex-column mb-5">
                            <label class="form-label col-12" for="artist">Artist(s) seperate with ( x )</label>
                            <div>
                                <div>
                                    <input class="form-control" type="text" value="<?= $data_song['artist']  ?>" id="artist" name="artist" required>
                                    <div id="tags" class="d-flex flex-row">
                                        <span class="small ms-1">Name shows here please seperate with a space then followed with an x then a space if ther are multiple artists</span>
                                    </div>
                                </div>

                                <button class="btn d-block mt-2" type="button" id="updateArtist" style="background:#333; color: white;" onclick="simpleUpdate(this, $('#songid').val(), 'artist', $('#artist').val(), document.getElementById('output'))">
                                    <i class="fa fa-upload"></i>
                                    <span class="spinner-grow spinner-grow-sm d-none"></span>
                                </button>
                            </div>

                        </div>

                        <div class="col-12 form-group">
                            <label class="form-label" for="genre">Genre</label>

                            <div class="mb-5">
                                <select class="form-select form-control" id="genre" name="genre" required>
                                    <option selected="" value="<?= $data_song['genre']  ?>"><?= $data_song['genre']  ?></option>
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
                                <button class="btn d-block mt-2" type="button" id="updateGenre" style="background:#333; color: white;" onclick="simpleUpdate(this, $('#songid').val(), 'genre', $('#genre').val(), document.getElementById('output'))">
                                    <i class="fa fa-upload"></i>
                                    <span class="spinner-grow spinner-grow-sm d-none"></span>
                                </button>
                            </div>
                        </div>


                        <div class="form-group col-md-6 col-sm-12 mb-2">
                            <div class="form-check ">
                                <label for="agelimit1">For all ages</label>
                                <input class="form-check-input ml-2" style="cursor: pointer; scale: 1.2; accent-color: green" type="radio" id="agelimit1" name="agelimit" value="0" <?= $data_song['age_limit'] == 0 ? "checked " : ""  ?> required>
                            </div>

                            <div class="form-check">
                                <label for="agelimit2">18+</label>
                                <input class="form-check-input ml-2" style="cursor: pointer; scale: 1.2; accent-color: green" type="radio" id="agelimit2" name="agelimit" value="1" <?= $data_song['age_limit'] == 1 ? "checked " : ""  ?> required>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <div class="form-check">
                                <label class="form-check-label" for="remix1">Not a remix</label>
                                <input class="form-check-input ml-2" style="cursor: pointer; scale: 1.2; accent-color: green" id="remix1" type="radio" name="remix" value="0" <?= $data_song['remix'] == 0 ? "checked " : ""  ?> required>
                            </div>

                            <div class="form-check">
                                <label class="form-check-label" for=" remix2">Remix of a song</label>
                                <input class="form-check-input ml-2" style="cursor: pointer; scale: 1.2; accent-color: green" id="remix2" type="radio" name="remix" value="1" <?= $data_song['remix'] == 1 ? "checked " : ""  ?>required>
                            </div>
                        </div>

                        <button class="btn mb-5" type="button" id="updateLimit" style="background:#333; color: white;">
                            <i class="fa fa-upload"></i>
                            <span class="spinner-grow spinner-grow-sm d-none"></span>
                        </button>

                        <div class="col-12 mb-5">
                            <label class="form-label" for="thumbnailInput">New Thumbnail</label><br>
                            <img id="previewImage" src="../audio_thumbnails/<?= $data_song['thumbnail'] ?>" alt="" style="width: 80px; height: 80px">
                            <input type="file" class="form-control border-2 border" id="thumbnailInput" name="thumbnail" accept="image/*" style="border: 2px dotted #fff; cursor:pointer; height: max-content;">
                            <div class="d-block mt-2">
                                <input type="checkbox" id="imgquality" style="accent-color:green; transform: scale(1.1);">
                                <label for="imgquality">Reduce quality</label>
                            </div>
                            <div class="invalid-feedback">Only jpg, png, jpeg, jfif allowed, Maximum: 2mb </div>

                            <div>
                                <button class="btn" type="button" id="updateThumbnail" style="background:#333; color: white;">
                                    <i class="fa fa-upload"></i>
                                    <span class="spinner-grow spinner-grow-sm d-none"></span>
                                </button>

                                <span id="imageUploadTxt"></span>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="audioInput">New Audio</label><br>
                            <audio controls src="../audios/<?= $data_song['audio_file'] ?>"></audio>
                            <input type="file" class="form-control border-2 border py-2" id="audioInput" name="audio" accept="audio/*" style="border: 2px dotted #fff; cursor:pointer; height: max-content;">
                            <div class="invalid-feedback">Only mp3, wav files allowed, Maximum: 8mb </div>

                            <button class="btn my-2" type="button" id="updateAudio" style="background:#333; color: white;">
                                <i class="fa fa-upload"></i>
                                <span class="spinner-grow spinner-grow-sm d-none"></span>
                            </button>
                        </div>
                    </form>

                <?php  } else {
                    echo "<h3 class='text-center my-5'> Audio creation was disallowed </h3>";
                }
                ?>
            </div>
        </div>
    </main>

    <?php require_once "./assets/footer.html" ?>
    <?php require_once "./assets/scripts.html" ?>

    <script>
        $(document).ready(() => {
            const loaderDiv = document.querySelector('.loader-div'),
                output = document.getElementById("output"),
                songId = document.getElementById("songid").value,
                artist = document.getElementById("artist"),
                tagsEl = document.getElementById("tags"),
                previewImg = document.getElementById("previewImage"),
                thumbnailInput = document.getElementById("thumbnailInput"),
                imgQualityInput = document.getElementById("imgquality");

            const xhr = new window.XMLHttpRequest();

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

            $("#updateLimit").click(() => {
                var agelimit = $('input[name=agelimit]:checked').val();
                var remix = $('input[name=remix]:checked').val();
                $.ajax({
                    url: 'api/edit_audio.php',
                    method: "POST",
                    timeout: 50000,
                    data: {
                        songid: songId,
                        type: 'limits',
                        ageLimit: agelimit,
                        remix: remix,
                    },
                    beforeSend: function() {
                        document.querySelector(`#updateLimit svg`).classList.add('d-none');
                        document.querySelector(`#updateLimit span`).classList.remove('d-none');
                    },
                    complete: function() {
                        document.querySelector(`#updateLimit svg`).classList.remove('d-none');
                        document.querySelector(`#updateLimit span`).classList.add('d-none');
                    },
                    success: function(response) {
                        if (response.status == 'error') {
                            scrollToPosition();
                            outputMessage(output, response.message, 5000, response.status);
                        } else {
                            outputMessage(output, response.message, 5000, response.status);
                        }
                    },
                    error: function(xhr, status, error) {
                        xhr.abort();
                        scrollToPosition();
                        if (status == 'timeout') {
                            outputMessage(output, `Limit and remix timed out`, 5000, 'error');
                        } else {
                            outputMessage(output, `Error occured during song name update`, 5000, 'error');
                            document.querySelector(`#updateLimit svg`).classList.remove('d-none');
                            document.querySelector(`#updateLimit span`).classList.add('d-none');
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
                            outputMessage(output, "Thumbnail is larger than 2mb", 5000, 'error');
                            scrollToPosition();
                        }
                    } else {
                        previewImg.src = "";
                        this.files[0] = "";
                        this.value = "";
                        outputMessage(output, "Only JPG, PNG, JFIF, JPEG and GIF files allowed", 5000, 'error');
                        scrollToPosition();
                    }
                } else {
                    previewImg.src = "";
                    this.files[0] = "";
                    this.value = "";
                    outputMessage(output, "Thumbnail is not a valid image file", 5000, 'error');
                    scrollToPosition();
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
                $('#imageUploadTxt').text("");

                if (imagefileValue !== "") {
                    $.ajax({
                        url: 'api/edit_audio.php',
                        method: "POST",
                        timeout: 100000,
                        data: {
                            songid: songId,
                            type: 'image',
                            value: compressedImage,
                        },
                        xhr: function() {
                            xhr.upload.addEventListener('progress', function(e) {
                                if (e.lengthComputable) {
                                    const percent = Math.round((e.loaded / e.total) * 100);
                                    $('#imageUploadTxt').html(percent + '% uploaded');
                                }
                            });
                            return xhr;
                        },
                        beforeSend: function() {
                            document.querySelector(`#updateLimit svg`).classList.add('d-none');
                            document.querySelector(`#updateLimit span`).classList.remove('d-none');

                        },
                        complete: function() {
                            document.querySelector(`#updateLimit svg`).classList.remove('d-none');
                            document.querySelector(`#updateLimit span`).classList.add('d-none');
                        },
                        success: function(response) {
                            if (response.status == 'error') {
                                scrollToPosition();
                                outputMessage(output, response.message, 5000, response.status);
                            } else {
                                outputMessage(output, response.message, 5000, response.status);
                                $('#imageUploadTxt').html(response.message);
                                setTimeout(() => {
                                    $('#imageUploadTxt').html("");
                                }, 5000);
                            }
                        },
                        error: function(xhr, status, error) {
                            xhr.abort();
                            scrollToPosition();
                            if (status == 'timeout') {
                                outputMessage(output, `Thumbnail update timed out`, 5000, 'error');
                            } else {
                                outputMessage(output, `Error occured during thumbnail update`, 5000, 'error');
                                document.querySelector(`#updateLimit svg`).classList.remove('d-none');
                                document.querySelector(`#updateLimit span`).classList.add('d-none');
                            }
                        },
                    });
                } else {
                    scrollToPosition();
                    outputMessage(output, "Thumbnail field is empty", 5000, 'error');
                }

            });

            $("#updateAudio").click(() => {
                const audio = document.getElementById("audioInput");
                if (audio.value != "") {
                    if (audio.files[0].type.startsWith("audio/")) {
                        if (audio.files[0].size <= 8000000) {
                            var audio_file = audio.files[0];
                            const formData = new FormData();
                            formData.set('type', 'audio');
                            formData.set('audio', audio.files[0]);
                            formData.set('songid', songId);

                            $.ajax({
                                url: "./api/edit_audio.php",
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
                                    loaderDiv.style.display = 'block';
                                    scrollToPosition();
                                },
                                complete: function() {
                                    loaderDiv.style.display = 'none';
                                    $('#uploadProgress').val(0);
                                    $('.progress_text').text("");
                                },
                                success: function(response) {
                                    if (response.status == 'error') {
                                        scrollToPosition();
                                        outputMessage(output, response.message, 5000, response.status);
                                    } else {
                                        outputMessage(output, response.message, 5000, response.status);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    xhr.abort();
                                    scrollToPosition();
                                    loaderDiv.style.display = 'none';
                                    $('#uploadProgress').val(0);
                                    $('.progress_text').text("");
                                    if (status === 'timeout') {
                                        outputMessage(output, 'Request timed out!', 5000, 'error');
                                    } else {
                                        outputMessage(output, 'An Error Occured', 5000, 'error');
                                    }
                                },

                            });

                            $('#cancel-button').click(function() {
                                if (xhr) {
                                    xhr.abort(); // Abort the ongoing upload
                                    loaderDiv.style.display = 'none';
                                    $('#uploadProgress').val(0);
                                    $('.progress_text').text("");
                                    outputMessage(output, 'Upload aborted', 5000, 'error');
                                    scrollToPosition();
                                }
                            });
                        } else {
                            outputMessage(output, "Audio file cannot be more than 8mb", 5000, 'error');
                            scrollToPosition();
                        }
                    } else {
                        outputMessage(output, "Only audio files allowed", 5000, 'error');
                        scrollToPosition();
                    }
                } else {
                    outputMessage(output, "Audio field is empty", 5000, 'error');
                    scrollToPosition();
                }
            });
        });
    </script>
</body>

</html>