<?php require "./assets/access.php";
$hash_key = "my_secret_key";
$CIPHER_METHOD = "AES-256-CBC";
$options = 0;
?>
<!DOCTYPE html>
<html lang="en" id="passwayPage">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once './assets/vendors.html'  ?>

    <title>Admin Passway</title>
</head>

<body class="bg-white d-flex justify-content-center align-items-center min-vh-100">


    <div class="card shadow-lg">
        <div class="card-header">
            <p>Welcome <b class="adminname"><?= $admin_name ?></b> would you like to sign in?</p>
        </div>
        <div class="card-body">
            <div id="info" class="d-none"></div>
            <form id="passwayForm">
                <div class="form-group mt-5">
                    <label for="passkey">Your admin passkey</label>
                    <input type="number" class="form-control" name="passkey" id="passkey" value="<?= isset($_COOKIE['passkey']) && isset($_COOKIE['IV']) ? openssl_decrypt($_COOKIE['passkey'], $CIPHER_METHOD, $hash_key, $options, $_COOKIE['IV']) : ''  ?>">
                    
                    <div class="w-100 text-right">
                        <a href="forgotten.php">Forgotten passkey?</a>
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="remember" name="remember" <?= isset($_COOKIE['passkey']) ? "checked" : '' ?>>
                        <label class="custom-control-label" for="remember">Remember Admin Passkey</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <button type="button" id="submit" class="btn btn-primary" title="Submit passkey">Submit <span class="spinner spinner-grow spinner-grow-sm d-none"></span></button>
        </div>
    </div>

    <script src="../js/jquery3.6.0.js"></script>
    <script src="./js/admin.js"></script>

    <script>
        const submit = document.getElementById("submit"),
            message = document.getElementById('info');
        submit.addEventListener('click', () => sendToBackend(submit, $("#passwayForm"), './api/passway.php', 20000, message, 'no_element'));
    </script>
</body>

</html>