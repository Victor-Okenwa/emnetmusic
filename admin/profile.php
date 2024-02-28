<?php require_once "./assets/access.php";
$firstname = $data_admin['firstname'];
$lastname = $data_admin['lastname'];
$nickname = $data_admin['nickname'];
$phone_number = $data_admin['phone_number'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require "./assets/vendors.html" ?>
</head>

<body>
    <?php require "./assets/navbar.php" ?>
    <div id="top_page"></div>

    <main class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>Your profile</h4>
            </div>
            <div class="card-body">
                <div id="output" class="d-none"></div>
                <form method="POST" class="row g-3 was-validated bg-gray-300" id="profileForm">
                    <div class="col-md-6 col-sm-12 mb-3">
                        <label class="form-label" for="firstname">Firstname</label>
                        <input class="form-control" id="firstname" type="text" name="firstname" value="<?= $firstname ?>" required>
                    </div>

                    <div class="col-md-6 col-sm-12 mb-3">
                        <label class="form-label" for="lastname">Lastname</label>
                        <input class="form-control" id="lastname" type="text" name="lastname" value="<?= $lastname ?>" required>
                    </div>

                    <div class="col-md-6 col-sm-12 mb-3">
                        <label class="form-label" for="nickname">Nickname</label>
                        <input class="form-control" id="nickname" type="text" name="nickname" value="<?= $nickname ?>" required>
                    </div>

                    <div class="col-md-6 col-sm-12 mb-3">
                        <label class="form-label" for="contact">Contact</label>
                        <input class="form-control" id="contact" type="text" name="contact" value="<?= $phone_number ?>" required>
                    </div>

                    <div class="col-12 mb-3">
                        <img id="previewImg" src="admin_profile/<?= $admin_image ?>" width="70" height="70">
                        <label class="form-label" for="profileImage">Upload profile image</label>
                        <input type="file" class="form-control" id="profileImage" accept="image/*" name="profile">
                        <div class="d-block mt-2">
                            <input type="checkbox" id="imgquality" style="accent-color:green; transform: scale(1.1);">
                            <label for="imgquality">Reduce quality</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary" id="submitProfile" name="submit" title="">
                            Submit form <span id="profileLoader" class="spinner spinner-grow spinner-grow-sm d-none"></span></button>
                        <b id="uploadProgress"></b>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php require_once "./assets/footer.html" ?>
    <?php require_once "./assets/scripts.html" ?>

    <script>
        $(document).ready(function() {
            const message = document.getElementById('output'),
                profileForm = document.getElementById('profileForm'),
                uploadProgress = document.getElementById('uploadProgress'),
                previewImg = document.getElementById("previewImg"),
                profileImage = document.getElementById("profileImage"),
                imgQualityInput = document.getElementById("imgquality"),
                profileLoader = document.getElementById("profileLoader"),
                submitProfile = document.getElementById("submitProfile");

            let xhr = new XMLHttpRequest();

            let imageWidth,
                imageHeight;
            profileImage.onchange = function(e) {
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
                            outputMessage(message, "Thumbnail is larger than 2mb", 4000, 'error');
                        }
                    } else {
                        previewImg.src = "";
                        this.files[0] = "";
                        this.value = "";
                        outputMessage(message, "Only JPG, PNG, JFIF, JPEG and GIF files allowed", 4000, 'error');
                    }
                } else {
                    previewImg.src = "";
                    this.files[0] = "";
                    this.value = "";
                    outputMessage(message, "Thumbnail is not a valid image file", 4000, 'error');
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
                if ($("#firstname").val() == "" || $("#lastname").val() == "" || $("#nickname").val() == "" || $("#contact").val() == "") {
                    outputMessage(message, "No field is allowed to be empty", 4000, 'error');
                    return;
                }
                if (previewImg.src != "") {
                    const formData = new FormData(profileForm);
                    formData.set('firstname', $('input[name="firstname"]').val());
                    formData.set('lastname', $('input[name="lastname"]').val());
                    formData.set('nickname', $('input[name="nickname"]').val());
                    formData.set('contact', $('input[name="contact"]').val());
                    formData.set('profile', resizeImage());

                    $.ajax({
                        url: 'api/profile_update.php',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        timeout: 40000,
                        beforeSend: function() {
                            profileLoader.classList.remove('d-none');
                        },
                        xhr: function() {
                            xhr.upload.addEventListener("progress", (e) => {
                                if (e.lengthComputable) {
                                    const percent = Math.round((e.loaded / e.total) * 100);
                                    $('#uploadProgress').text(percent + '% ' + '/ 100% uploaded');
                                }
                            });
                            return xhr;
                        },
                        complete: function() {
                            profileLoader.classList.add('d-none');
                            $('#uploadProgress').text("");
                        },
                        success: function(response) {
                            if (response.status == 'success') {
                                outputMessage(message, response.message, 4000, response.status);
                                previewImg.src = "";
                            } else {
                                outputMessage(message, response.message, 4000, response.status);
                            }
                        },
                        error: function(xhr, status, error) {
                            xhr.abort();
                            profileLoader.classList.add('d-none');
                            $('#uploadProgress').text("");
                            if (status === 'timeout') {
                                outputMessage(message, 'Request timed out!', 4000, 'error');
                            } else {
                                outputMessage(message, 'An Error Occured ' + error, 4000, 'error');
                            }
                        },
                    })

                } else {
                    outputMessage(message, "Image field cannot be empty", 4000, 'error');
                }
            }

            $("#profileForm").submit(function(event) {
                event.preventDefault();
                ajaxRequest();
            });

        });
    </script>
</body>

</html>

</html>