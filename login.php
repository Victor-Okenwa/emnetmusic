<?php require_once "./backend/connection.php"  ?>
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

    <title>Emnet ~ Login page</title>
</head>

<body id="loginpage">

    <div id="loader-container">
        <div id="loader"></div>
    </div>

    <div class="wrapper position-relative">
        <?php include_once "connectivity.php" ?>

        <div class="header">
            <a href="./login.php" class="active">Login</a>
            <a href="./signup.php">Register</a>
        </div>

        <div id="result" class="" style="display: none;"></div>

        <form id="loginForm" class="row g-3 was-validated">
            <div class="form-group col-12">
                <label class="form-label" for="email">Email </label>
                <input required type="email" id="email" name="email" class="form-control" placeholder="Your email" value="<?= isset($_COOKIE["Email"]) ? $_COOKIE['Email'] : ""; ?>">
            </div>

            <div class="form-group pword-section col-12">
                <label class="form-label" for="password">Password</label>
                <div class="mk-flex btn-icon-split">
                    <input required type="password" id="password" name="password" class="form-control p-input" autocomplete="off" placeholder="Insert password" value="<?php
                                                                                                                                                                        if (isset($_COOKIE["Password"]) && isset($_COOKIE["iv"]))
                                                                                                                                                                            echo decryptData($_COOKIE["Password"], "my_secret_key", $_COOKIE["iv"]);
                                                                                                                                                                        elseif (isset($_COOKIE["Password"]))
                                                                                                                                                                            echo $_COOKIE["Password"];
                                                                                                                                                                        else "";
                                                                                                                                                                        ?>">
                    <i class="fa fa-eye btn" onclick="viewPassword(this) "></i>
                </div>

                <div align="right" class="mt-3">
                    <a href="forgotten.php" class="text-primary">Forgotten password</a>
                </div>
            </div>

            <div class="form-group form-check col-12">
                <input type="checkbox" name="remember" id="rememberme" style="cursor: pointer; transform: scale(1.2); accent-color: rgb(3, 124, 3);" value="" <?= isset($_COOKIE['Email']) && isset($_COOKIE['Password']) ? "checked" : ""  ?>>
                <label class="form-label" for="rememberme">Remember me</label>
            </div>

            <div class="form-group send-button col-12">
                <button type="button" class="btn mk-flex w-100 justify-content-center" id="submit">
                    <div>Login</div>
                    <span class="spinner spinner-border spinner-border-sm d-none"></span>
                </button>
            </div>

        </form>


    </div>


    <script src="./js/jquery3.6.0.js"></script>
    <script src="./js/login-signin.js"></script>

    <script>
        $(document).ready(function() {
            const output = document.getElementById("result"),
                submitBtn = document.getElementById("submit"),
                loginForm = document.getElementById('loginForm');
            var xhr = new XMLHttpRequest();

            loginForm.onsubmit = (e) => {
                e.preventDefault();
            }

            submitBtn.onclick = () => {
                sendToBackend(this, $('#loginForm'), 'backend/login.php', 15000, output, 'index.php');
            }
        });
    </script>
</body>

</html>