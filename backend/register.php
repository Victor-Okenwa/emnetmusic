<?php
# *********** USER REGISTRATION PAGE ************ #
# ---- Here we are grabing all user credentials
# ---- And inserting it into th database table users
require "connection.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

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

if (isset($_POST['type']) && !empty($_POST['type'])) {

    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else
        $ip_address = $_SERVER['REMOTE_ADDR'];

    $user_type = strtolower(inputValidation($conn, $_POST['type']));

    if ($user_type == 'user') {
        $firstname = strtolower(inputValidation($conn, $_POST['firstname']));
        $surname = strtolower(inputValidation($conn, $_POST['surname']));
        $nickname = strtolower(inputValidation($conn, $_POST['nickname']));
        $email = strtolower(inputValidation($conn, $_POST['email']));
        $password = trim($_POST['password']);
        $phonenumber = inputValidation($conn, $_POST['phonenumber']);
        $country = inputValidation($conn, $_POST['country']);
        $gender = inputValidation($conn, $_POST['gender']);

        if (!empty($firstname) && !empty($surname) && !empty($email) && !empty($email) && !empty($password) && !empty($phonenumber) && !empty($country)) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // lets check if email already exists in the database
                if (!isInDb($email, 'email', 'users') && !isInDb($email, 'email', 'admin') && !isInDb($email, 'email', 'unverified_users')) {
                    if (!isInDb($nickname, 'nickname', 'users') && !isInDb($nickname, 'nickname', 'admin') && !isInDb($nickname, 'nickname', 'unverified_users')) {
                        if (strlen($password) >= 6) {
                            $unique_id = time() . random_int(1000000000, 9999999999);
                            $hpassword = password_hash($password, PASSWORD_DEFAULT);
                            $token = random_int(10000, 99999);


                            while (isInDb($unique_id, 'user_id', 'users') || isInDb($unique_id, 'admin_id', 'admin')) {
                                $unique_id = time() . random_int(1000000000, 9999999999);
                                if (!isInDb($unique_id, 'user_id', 'users') || !isInDb($unique_id, 'admin_id', 'admin')) {
                                    break;
                                }
                            }

                            while (isInDb($token, 'token', 'unverified_users')) {
                                $token = random_int(10000, 99999);
                                if (!isInDb($token, 'token', 'unverified_users')) {
                                    break;
                                }
                            }

                            if (sendVerificationCode($email, $token)) {
                                $send_query = mysqli_query($conn, "INSERT INTO unverified_users 
                                (user_id, firstname, surname, nickname, email, password, phone_number, country, ip_address, gender, token, user_type) 
                                VALUES 
                                ('$unique_id', '$firstname', '$surname', '$nickname', '$email', '$hpassword', '$phonenumber', '$country', '$ip_address', '$gender', $token, 'user')");

                                if ($send_query) {
                                    setcookie('unverifed_id', $unique_id, time() + 60 * 60 * 24 * 3, '/');
                                    setcookie('unverifed_email', $email, time() + 60 * 60 * 24 * 3, '/');
                                    $response = ['status' => 'success', 'message' => "Verification mail sent ~ redirectng"];
                                } else {
                                    $response = ['status' => 'error', 'message' => "Request failed "];
                                }
                            } else {
                                $response = ['status' => 'error', 'message' => "Verification email did not send"];
                            }
                        } else {
                            $response = ['status' => 'error', 'message' => "Password must be more than 6 character length"];
                        }
                    } else {
                        $response = ['status' => 'error', 'message' => "Username already exists"];
                    }
                } else {
                    $response = ['status' => 'error', 'message' => "Email already exists"];
                }
            } else {
                $response = ['status' => 'error', 'message' => "$email is not a valid email"];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'All fields are required'];
        }
    } elseif ($user_type == 'team') {
        $founder = strtolower(inputValidation($conn, $_POST['founder']));
        $teamname = strtolower(inputValidation($conn, $_POST['teamname']));
        $email = strtolower(inputValidation($conn, $_POST['email']));
        $password = trim($_POST['password']);
        $phonenumber = inputValidation($conn, $_POST['phonenumber']);
        $country = inputValidation($conn, $_POST['country']);
        if (!empty($founder) && !empty($teamname) && !empty($email) && !empty($password) && !empty($phonenumber) && !empty($country)) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                if (!isInDb($email, 'email', 'users') && !isInDb($email, 'email', 'admin') && !isInDb($email, 'email', 'unverified_users')) {
                    if (!isInDb($teamname, 'nickname', 'users') && !isInDb($teamname, 'nickname', 'admin') && !isInDb($teamname, 'nickname', 'unverified_users')) {

                        if (strlen($password) >= 6) {
                            $unique_id = time() . random_int(1000000000, 9999999999);
                            $hpassword = password_hash($password, PASSWORD_DEFAULT);
                            $token = random_int(10000, 99999);
                            while (isInDb($unique_id, 'user_id', 'users') || isInDb($unique_id, 'admin_id', 'admin')) {
                                $unique_id = time() . random_int(1000000000, 9999999999);
                                if (!isInDb($unique_id, 'user_id', 'users') || !isInDb($unique_id, 'admin_id', 'admin')) {
                                    break;
                                }
                            }

                            while (isInDb($token, 'token', 'unverified_users')) {
                                $token = random_int(10000, 99999);
                                if (!isInDb($token, 'token', 'unverified_users')) {
                                    break;
                                }
                            }

                            if (sendVerificationCode($email, $token)) {
                                $send_query = mysqli_query($conn, "INSERT INTO unverified_users (user_id, firstname, nickname, email, password, phone_number, country, ip_address, token, user_type) 
                            VALUES ('$unique_id', '$founder', '$teamname', '$email', '$hpassword', '$phonenumber', '$country', '$ip_address', $token, 'team')");
                                if ($send_query) {
                                    setcookie('unverifed_id', $unique_id, time() + 60 * 60 * 24 * 3, '/');
                                    setcookie('unverifed_email', $email, time() + 60 * 60 * 24 * 3, '/');
                                    $response = ['status' => 'success', 'message' => "Verification mail sent ~ redirectng"];
                                } else {
                                    $response = ['status' => 'error', 'message' => "Request failed "];
                                }
                            } else {
                                $response = ['status' => 'error', 'message' => "Verification email did not send"];
                            }
                        } else {
                            $response = ['status' => 'error', 'message' => "Password must be more than 6 character length"];
                        }
                    } else {
                        $response = ['status' => 'error', 'message' => "Team name already exists"];
                    }
                } else {
                    $response = ['status' => 'error', 'message' => "Email already exists"];
                }
            } else {
                $response = ['status' => 'error', 'message' => "$email is not a valid email"];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'All fields are required'];
        }
    } else {
        $response = ['status' => 'error', 'message' => 'Response not found'];
    }
}
outputInJSON($response);
