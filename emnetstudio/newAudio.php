<?php

/*________________-_____________________|
# Here a creator can upload new music file
* This is the main file
|_________________-_____________________*/

include "access.php";
if (!isset($_SESSION["uniqueID"])) {
    die("You are not logged in");
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

        #tags span {
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
                        <h5 id="upload_info">Uploading...</h5>
                        <progress id="uploadProgress" class="progress progress-bar animated progress-bar-animated" value="0" max="100"></progress>
                        <h5 class="progress_text text-light text-center"></h5>
                        <button id="cancel-button" class="btn btn-danger mt-3"><i class="fas fa-times-circle"></i> Cancel</button>
                    </div>
                </div>

                <div class="container-fluid mt-5 pt-5 px-0">
                    <div class="" id="result" style="display: none; transition: 1s ease;"></div>

                    <div class="tab-content rounded-bottom">
                        <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-789">

                            <form class="row g-3 was-validated" id="audioForm" method="post" enctype="multipart/form-data">
                                <div class="col-md-4">
                                    <label class="form-label" for="songName">Song name</label>
                                    <input class="form-control" id="songName" type="text" name="songname" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="artist">Artist(s) seperate with ( x )</label>
                                    <input class="form-control" type="text" value="" id="artist" name="artist" required>
                                    <div id="tags" class="d-flex flex-row">
                                        <span class="small ms-1">Name shows here please seperate with a space then followed with an x then a space if ther are multiple artists</span>
                                    </div>
                                </div>

                                <div class="col-12 form-group mb-5">
                                    <label class="form-label" for="genre">Genre</label>

                                    <select class="form-select form-select-lg form-control" style="object-fit: cover; isolation: isolate;" id="genre" name="genre" required="">
                                        <option selected="" disabled value="">Choose a genre...</option>
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
                                </div>

                                <div class="form-group col-md-6 col-sm-12">
                                    <div class="form-check ">
                                        <label for="agelimit1">For all ages</label>
                                        <input class="form-check-input ml-2" style="cursor: pointer; scale: 1.2" type="radio" id="agelimit1" name="agelimit" required="" value="0" checked required>
                                    </div>

                                    <div class="form-ch*eck mb-3">
                                        <label for="agelimit2">18+</label>
                                        <input class="form-check-input ml-2" style="cursor: pointer; scale: 1.2" type="radio" id="agelimit2" name="agelimit" required="" value="1" required>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <div class="form-check">
                                        <label class="form-check-label" for="remix1">Not a remix</label>
                                        <input class="form-check-input ml-2" style="cursor: pointer; scale: 1.2" id="remix1" type="radio" name="remix" required="" value="0" checked required>
                                    </div>

                                    <div class="form-check mb-3">
                                        <label class="form-check-label" for=" remix2">Remix of a song</label>
                                        <input class="form-check-input ml-2" style="cursor: pointer; scale: 1.2" id="remix2" type="radio" name="remix" required="" value="1" required>
                                    </div>
                                </div>

                                <div class="col-12 mb-5">
                                    <img class="d-block m-auto" id="previewImage" height="100" width="100" alt="Preview here">
                                    <label class="form-label" for="thumbnailInput">Upload thumbnail</label>
                                    <input type="file" class="form-control" id="thumbnailInput" name="thumbnail" accept="image/*" style="border: 2px dotted #fff; cursor:pointer; height: max-content;">
                                    <div class="d-block mt-2">
                                        <input type="checkbox" id="imgquality" style="accent-color:green; transform: scale(1.1);">
                                        <label for="imgquality">Reduce quality</label>
                                    </div>
                                    <div class="invalid-feedback">Only image files allowed, Maximum: 2mb</div>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label" for="audioInput">Upload Audio </label>
                                    <input type="file" class="form-control" id="audioInput" name="audio" accept="audio/*" required style="border: 2px dotted #fff; cursor:pointer; height: max-content;">
                                    <div class="invalid-feedback">only audio files allowed, Maximum: 8mb</div>
                                </div>

                                <div class="col-12">
                                    <button class="d-block btn col-md-6 col-lg-4 col-sm-12 m-auto" type="submit" id="submitAudio" name="submitAudio" style="background: #333; color: white; border: 1px solid #f5f5f5">
                                        <i class="fa fa-upload"></i> Add Post
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
            const result = document.getElementById("result"),
                loaderDiv = document.querySelector(".loader-div"),
                audioForm = document.getElementById("audioForm"),
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
                }, 25000)
            }

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
            artist.addEventListener("keyup", (e) => {
                createTags(e.target.value);
            })

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

            function ajaxRequest() {
                const audio = document.getElementById("audioInput").files[0];
                if ($("#songName").val() == "" || $("#artist").val() == "" || $("#genre").val() == "" || $("#audio").val() == "") {
                    outPutMessage("No field is allowed to be empty", 'error');
                    $(".scroll-to-top").click();
                    return;
                }
                if (previewImg.src != "") {
                    if (audio.type.startsWith("audio/")) {
                        if (audio.size <= 10000000) {

                            const formData = new FormData(audioForm);
                            formData.set('songname', $('input[name="songname"]').val());
                            formData.set('artist', $('input[name="artist"]').val());
                            formData.set('genre', $('select[name="genre"]').val());
                            formData.set('agelimit', $('input[name="agelimit"]:checked').val());
                            formData.set('remix', $('input[name="remix"]:checked').val());
                            formData.set('thumbnail', resizeImage());
                            formData.set('audio', audio);

                            $.ajax({
                                url: 'newAudioApi.php',
                                type: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                timeout: 1000000,
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
                                    xhr.abort();
                                    $(".scroll-to-top").click();
                                    loaderDiv.style.display = 'none';
                                    $('#uploadProgress').val(0);
                                    $('.progress_text').text("");
                                    if (status === 'timeout') {
                                        outPutMessage('Request timed out!', 'error');
                                    } else {
                                        outPutMessage('An Error Occured', 'error');
                                    }
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

                        } else {
                            outPutMessage("Audio file is larger than 9mb", 'error');
                            $(".scroll-to-top").click();
                        }
                    } else {
                        outPutMessage("Audio file is not a valid audio file", 'error');
                        $(".scroll-to-top").click();
                    }

                } else {
                    outPutMessage("Image field cannot be empty", 'error');
                    $(".scroll-to-top").click();
                }
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