<!DOCTYPE html>
<html lang="en" class="user_getting">

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
        }
    </style>
    <title>Emnet ~ Forgotten password</title>

</head>

<body>

    <div class="wrapper position-relative">
        <?php
        include_once "connectivity.php"
        ?>
        <div class="card">
            <div class="card-header">
                <h5>Reset password</h5>
            </div>


            <div id="result" class="alert alert-danger text-center" style="display: none; transition: all .7s ease;"></div>
            <form class="row g-3 was-validated" action="#">

                <div class="card-body col-12">
                    <div class="form-group col-12">
                        <label class="form-label" for="password">Email</label>
                        <input type="email" id="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                </div>

                <div class="card-footer col-12">
                    <div class="form-group send-button col-12">
                        <button type="button" class="position-relative mk-flex text-light w-100 justify-content-center py-1" id="submit" style="border: none; border-radius: 3px;">
                            <div id="send">Send token</div>
                            <span style="display: none;" id="loading">Loading ...</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="./js/jquery3.6.0.js"></script>
    <script>
        $(document).ready(function() {
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
                }, 7000)
            }

            $('#submit').click(function() {
                var email = $('#email').val();
                if (email !== "") {
                    $.ajax({
                        type: "POST",
                        url: "forgottenApi.php",
                        timeout: 50000,
                        data: {
                            email: email
                        },
                        dataType: "json",
                        beforeSend: function() {
                            $('#loading').show();
                            $('#send').hide();
                        },
                        success: function(data) {
                            if (data.status == "error") {
                                outPutMessage(data.message, data.status);
                            } else if (data.status == "success") {
                                outPutMessage(data.message, data.status);
                            }
                        },

                        error: function(xhr, status, error) {
                            if (status === 'timeout') {
                                // Handle timeout error
                                outPutMessage('Email sending timed out!', 'error');
                                $('#loading').hide();
                                $('#send').show();
                            } else {
                                outPutMessage('E-mail send error:' + error, 'error');
                                $('#loading').hide();
                                $('#send').show();
                            }
                        },

                        complete: function() {
                            // Hide loading indicator if shown
                            $('#loading').hide();
                            $('#send').show();
                        }

                    });
                }

            });
        });
    </script>


</body>

</html>