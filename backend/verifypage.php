<?php
require "connection.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require "../PHPMailer/src/Exception.php";

function sendVerificationCode($email, $token)
{
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPSecure = "ssl";
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = '465';
        $mail->Username   = 'emnetmusic.eu@gmail.com'; // SMTP account username
        $mail->Password   = 'rxnkmrtnmzvxjpgg';
        $mail->Mailer = "smtp";
        $mail->IsSMTP(); // telling the class to use SMTP  
        $mail->SMTPAuth   = true;                  // enable SMTP authentication  
        $mail->CharSet = 'utf-8';
        $mail->SMTPDebug  = 0;

        $mail->setFrom("emnetmusic.eu@gmail.com", "Emnet Music");
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Email verification";
        $mail->Body = "
        <b>This message is to verify your email account</b>
        <p>Your verification code is:
                <b style='font-size: 25px; font-family:'Lucida Sans''>$token</b>
        </p>
        ";

        // Set the timeout value for the SMTP connection
        $timeout = 50;  // timeout after 50 seconds
        stream_context_create([
            'ssl' => [
                'socket' => [
                    'timeout' => $timeout,
                ],
            ],
        ]);

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        if ($mail->send()) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}

// $response = [];

if ($_POST['type'] == 'verify') {
    $email = strtolower(inputValidation($conn, $_POST['email']));
    $token =  inputValidation($conn, $_POST['token']);


    if (!empty($email) && !empty($token)) {
        if (isInDb($email, 'email', 'unverified_users') && isInDb($token, 'token', 'unverified_users')) {
            if (existsWith($email, $token, 'email', 'token', 'unverified_users')) {
                $query_user = $conn->query("SELECT * FROM unverified_users WHERE email = '$email'")->fetch_assoc();
                $user_id = $query_user['user_id'];
                $user_type = $query_user['user_type'];
                $user_name = $query_user['nickname'];
                $email = $query_user['email'];
                $password = $query_user['password'];
                $phone_number = $query_user['phone_number'];
                $country = $query_user['country'];
                $ip_address = $query_user['ip_address'];

                $date = date('d M, Y - H:i');
                if ($user_type == 'user') {
                    $firstname = $query_user['firstname'];
                    $surname = $query_user['surname'];
                    $gender = $query_user['gender'];

                    $move_user = $conn->query("INSERT INTO users 
                (user_id, firstname, surname, nickname, email, password, phone_number, country, ip_address, gender, user_type, created_on)
                VALUES ('$user_id', '$firstname', '$surname', '$user_name', '$email', '$password', '$phone_number', '$country', '$ip_address', '$gender', 'user', '$date')
                ");

                    if ($move_user) {
                        if ($conn->query("DELETE FROM unverified_users WHERE email = '$email'")) {
                            $response = ['status' => 'success', 'message' => "Your account has been verified"];
                        } else {
                            $response = ['status' => 'success', 'message' => "Request failed 2"];
                        }
                    } else {
                        $response = ['status' => 'success', 'message' => "Request failed"];
                    }
                } else {
                    $founder = $query_user['firstname'];
                    $move_user = $conn->query("INSERT INTO users 
                (user_id, firstname, nickname, email, password, phone_number, country, ip_address, user_type, created_on)
                VALUES ('$user_id', '$founder', '$user_name', '$email', '$password', '$phone_number', '$country', '$ip_address', 'team', '$date')
                ");
                    setcookie('unverifed_id', '', time() - 3600, '/');
                    setcookie('unverifed_email', '', time() - 3600, '/');
                    if ($move_user) {
                        if ($conn->query("DELETE FROM unverified_users WHERE email = '$email'")) {
                            $response = ['status' => 'success', 'message' => "Your account has been verified"];
                        } else {
                            $response = ['status' => 'success', 'message' => "Request failed 2"];
                        }
                    } else {
                        $response = ['status' => 'success', 'message' => "Request failed"];
                    }
                }
            } else {
                $response = ['status' => 'error', 'message' => "Email and token not found"];
            }
        } else {
            $response = ['status' => 'error', 'message' => "Email or token not found"];
        }
    } else {
        $response = ['status' => 'error', 'message' => 'All field are required'];
    }
} elseif ($_POST['type'] == 'resend') {
    $email = strtolower(inputValidation($conn, $_POST['email']));
    $user_id =  inputValidation($conn, $_POST['user_id']);

    $response = ['status' => 'success', 'message' => $email . $user_id];


    if (!empty($email) && !empty($user_id)) {
        if (existsWith($email, $user_id, 'email', 'user_id', 'unverified_users')) {

            $token = $conn->query("SELECT token FROM unverified_users WHERE email = '$email'")->fetch_assoc()['token'];

            if (sendVerificationCode($email, $token)) {
                $response = ['status' => 'success', 'message' => 'Email was sent'];
            } else {
                $response = ['status' => 'error', 'message' => 'Email was not sent, try again'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Your email and id not found'];
        }
    } else {
        $response = ['status' => 'error', 'message' => 'Your email or id is empty'];
    }
} else {
    $response = ['status' => 'error', 'message' => 'Request not found'];
}
outputInJSON($response);
