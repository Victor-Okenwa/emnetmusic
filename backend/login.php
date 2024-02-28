<?php
session_start();
require_once "./connection.php";
$iv = openssl_random_pseudo_bytes(16);

while (true) {
    if (strlen($iv) !== 16) {
        $iv = str_pad($iv, 16, "\0");
    } else {
        break;
    }
}

$email =  strtolower(inputValidation($conn, $_POST['email']));
$password = trim($_POST['password']);

if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $sql_login = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}' ");

    $sql_login_admin = mysqli_query($conn, "SELECT * FROM admin WHERE email = '{$email}' ");

    if (mysqli_num_rows($sql_login) > 0) {
        $data = mysqli_fetch_assoc($sql_login);
        $hpassword = $data['password'];
        $status = "Active Now";
        $remember = "0";

        if (password_verify($password, $hpassword)) {
            if (isset($_POST['remember'])) {
                setcookie("iv", $iv, 60 * 60 * 24 * 30, '/');
                setcookie("userId", $data['id'], time() + 1500000, '/');
                setcookie("uniqueId", $data['user_id'], time() + 1500000, '/', true);
                setcookie("Nickname", $data['nickname'], time() + 1500000, '/');
                setcookie("Email", $email, time() + 1500000, '/');
                setcookie("Password", encryptData($password, "my_secret_key", $iv), time() + 1500000, '/', true, true);
                setcookie("Status", $status, time() + 1500000, '/');
            } else {
                if (count($_COOKIE) > 0) {
                    setcookie("iv", "");
                    setcookie("userId", "");
                    setcookie("uniqueId", "");
                    setcookie("Nickname", "");
                    setcookie("Email", "");
                    setcookie("Password", "");
                    setcookie("Status", "");
                }
            }
            $_SESSION['ID'] = $data['id'];
            $_SESSION['uniqueID'] = $data['user_id'];
            $_SESSION['status'] = $data['status'];
            $response = array('status' => 'success', 'message' => 'You have been logged in ~ redirecting');
        } else {
            $response = array('status' => 'error', 'message' => 'Incorrect Password');
        }
    } elseif (mysqli_num_rows($sql_login_admin) > 0) {
        // LOGIN BLOCK FOR ADMIN
        $data = mysqli_fetch_assoc($sql_login_admin);
        $hpassword = $data['password'];
        $status = "Active Now";
        $remember = "0";

        if (password_verify($password, $hpassword)) {

            if ($remember == 1) {
                setcookie("adminId", $data['id'], time() + 8035200,  '/');
                setcookie("adminUniqueId", $data['admin_id'], time() + 8035200,  '/');
                setcookie("Email", $_POST['email'], time() + 8035200,  '/');
                setcookie("Password", $_POST['password'], time() + 8035200,  '/');
            } else {
                setcookie("adminId", "");
                setcookie("adminUniqueId", "");
                setcookie("Email", "");
                setcookie("Password", "");
            }
            $_SESSION['adminID'] = $data['id'];
            $_SESSION['adminUniqueID'] = $data['admin_id'];
            $_SESSION['status'] = $data['status'];
            $response = array('status' => 'success', 'message' => 'admin');
        } else {
            $response = array('status' => 'error', 'message' => 'Incorrect password');
        }
    } else {
        $response = array('status' => 'error', 'message' => 'Incorrect email or password');
    }
} else {
    $response = array('status' => 'error', 'message' => "$email is invalid");
}
outputInJSON($response);
