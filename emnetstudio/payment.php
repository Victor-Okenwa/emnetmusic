<?php
session_start();
include_once "../backend/connection.php";

if (isset($_SESSION["uniqueID"])) {
    $unique_id = $_SESSION['uniqueID'];

    $query_user = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '{$_SESSION["uniqueID"]}' ");
    if (mysqli_num_rows($query_user) > 0) {
        $fetch_user = mysqli_fetch_assoc($query_user);
        $email = $fetch_user['email'];
        $nickname = $fetch_user['nickname'];
    }

    if (isset($_POST['submit'])) {
        $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
        $nickname = mysqli_real_escape_string($conn, $_POST['nickname']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $amount = mysqli_real_escape_string($conn, $_POST['amount']);
        $account = mysqli_real_escape_string($conn, $_POST['account']);
        $account_number  = mysqli_real_escape_string($conn, $_POST['account_number']);
        $payment_type  = mysqli_real_escape_string($conn, $_POST['payment_type']);
        $payment_way  = mysqli_real_escape_string($conn, $_POST['payment_way']);

        if (!empty($user_id) && !empty($nickname) && !empty($email) && !empty($amount) && !empty($account_number)) {

            $insert_payment = mysqli_query($conn, "INSERT INTO payments (user_id, nickname, email, account_name, account_number, amount, payment_type, payment_way) VALUES ($user_id, '$nickname', '$email', '$account', $account_number, '$amount', '$payment_type',	'$payment_way')");

            if ($insert_payment) {
                $query_request = mysqli_query($conn, "UPDATE requests SET status = '2' WHERE user_id = $user_id");

                $insert_creator = mysqli_query($conn, "INSERT INTO creators (user_id, nickname, email) VALUES ($user_id, '$nickname', '$email')");

                if ($insert_creator) {
                    $_SESSION['msg'] = "Insert and update successful";
                    header("Location: index.php");
                } else {
                    $_SESSION['msg'] = "Insert into creator went wrong";
                }
            } else {
                $_SESSION['msg'] = "Insert into payments went wrong";
            }
        } else {
            $account = "";
            $account_number  = "";
            $_SESSION['msg'] = "All Input filelds are required";
        }
    }
} else {
    die();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Register</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Pay Here!</h1>
                            </div>
                            <?php
                            if (isset($_SESSION['msg'])) {
                                echo '<div class="btn btn-facebook mb-3"> ' . $_SESSION['msg'] . ' </div>';
                                unset($_SESSION['msg']);
                            } else {
                            }
                            ?>
                            <div class="text-center my-2 fa-2x">Amount to be paid: ₦25,000</div>
                            <form action="" method="POST" class="user">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="exampleFirstName" name="user_id" value="<?= $unique_id ?>" placeholder="First Name" hidden>
                                    </div>
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="exampleFirstName" name="nickname" value="<?= $nickname ?>" hidden>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" id="exampleLastName" name="amount" value="25000" hidden>
                                    </div>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control form-control-user" id="exampleLastName" name="account" placeholder="Account name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="number" class="form-control form-control-user" id="exampleInputEmail" name="account_number" placeholder="Account number">
                                </div>

                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="exampleInputEmail" name="email" value="<?= $email ?>" placeholder="Email Address" hidden>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <select name="payment_type" id="" class="form-control rounded-pill">
                                            <option value="card" selected>Card</option>
                                            <option value="bank">Bank</option>
                                            <option value="USSD">USSD</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <select name="payment_way" id="" class="form-control rounded-pill">
                                            <option value="visa" selected>Visa</option>
                                            <option value="verve">Verve</option>
                                            <option value="interswitch">Interswitch</option>
                                            <option value="mastercard">Mastercard</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" href="login.html" class="btn btn-primary btn-user btn-block" name="submit">
                                    Pay
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>