<?php
require "../assets/access.php";

if ($admin_rank == "super admin") {
    $firstname = inputValidation($conn, $_POST['firstname']);
    $lastname = inputValidation($conn, $_POST['lastname']);
    $nickname = inputValidation($conn, $_POST['nickname']);
    $email = inputValidation($conn, $_POST['email']);
    $password = inputValidation($conn, $_POST['password']);
    $confirm_password = inputValidation($conn, $_POST['confirm_password']);
    $phone_number = inputValidation($conn, $_POST['phone_number']);

    if (!empty($firstname) && !empty($lastname) && !empty($nickname) && !empty($email) && !empty($password) && !empty($confirm_password) && !empty($phone_number)) {
        if ($password === $confirm_password) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                if (!isInDb($nickname, 'nickname', 'users') && !isInDb($nickname, 'nickname', 'admin')) {
                    if (!isInDb($email, 'email', 'users') && !isInDb($email, 'email', 'admin')) {

                        $unique_id = random_int(1000000000, 9900000000);
                        $passkey =  random_int(1000000, 9900000);
                        while (isInDb($unique_id, 'admin_id', 'admin')) {
                            $unique_id = random_int(1000000, 9900000);
                        }
                        while (isInDb($passkey, 'passkey', 'admin')) {
                            $passkey = random_int(1000000, 9900000);
                        }

                        $hpassword = password_hash($password, PASSWORD_DEFAULT);
                        $admin_rank = "admin";

                        $query_insert = mysqli_query($conn, "INSERT INTO admin 
                        (admin_id, firstname, lastname, nickname, email,	password, passkey, admin_rank,	phone_number, status, remember_me)
                         VALUES ($unique_id, '$firstname', '$lastname', '$nickname', '$email', '$hpassword', $passkey, '$admin_rank', '$phone_number', 'Active Now', '0')");

                        if ($query_insert) {
                            $response = ['status' => 'success', 'message' => "Admin added Passkey:  <b>$passkey</b>, copy before 5 minutes or resfreshing"];
                        } else {
                            $response = ['status' => 'error', 'message' => "Failed to add admin"];
                        }
                    } else {
                        $response = ['status' => 'error', 'message' => "Email: $email already exists"];
                    }
                } else {
                    $response = ['status' => 'error', 'message' => "Nickname: $nickname already exists"];
                }
            } else {
                $response = ['status' => 'error', 'message' => "$email is not a valid email"];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Passwords does not match'];
        }
    } else {
        $response = ['status' => 'error', 'message' => 'All fields are required'];
    }
} else {
    $response = ['status' => 'error', 'message' => 'Request is not allowed'];
}

outputInJSON($response);
