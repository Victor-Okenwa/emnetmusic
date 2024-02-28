<?php require_once "./backend/connection.php" ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./logos/Emnet_Logo2.png" type="image/x-icon">

    <!-- -------------- Font awesome REM(insert for web letter) --------- -->
    <link rel="stylesheet" href="./css/all.css">

    <!-- -------------- Google material icons REM(insert for web letter) --------- -->
    <link rel="stylesheet" href="./iconfont/material-icons.css">


    <!-- -------------- boostrap REM(insert for web letter) --------- -->
    <link rel="stylesheet" href="./css/boot4.css">

    <!-- -------------- custom --------- -->
    <link rel="stylesheet" href="./css/style.css">

    <style>
        .wrapper .offlineStatus,
        .wrapper .onlineStatus {
            position: absolute !important;
            opacity: 1;
            width: 100%;
            height: 80px;
            display: flex;
            visibility: hidden;
            pointer-events: visible;
            justify-content: space-between;
            z-index: 6000;
            transition: opacity 1s ease;
            align-items: center;
            top: 0;
        }
    </style>

    <title>Emnet ~ Password reset</title>
</head>

<body>
    <div class="wrapper d-flex flex-column justify-content-center align-items-center min-vh-100 position-relative">

        <?php
        $query_user_exists = $conn->query("SELECT * FROM users WHERE email = '{$_GET['email']}' ");
        $query_token_exists = $conn->query("SELECT * FROM tokens WHERE token = '{$_GET['token']}' ");

        if (!isset($_GET['email']) || $_GET['email'] == "") {
        ?>
            <div class="container text-light bg-danger py-3 rounded-sm text-center" width="100%">Email Not found</div>
        <?php
        } elseif (!isset($_GET['token']) || $_GET['token'] == "") {
        ?>
            <div class="container text-light bg-danger py-3 rounded-sm text-center" width="100%">Token not found</div>
        <?php
        } elseif ($query_user_exists->num_rows < 1) {
        ?>
            <div class="container text-light bg-danger py-3 rounded-sm text-center" width="100%">User Not Found</div>

        <?php
        } elseif ($query_token_exists->num_rows < 1) {
        ?>
            <div class="container text-light bg-danger py-3 rounded-sm text-center" width="100%">Token Not Found</div>
        <?php
        } else {
            include_once "connectivity.php";
        ?>
            <div class="card" style="max-width: 100%;">

                <div class="card-header">
                    <h5>Change password</h5>
                </div>
                <div id="result" class="alert alert-danger text-center" style="display: none; transition: all .7s ease;"></div>

                <form class="row g-3 was-validated" method="post">
                    <input type="hidden" class="form-control" name="email" id="email" value="<?= $_GET['email'] ?>">
                    <input type="hidden" class="form-control" name="token" id="token" value="<?= $_GET['token'] ?>">

                    <div class="card-body col-12">
                        <div class="form-group col-12">
                            <label class="form-label" for="newPassword">New password</label>

                            <input type="password" name="newPassword" class="form-control" id="newPassword" minlength="6" placeholder="Enter a new password" required>
                        </div>
                    </div>
                    <div class="card-body col-12">
                        <div class="form-group col-12">
                            <label class="form-label" for="confPassword">Confirm password</label>
                            <input type="password" name="confPassword" class="form-control" id="confPassword" minlength="6" placeholder="Confirm password" required>
                        </div>
                    </div>

                    <div class="card-footer col-12">
                        <div class="form-group send-button col-12">
                            <button type="button" class="btn mk-flex bg-dark text-light w-100 justify-content-center" id="submit">
                                <div id="resettext">Reset password</div>
                                <span style="display: none;" class="text-warning" id="loading">Loading ...</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        <?php  } ?>
    </div>

    <script src="./js/jquery3.6.0.js"></script>
    <script src="./js/bootstrap4.js"></script>
    <script>
        $(document).ready(() => {
            let result = document.getElementById('result');

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
                }, 5000)
            }

            $('#submit').click(function() {
                var email = $('#email').val();
                var token = $('#token').val();
                var password = $('#newPassword').val().trim();
                var confPassword = $('#confPassword').val().trim();

                if (password != "" && confPassword != "") {

                    if (password == confPassword) {

                        $.ajax({
                            type: "POST",
                            url: "reset_passwordApi.php",
                            timeout: 30000,
                            data: {
                                email: email,
                                token: token,
                                newPassword: password,
                                confPassword: confPassword
                            },
                            dataType: "json",
                            beforeSend: function() {
                                $('#loading').show();
                                $('#resettext').hide();
                            },
                            error: function(xhr, status, error) {
                                if (status === 'timeout') {
                                    // Handle timeout error
                                    outPutMessage('Your request timed out', 'error');
                                    $('#loading').hide();
                                    $('#resettext').show();
                                } else {
                                    outPutMessage('Password request send error:' + error, 'error');
                                    $('#loading').hide();
                                    $('#resettext').show();
                                }
                            },
                            success: function(data) {
                                if (data.status == 'error') {
                                    outPutMessage(data.message, data.status);
                                } else {
                                    outPutMessage(data.message, data.status);
                                    location.href = "index.php";
                                }

                            },
                            complete: function() {
                                // Hide loading indicator if shown
                                $('#loading').hide();
                                $('#resettext').show();
                            }
                        })

                    } else {
                        outPutMessage("Passwords do not match", 'error');
                    }

                } else {
                    outPutMessage("All fields are required", 'error');
                }
                console.log(email, token, password, confPassword);


            })

        });
    </script>
</body>

</html>