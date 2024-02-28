<?php require_once "./assets/access.php"; ?>
<!DOCTYPE html>
<html lang="en" id="passwayPage">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once './assets/vendors.html'  ?>

    <title>Admin Passway</title>
</head>

<body class="bg-white">


    <main class="container-fluid">
        <div class="card shadow-lg">
            <div class="card-header">
                <!-- <a href="" style="padding:1.5% 7%;background: #7ee0f8;border-radius:15px; font-size:17px; font-weight:bold">Click</a> -->
                <p>Enter your email</p>
            </div>
            <form id="form">
                <div class="card-body">
                    <div id="info" class="d-none"></div>
                    <div class="form-group mt-5">
                        <label for="passkey">Your email</label>
                        <input type="email" class="form-control" name="email" id="email">

                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" id="submit" class="btn btn-primary" title="Receive email"> Send <span class="spinner spinner-grow spinner-grow-sm d-none"></span></button>
                </div>
            </form>
        </div>
    </main>

    <script src="../js/jquery3.6.0.js"></script>
    <script src="./js/admin.js"></script>

    <script>
        const submit = document.getElementById("submit"),
            message = document.getElementById('info');
        submit.addEventListener('click', () => sendToBackend(submit, $("#form"), './api/forgotten.php', 10000, message, 'no_element'));
    </script>
</body>

</html>