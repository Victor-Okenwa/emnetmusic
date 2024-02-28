<?php require_once "./assets/access.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "./assets/vendors.html" ?>
    <?php include "./assets/datatables.html" ?>
</head>

<body class="bg-dark text-bg-dark">
    <?php include "./assets/navbar.php" ?>

    <div id="top_page"></div>

    <main class="container-fluid">
        <!-- <div class="alert alert-warning text-center d-flex align-items-center g-5" role="alert">
            <button class="btn d-inline-block" type="button" data-dismiss="alert" aria-label="Close"><i class="fa fa-times"></i></button>
            <div class="text-center" style="width: 90%;">The admin passkey is -- <b>45289292</b> copy before refresh </div>
            <?php
            if (isset($_SESSION['passkey']) && $_SESSION['passkey'] != ' ') {
                echo '<h2 class="text-light p-2"> ' . $_SESSION['passkey'] . '</h2>';
            }
            ?>
            <?= (isset($_SESSION['info']) && $_SESSION['info'] !== "") ? "<div class='p-2 text-light text-center'>" . $_SESSION['info'] . "</div>" : "";
            unset($_SESSION['info']);
            ?>
        </div> -->

        <div id="output" style="text-align: center; font-size:20px" class="d-none"></div>

        <form id="adminForm" class="was-validated">
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="text-light ml-3" for="firstname">Firstname</label>
                    <input type="text" class="form-control is-invalid" id="firstname" placeholder=" Firstname" name="firstname" required>
                </div>

                <div class="mb-3 col-md-6">
                    <label class="text-light ml-3" for="lastname">Lastname</label>
                    <input type="text" class="form-control is-invalid" id="lastname" placeholder=" Surname" name="lastname" required="">
                </div>

                <div class="mb-3 col-md-6">
                    <label class="text-light ml-3" for="nickname">Nickname</label>
                    <input type="text" class="form-control is-invalid" id="nickname" placeholder="Nickname" name="nickname" required>
                </div>

                <div class="mb-3 col-md-6">
                    <label class="text-light ml-3" for="email">Email</label>
                    <input type="email" class="form-control is-invalid" id="email" name="email" placeholder="Email" required>
                </div>

                <div class="mb-3 col-md-6">
                    <label class="text-light ml-3" for="password">Password</label>
                    <input type="password" class="form-control is-invalid" id="password" placeholder="Password must not be less than 6" name="password" required minlength="6">
                </div>

                <div class="mb-3 col-md-6">
                    <label class="text-light ml-3" for="password_confirmation">Confirm Password</label>
                    <input type="password" class="form-control is-invalid" id="password_confirmation" placeholder="Confirm password" name="confirm_password" required minlength="6">
                </div>

                <div class="mb-3 col-md-6">
                    <label class="text-light ml-3" for="contact">Phone number</label>
                    <input type="number" class="form-control is-invalid" id="contact" placeholder="Contact" name="phone_number" required="">
                </div>
            </div>
            <div class="mb-3">
                <button class="btn btn-primary" type="button" id="submitBtn">
                    Submit form
                    <span class="spinner spinner-grow spinner-grow-sm d-none" id="loader"></span>
                </button>
            </div>
        </form>
    </main>

    <?php include "./assets/footer.html" ?>
    <?php include "./assets/scripts.html" ?>

    <script>
        $(document).ready(() => {
            const submitBtn = document.getElementById("submitBtn"),
                adminForm = $("#adminForm"),
                loader = $("#loader"),
                output = document.getElementById("output");

            submitBtn.addEventListener('click', () => {
                const formData = adminForm.serialize();

                adminForm.onsubmit - function(e) {
                    e.preventDefault();
                }

                $.ajax({
                    url: './api/add_admin.php',
                    method: "POST",
                    timeout: 50000,
                    data: formData,
                    beforeSend: function() {
                        loader.show();
                    },
                    complete: function() {
                        loader.hide();
                    },
                    error: function(xhr, status, error) {
                        loader.hide();
                        if (status === 'timeout') {
                            outputMessage(output, 'Request Timed Out', 5000, 'error');
                        } else {
                            outputMessage(output, 'Error: ' + error, 5000, 'error');
                        }
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            outputMessage(output, response.message, 300000, 'success');
                        } else {
                            outputMessage(output, response.message, 5000, 'error');
                        }
                    }
                })
            });
        });
    </script>
</body>

</html>