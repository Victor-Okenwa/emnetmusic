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

    <script>
        function scrollToPosition(element) {
            element.scrollIntoView({
                behavior: "auto",
                wait: 2000
            });

        }

        function hideBtn() {
            const toppageBtn = document.querySelector('.toppage');
            if (toppageBtn.classList.contains('d-none')) {
                toppageBtn.classList.remove('d-none')
            }
        }

        function displayBtn() {
            const toppageBtn = document.querySelector('.toppage');
            if (!toppageBtn.classList.contains('d-none')) {
                toppageBtn.classList.add('d-none')
            }
        }
    </script>

    <title>Emnet ~ signup page</title>
</head>

<body id="signuppage">


    <div id="loader-container">
        <div id="loader"></div>
    </div>


    <div class="d-flex flex-column">

        <section class="signup-option container-fluid">
            <div class="head-buttons">
                <a href="login.php" class="btn btn-light mt-3 shadow-lg"><i class="fa fa-long-arrow-alt-left"></i> Login</a>
                <a href="/" class="btn btn-light mt-3 ml-2"><i class="fa fa-home"></i> </a>
            </div>

            <div class="options row">
                <div class="option col-md-5" onclick="scrollToPosition(document.getElementById('user')), hideBtn()">
                    <i class="fa fa-user"></i>
                    <p>Signup as a user, a user can also signup as an artist</p>
                </div>
                <div class="option col-md-5" onclick="scrollToPosition(document.getElementById('team')), hideBtn()">
                    <i class="fa fa-users"></i>
                    <p>Signup as a record label or team, you can chat with artists</p>
                </div>
            </div>
        </section>

        <section id="user" class="form">
            <div class="top_target"></div>
            <div class="wrapper ">
                <div id="outputuser" style="display: none;"></div>

                <form id="userform" class="row g-3 was-validated">
                    <input type="text" name="type" class="field_1" value="user" hidden>
                    <div class="form-group col-md-12">
                        <div class="mr-lg-2">
                            <label class="form-label" for="firstname">Firstname</label>
                            <input type="text" name="firstname" class="form-control" placeholder="Use your real name" id="firstname" required>

                            <label class="form-label" for="surname">Lastname</label>
                            <input type="text" name="surname" class="form-control" placeholder="Use your real name" id="surname" required>

                            <label class="form-label" for="nickname">Username</label>
                            <input type="text" name="nickname" class="form-control" placeholder="Your desired name" id="nickname" required>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <label class="form-label" for="phonenumber">Phone number</label>
                        <input type="number" name="phonenumber" class="form-control" placeholder="Your phone number" id="phonenumber" ---------required>
                    </div>

                    <div class="form-group col-md-12">
                        <label class="form-label" for="password">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Your email" id="email" required>
                    </div>


                    <div class="form-group col-md-12 pword-section">
                        <label class="form-label" for="password">Password</label>
                        <div class="mk-flex btn-icon-split">
                            <input type="password" name="password" oninput="passwordStrength(this)" class="form-control p-input" minlength="6" autocomplete="off" placeholder="Insert password*not less than 6" id="password" required>
                            <i class="fa fa-eye btn" onclick="viewPassword(this)"></i>
                        </div>
                        <span class="strength-text mk-flex">
                            <label class="form-label" for="strength">strength:</label>
                            <span class="strength1" title="weak"></span>
                            <span class="strength2" title="average"></span>
                            <span class="strength3" title="strong"></span>
                        </span>
                    </div>


                    <div class="form-group col-md-12">
                        <label class="form-label" for="Country">Country <i class="fa fa-globe"></i> </label>

                        <select name="country" id="country" class="form-control rounded-sm" required>
                            <option value="Nigeria" selected>Nigeria</option>
                        </select>
                    </div>

                    <div class="form-group col-md-12">
                        <label class="form-label" for="Gender">Gender</label>
                        <div>
                            <input type="radio" name="gender" id="male" value="male" style="cursor: pointer; transform: scale(1.2);" checked required>
                            <label class="form-label" for="male">Male</label>
                        </div>
                        <div>
                            <input type="radio" name="gender" id="female" value="female" style="cursor: pointer; transform: scale(1.2);" required>
                            <label class="form-label" for="female">Female</label>
                        </div>
                    </div>

                    <div class="form-group send-button col-md-12">
                        <button type="button" class="btn mk-flex bg-primary text-light w-100 justify-content-center" id="userbtn">
                            <div>Signup</div>
                            <span class="spinner spinner-border spinner-border-sm d-none"></span>
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <section id="team" class="form">
            <div class="top_target"></div>
            <div class="wrapper ">
                <div id="outputteam" style="display: none;"></div>
                <form id="teamform" class="row g-3 was-validated">
                    <input type="text" name="type" class="field_1" value="team" hidden>
                    <div class="form-group col-md-12">
                        <div class="mr-lg-2">
                            <label class="form-label" for="teamname">Team name</label>
                            <input type="text" name="teamname" class="form-control" placeholder="Your RL/team name" id="teamname" required>
                        </div>
                        <div class="mr-lg-2">
                            <label class="form-label" for="founder">Founder</label>
                            <input type="text" name="founder" class="form-control" placeholder="RL/Team owner" id="founder" required>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <label class="form-label" for="number">Phone number</label>
                        <input type="number" name="phonenumber" class="form-control" placeholder="Your phone number" id="phonenumber" minlength="6" required>
                    </div>

                    <div class="form-group col-md-12">
                        <label class="form-label" for="password">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Your email" id="email" required>
                    </div>


                    <div class="form-group col-md-12 pword-section">
                        <label class="form-label" for="password">Password</label>
                        <div class="mk-flex btn-icon-split">
                            <input type="password" name="password" oninput="passwordStrength(this)" class="form-control p-input" minlength="6" autocomplete="off" placeholder="Insert password*not less than 6" id="password" required>
                            <i class="fa fa-eye btn" onclick="viewPassword(this)"></i>
                        </div>
                        <span class="strength-text mk-flex">
                            <label class="form-label" for="strength">strength:</label>
                            <span class="strength1" title="weak"></span>
                            <span class="strength2" title="average"></span>
                            <span class="strength3" title="strong"></span>
                        </span>
                    </div>


                    <div class="form-group col-md-12">
                        <label class="form-label" for="Country">Country <i class="fa fa-globe"></i> </label>

                        <select name="country" id="country" class="form-control rounded-sm" required>
                            <option value="Nigeria" selected>Nigeria</option>
                        </select>
                    </div>

                    <div class="form-group send-button col-md-12">
                        <button type="button" class="btn mk-flex bg-primary text-light w-100 justify-content-center" id="teambtn">
                            <div>Signup</div>
                            <span class="spinner spinner-border spinner-border-sm d-none"></span>
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <button type="button" class="toppage btn btn-dark text-light d-none" onclick="scrollToPosition(document.querySelector('.signup-option')), displayBtn()"><i class="fa fa-long-arrow-alt-up"></i></button>

    <script src="./js/jquery3.6.0.js"></script>
    <script src="./js/login-signin.js"></script>

    <script>
        $(document).ready(() => {
            document.getElementById('userbtn').onclick = function() {
                sendToBackend(this, $('#userform'), 'backend/register.php', 15000, document.getElementById('outputuser'), 'verify.php');
                this.parentElement.parentElement.parentElement.parentElement.querySelector('.top_target').scrollIntoView({
                    behavior: 'smooth'
                });
            }

            document.getElementById('teambtn').onclick = function() {
                sendToBackend(this, $('#teamform'), 'backend/register.php', 15000, document.getElementById('outputteam'), 'verify.php');
                this.parentElement.parentElement.parentElement.parentElement.querySelector('.top_target').scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    </script>

</body>

</html>