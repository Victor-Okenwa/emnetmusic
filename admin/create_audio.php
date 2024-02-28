<?php require "./assets/access.php" ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "./assets/vendors.html" ?>
    <style>
        .tagstyle {
            background: #333;
            color: #f1f1f1;
            border-radius: 20px;
            padding: 6px 10px;
            margin-top: .6em;
            box-shadow: 0px 3px 8px rgba(0, 0, 0, .2);
        }
    </style>
</head>

<body>
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
    <?php require_once "./assets/navbar.php" ?>
    <div id="top_page"></div>

    <main class="container-fluid">

        <div class="card">
            <div class="card-header bg-secondary text-light">
                <h4>Post an audio</h4>
            </div>
            <div class="card-body card-header">
                <div id="output" class="d-none" style="font-size: 17px"></div>
                <?php if ($data_setting['crt_audio'] == 1 || $data_admin['admin_rank'] == "super admin") { ?>

                    <form method="POST" class="row g-3 was-validated" id="audioForm">
                        <div class="col-md-4 col-sm-12">
                            <label class="form-label" for="songName">Song name</label>
                            <input class="form-control" id="songName" type="text" name="songname" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="artist">Artist(s) seperate with ( x )</label>
                            <input class="form-control" type="text" id="artist" name="artist" required>
                            <div id="tags" class="d-flex flex-row">
                                <span class="small ms-1">Name shows here please seperate with a space then followed with an x then a space if ther are multiple artists</span>
                            </div>
                        </div>

                        <div class="col-12 mb-5">
                            <label class="form-label" for="genre">Genre</label>
                            <select class="form-select form-control" id="genre" name="genre" required>
                                <option selected="" disabled="" value="">Choose a genre...</option>
                                <option value="Afro Beat">Afro Beat</option>
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
                        </div>

                        <div class="form-group col-md-6">
                            <div class="form-check">
                                <label for="agelimit1">For all ages</label>
                                <input id="agelimit1" type="radio" name="agelimit" required value="0" checked required>
                            </div>

                            <div class="form-check mb-3">
                                <label for="agelimit2">18+</label>
                                <input id="agelimit2" type="radio" name="agelimit" required value="1" required>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <div class="form-check">
                                <label for="remix1">Not a remix</label>
                                <input id="remix1" type="radio" name="remix" required value="0" checked required>
                            </div>

                            <div class="form-check mb-3">
                                <label for="remix2">Remix of a song</label>
                                <input id="remix2" type="radio" name="remix" required value="1" required>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <img id="previewImg" src="" width="70" height="70">
                            <label class="form-label" for="thumbnailInput">Upload thumbnail</label>
                            <input type="file" class="form-control" id="thumbnailInput" accept="image/*" name="thumbnail" required>
                            <div class="d-block mt-2">
                                <input type="checkbox" id="imgquality" style="accent-color:green; transform: scale(1.1);">
                                <label for="imgquality">Reduce quality</label>
                            </div>
                            <div class="invalid-feedback">Maximum of 2mb</div>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="audioInput">Upload Audio</label>
                            <input type="file" class="form-control" id="audioInput" name="audio" accept="audio/*" required>
                            <div class="invalid-feedback">Maximum of 8mb</div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary" type="submit" id="submitAudio" name="submitAudio" title="Send audio to emnet">
                                Submit form</button>
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
            const message = document.getElementById('output'),
                audioForm = document.getElementById("audioForm"),
                info = document.getElementById("info"),
                artist = document.getElementById("artist"),
                tagsEl = document.getElementById("tags"),
                loaderDiv = document.querySelector(".loader-div"),
                uploadProgress = document.getElementById('uploadProgress'),
                previewImg = document.getElementById("previewImg"),
                thumbnailInput = document.getElementById("thumbnailInput"),
                imgQualityInput = document.getElementById("imgquality"),
                submitAudio = document.getElementById("submitAudio");

            let xhr = new XMLHttpRequest();
            console.log(xhr);
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

            function scrollToPosition() {
                document.getElementById("top_page").scrollIntoView({
                    behavior: "smooth"
                });
            }

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
                            outputMessage(message, "Thumbnail is larger than 2mb", 5000, 'error');

                        }
                    } else {
                        previewImg.src = "";
                        this.files[0] = "";
                        this.value = "";
                        outputMessage(message, "Only JPG, PNG, JFIF, JPEG and GIF files allowed", 5000, 'error');
                        scrollToPosition();
                    }
                } else {
                    previewImg.src = "";
                    this.files[0] = "";
                    this.value = "";
                    outputMessage(message, "Thumbnail is not a valid image file", 5000, 'error');
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

            function ajaxRequest() {
                const audio = document.getElementById("audioInput").files[0];
                if ($("#songName").val() == "" || $("#artist").val() == "" || $("#genre").val() == "" || $("#audio").val() == "") {
                    outputMessage(message, "No field is allowed to be empty", 5000, 'error');
                    scrollToPosition();
                    return;
                }
                if (previewImg.src != "") {
                    if (audio.type.startsWith("audio/")) {
                        if (audio.size <= 8000000) {
                            const formData = new FormData(audioForm);
                            formData.set('songname', $('input[name="songname"]').val());
                            formData.set('artist', $('input[name="artist"]').val());
                            formData.set('genre', $('select[name="genre"]').val());
                            formData.set('agelimit', $('input[name="agelimit"]:checked').val());
                            formData.set('remix', $('input[name="remix"]:checked').val());
                            formData.set('thumbnail', resizeImage());
                            formData.set('audio', audio);

                            $.ajax({
                                url: 'api/new_audio.php',
                                method: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                timeout: 1000000,
                                beforeSend: function() {
                                    loaderDiv.style.display = 'block';
                                    scrollToPosition();
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
                                    document.getElementById("top_page").scrollIntoView({
                                        behavior: "smooth"
                                    });
                                    if (response.status == 'success') {
                                        outputMessage(message, response.message, 5000, response.status);
                                        audioForm.reset();
                                        previewImg.src = "";
                                    } else {
                                        outputMessage(message, response.message, 5000, response.status);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    xhr.abort();
                                    scrollToPosition();
                                    loaderDiv.style.display = 'none';
                                    $('#uploadProgress').val(0);
                                    $('.progress_text').text("");
                                    if (status === 'timeout') {
                                        outputMessage(message, 'Request timed out!', 5000, 'error');
                                    } else {
                                        outputMessage(message, 'An Error Occured ' + error, 5000, 'error');
                                    }
                                },

                            });

                            $('#cancel-button').click(function() {
                                if (xhr) {
                                    xhr.abort(); // Abort the ongoing upload
                                    loaderDiv.style.display = 'none';
                                    $('#uploadProgress').val(0);
                                    $('.progress_text').text("");
                                    outputMessage(message, 'Upload aborted', 5000, 'error');
                                    scrollToPosition();
                                }
                            });

                        } else {
                            outputMessage(message, "Audio file is larger than 8mb", 5000, 'error');
                            scrollToPosition();
                        }
                    } else {
                        outputMessage(message, "Audio file is not a valid audio file", 5000, 'error');
                        scrollToPosition();
                    }

                } else {
                    outputMessage(message, "Image field cannot be empty", 5000, 'error');
                    scrollToPosition();
                }
            }

            $("#audioForm").submit(function(event) {
                event.preventDefault();
                ajaxRequest();
            });

        });
    </script>
</body>

</html>