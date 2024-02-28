<?php
session_start();
require_once './backend/connection.php';
if (isset($_SESSION["uniqueID"])) {

    $query_user = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '{$user_id}' ");

    if (mysqli_num_rows($query_user) > 0) {
        $fetch_user = mysqli_fetch_assoc($query_user);
        $userid = trim($fetch_user['user_id']);
        $fname = trim($fetch_user['firstname']);
        $sname = trim($fetch_user['surname']);
        $oname = trim($fetch_user['othername']);
        $nickname = trim($fetch_user['nickname']);
        $email = trim($fetch_user['email']);
        $contact = trim($fetch_user['phone_number']);
        $country = trim($fetch_user['country']);
        $gender = strtolower(trim($fetch_user['gender']));
        $_SESSION['nickname'] = trim($fetch_user['nickname']);
        $img = trim($fetch_user['user_profile']);
    }
} else {
    header('location: /');
}
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

<body class="paused" id="profile_page">
    <!-- ----------------------- HEADER ------------------------ -->
    <?php require "./navbars.php" ?>
    <!--x----------------------- HEADER ------------------------x-->

    <main id="main" class="">

        <div class="user_image <?= (!empty($img)) ? 'active' : '' ?>">

            <div class="alert alert-danger" id="infoProfile" style="text-align:center; display: none"></div>
            <form id="profileUpload">
                <div class="modal-body">
                    <div class="mk-flex justify-content-between flex-column">
                        <div class="upload-box">
                            <img src="./userprofiles/<?= $img ?>" alt="">
                            <input type="file" id="newprofile" name="imgfile" value="Choose a new profile" accept="image/*" hidden>
                            <p class="text-light browse-text">Browse file upload <small>Maximum: 2mb</small></p>
                        </div>

                        <div class="checkbox">
                            <input type="checkbox" id="quality">
                            <label for="quality">Reduce quality</label>
                        </div>

                    </div>
                </div>
                <div class="profile-buttons">
                    <?= (!empty($img)) ? "<button type='button'  class='delete-profile material-icons btn btn-danger' data-toggle='modal' data-target='#deleteProfile'>delete</button>" : "" ?>

                    <button disabled type="button" name="delete" class="btn btn-success" id="userProfileBtn"><i class="fa fa-upload"></i>
                        <span class="spinner-grow spinner-grow-md mr-3" style="display: none;" id="loaderUpload"></span>
                    </button>
                </div>
            </form>


        </div>

        <hr class="my-5" style="width:98%">
        <div class="user_details ">
            <h4 class="text-center text-light">Details</h4>

            <form id="detailUpload" class="d-flex flex-column align-items-center">
                <div class="col-md-6 col-sm-12 mb-4">
                    <label for="firstname" class="text-light">Firstname</label>
                    <input type="text" id="firstname" class="form-control" value="<?= $fname ?>" required>
                </div>

                <div class="col-md-6 col-sm-12 mb-4">
                    <label for="surname" class="text-light">Surname</label>
                    <input type="text" id="surname" class="form-control" value="<?= $sname ?>" required>
                </div>

                <div class="col-md-6 col-sm-12 mb-4">
                    <label for="othername" class="text-light">Othername</label>
                    <input type="text" id="othername" class="form-control" value="<?= $oname ?>">
                </div>

                <div class="col-md-6 col-sm-12 mb-4">
                    <label for="contact" class="text-light">Contact</label>
                    <input type="text" id="contact" class="form-control" value="<?= $contact ?>">
                </div>

                <div class="col-md-6 col-sm-12 mb-4">
                    <label for="country" class="text-light">Country</label>
                    <input type="text" id="country" class="form-control" value="<?= $country ?>">
                </div>

                <div class="col-md-6 col-sm-12 mb-4">
                    <label for="gender" class="text-light">GENDER</label>
                    <div>
                        <input type="radio" name="gender" id="male" value="male" style="cursor: pointer; transform: scale(1.2); accent-color: #1fbf" required <?= $gender == 'male' ? 'checked' : '' ?>>
                        <label class="form-label text-light" for="male">Male</label>
                    </div>
                    <div>
                        <input type="radio" name="gender" id="female" value="female" style="cursor: pointer; transform: scale(1.2); accent-color: #1fbf" required <?= $gender == 'female' ? 'checked' : '' ?>>
                        <label class="form-label text-light" for="female">Female</label>
                    </div>
                    <div>
                        <input type="radio" name="gender" id="other" value="other" style="cursor: pointer; transform: scale(1.2); accent-color: #1fbf" required <?= $gender == 'other' ? 'checked' : '' ?>>
                        <label class="form-label text-light" for="other">Other</label>
                    </div>
                </div>

                <div class="alert alert-danger" id="infoDetails" style="text-align:center; display: none;"></div>
                <button type="button" id="detailsUpdate" class="btn btn-light col-lg-4 col-md-6 col-sm-12 m-auto d-block" title="update details">Update Details
                    <span class="spinner-grow spinner-grow-md mr-3" style="display: none;" id="loaderDetails"></span>
                </button>
            </form>
            <?php
            $query_creator = $conn->query("SELECT record_label FROM creators where user_id = '{$unique_id}'");

            if ($query_creator->num_rows > 0) {
                $recordLabel = trim($query_creator->fetch_assoc()['record_label'])
            ?>
                <hr class="my-5" style="width:98%">
                <h4 class="text-center text-light">For creators</h4>
                <div class="alert alert-danger" id="infoCreators" style="text-align:center; display: none;"></div>

                <form id="creatorForm">
                    <div class="col-md-6 col-sm-12 mb-3">
                        <label for="record-label" class="text-light">Record label</label>
                        <input type="text" id="record-label" class="form-control  mb-2" placeholder="If empty then you are searching" value="<?= $recordLabel ?>" required>

                        <button type="button" class="btn btn-info" id="creatorBtn" title="update details">Update Details
                            <span class="spinner-grow spinner-grow-md mr-3" style="display: none;" id="loaderCreator"></span>
                        </button>

                    </div>
                </form>
            <?php } ?>
        </div>
    </main>



    <!------------- Modals ---------->
    <!-- delete profile -->

    <div class="modal fade" id="deleteProfile" tabindex="" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index:10000">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn btn-close" data-toggle="modal" data-target="#deleteProfile" data-bs-dismiss="modal" aria-label="Close"> <i class="fa fa-times"></i> </button>
                    <div id="profileUploadInfo"></div>
                </div>
                <form>
                    <div class="modal-body">

                        <h5> Do you want to delete profile</h5>
                        <input type="text" name="userID" id="profileDeleteUserID" value="<?= $userid ?>" hidden>
                        <input type="text" name="profileImg" id="profileDeletePrevURL" value="<?= $img ?>" hidden>
                    </div>
                    <div class="modal-footer">
                        <button type="button" name="delete" id="profileDelete" class="btn btn-primary material-icons" id="deleteprofileBtn">delete
                            <span class="spinner-grow spinner-grow-sm" id="loaderDelete" style="display: none;"></span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- delete profile -->

    <?php include "modals.php" ?>

    <!------------- Modals ---------->



    <!-- ----- jQuery(insert for web letter) ----- -->
    <script src="./js/jquery3.6.0.js"></script>

    <!-- ----- popper(insert for web letter) ----- -->
    <script src="./js/popper1.160.min.js"></script>

    <!-- ----- boostrap(insert for web letter) ----- -->
    <script src="./js/bootstrap4.js"></script>

    <!-- ----- custom ----- -->
    <script src="./js/main.js"></script>


    <script>
        function outputInfo(html, data, status) {
            html.classList = "";
            html.style.display = '';
            html.textContent = data;
            if (status == 'error') {
                html.classList = 'alert alert-danger';
            } else {
                html.classList = 'alert alert-success';
            }

            setTimeout(() => {
                html.classList = '';
                html.style.display = 'none';
                html.textContent = '';
            }, 3500);
        }

        const userProfile = document.querySelector(".user_image"),
            uploadBox = document.querySelector(".upload-box"),
            previewImg = uploadBox.querySelector("img"),
            fileInput = uploadBox.querySelector("input"),
            qualityInput = document.getElementById("quality"),
            profileBtn = document.getElementById('userProfileBtn');

        // \\ PROFILE UPDATE // \\
        $(document).ready(() => {

            let imageRatio, imageWidth = 800,
                imageHeight = 800;

            const sendFile = (data) => {
                var infoProfile = document.getElementById('infoProfile');
                if (data !== "") {
                    $.ajax({
                        type: 'POST',
                        url: "backend/upload_profile.php",
                        dataType: 'text',
                        timeout: 20000,
                        data: {
                            imgfile: data,
                        },
                        beforeSend: () => {
                            $("#loaderUpload").show();
                        },
                        error: (xhr, status, error) => {
                            $("#loaderUpload").hide();
                            if (status == 'timeout') {
                                outputInfo(infoProfile, "Your request timed out", 'error');
                            } else {
                                outputInfo(infoProfile, "Request Error: " + status, 'error');
                            }
                        },
                        complete: () => {
                            $("#loaderUpload").hide();
                        },
                        success: (response) => {
                            $("#loaderUpload").hide();
                            if (response == 'success') {
                                outputInfo(infoProfile, "Profile updated", 'success');
                            } else {
                                outputInfo(infoProfile, response, 'error');
                            }
                        }
                    })
                }
            }

            const loadFile = function(e) {
                const file = e.target.files[0];
                if (!file) return;
                if (file.type.startsWith('image/')) {

                    if (file.size <= 2000000) {
                        profileBtn.removeAttribute('disabled');
                        previewImg.src = URL.createObjectURL(file);

                        previewImg.addEventListener("load", () => {
                            userProfile.classList.add("active");
                            imageWidth = previewImg.naturalWidth;
                            imageHeight = previewImg.naturalHeight;
                        })
                    } else {
                        profileBtn.setAttribute('disabled', '');
                        outputInfo(infoProfile, "File size is too large", 'error');
                        previewImg.src = "";
                        userProfile.classList.remove("active");
                    }
                } else {
                    profileBtn.setAttribute('disabled', '');
                    outputInfo(infoProfile, "File is not a valid image", 'error');
                    previewImg.src = "";
                    userProfile.classList.remove("active");
                }
            }

            const resizeAndUpload = function() {
                const canvas = document.createElement("canvas");
                const ctx = canvas.getContext("2d");
                const imgQuality = qualityInput.checked ? 0.6 : 0.8;
                canvas.width = imageWidth;
                canvas.height = imageHeight;
                ctx.drawImage(previewImg, 0, 0, canvas.width, canvas.height);
                const compressedImage = canvas.toDataURL("image/jpeg", imgQuality);
                sendFile(compressedImage);
            }

            fileInput.addEventListener('change', loadFile);
            uploadBox.addEventListener("click", () => newprofile.click());

            $("#userProfileBtn").click(() => {
                resizeAndUpload();
            })

            // \\ PROFILE DELETE // \\
            $('#profileDelete').click(function() {
                var profileDeleteUserID = $('#profileDeleteUserID').val().trim();
                var profileDeletePrevURL = $('#profileDeletePrevURL').val().trim()
                if (profileDeleteUserID !== "" && profileDeletePrevURL !== "") {
                    $.ajax({
                        type: "POST",
                        url: "backend/delete_profile.php",
                        timeout: 30000,
                        data: {
                            profileDeleteUserID: profileDeleteUserID,
                            profileDeletePrevURL: profileDeletePrevURL,
                        },

                        beforeSend: function() {
                            $('#loaderDelete').show();
                        },
                        success: function(data) {
                            if (data.status == "error") {
                                alert(data.message);
                            } else {
                                alert('Profile deleted');
                                previewImg.src = "";
                            }
                        },

                        error: function(xhr, status, error) {
                            if (status === 'timeout') {
                                // Handle timeout error
                                alert('Request timed out!', 'error');
                                $('#loaderDelete').hide();
                            } else {
                                alert('Error:' + error, 'error');
                                $('#loaderDelete').hide();
                            }
                        },
                        complete: function() {
                            $('#loaderDelete').hide();
                        }
                    });
                }

                // \\ DETAILS UPDATE // \\
                $('#detailsUpdate').click(() => {
                    var firstname = $('#firstname').val().trim();
                    var surname = $('#surname').val().trim();
                    var othername = $('#othername').val().trim();
                    var contact = $('#contact').val().trim();
                    var country = $('#country').val().trim();
                    var gender = $('input[name=gender]:checked').val().trim();
                    var infoDetails = document.getElementById('infoDetails');

                    if (firstname == "") {
                        return outputInfo(infoDetails, 'Firstname is required', 'error');
                    } else if (surname == "") {
                        return outputInfo(infoDetails, 'Surname is required', 'error');
                    } else if (contact == "") {
                        return outputInfo(infoDetails, 'Contact is required', 'error');
                    } else if (country == "") {
                        return outputInfo(infoDetails, 'Country is required', 'error');
                    }

                    $.ajax({
                        url: "backend/upload_profile.php",
                        method: 'POST',
                        timeout: 20000,
                        data: {
                            details: 1,
                            firstname: firstname,
                            surname: surname,
                            othername: othername,
                            contact: contact,
                            country: country,
                            gender: gender,
                        },
                        beforeSend: () => {
                            $("#loaderDetails").show();
                        },
                        error: (xhr, status, error) => {
                            $("#loaderDetails").hide();
                            if (status == 'timeout') {
                                outputInfo(infoDetails, "Your request timed out", error);
                            } else {
                                outputInfo(infoDetails, "Request Error", 'error');
                            }
                        },
                        complete: () => {
                            $("#loaderDetails").hide();
                        },
                        success: (response) => {
                            $("#loaderDetails").hide();
                            if (response == 'success') {
                                outputInfo(infoDetails, "Details updated", 'success');
                            } else {
                                outputInfo(infoDetails, `${response}`, 'error');
                            }
                        }

                    });

                });
            });

            $('#creatorBtn').click(() => {
                var recordLabel = $('#record-label').val().trim();
                var infoCreators = document.getElementById("infoCreators");

                if (recordLabel == '') {
                    return outputInfo(infoCreators, 'Record label is empty', 'error');
                }

                $.ajax({
                    url: "backend/upload_profile.php",
                    method: 'POST',
                    timeout: 20000,
                    data: {
                        creator: 1,
                        recordLabel: recordLabel,
                    },
                    beforeSend: () => {
                        $("#loaderCreator").show();
                    },
                    error: (xhr, status, error) => {
                        $("#loaderCreator").hide();
                        if (status == 'timeout') {
                            outputInfo(infoCreators, "Your request timed out", error);
                        } else {
                            outputInfo(infoCreators, "Request Error", 'error');
                        }
                    },
                    complete: () => {
                        $("#loaderCreator").hide();
                    },
                    success: (response) => {
                        $("#loaderCreator").hide();
                        if (response == 'success') {
                            outputInfo(infoCreators, "Record label updated", 'success');
                        } else {
                            outputInfo(infoCreators, `${response}`, 'error');
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>