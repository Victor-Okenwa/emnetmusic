<?php
session_start();
require "./backend/connection.php";
$email = inputValidation($conn, $_POST['email']);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';



if ($email !== "") {

    $query_email_users = $conn->query("SELECT * FROM users WHERE email = '{$email}'");
    $query_email_admin = $conn->query("SELECT * FROM admin WHERE email = '{$email}'");

    $user_type = null;

    if ($query_email_users->num_rows > 0) {
        $user_type = 0;

        $row = $query_email_users->fetch_assoc();
        $get_user_id = $row['user_id'];
    } elseif ($query_email_admin->num_rows > 0) {
        $user_type = 1;

        $row = $query_email_admin->fetch_assoc();
        $get_user_id = $row['admin_id'];
    } else {
        $user_type = null;
    }


    if ($user_type !== null) {

        $token = time() + random_int(2000000, 6000000);

        $query_tokens = $conn->query("SELECT * from tokens WHERE email = '{$email}' and user_id = '{$get_user_id}' ");
        $query_exist_tok = $conn->query("SELECT token from tokens WHERE token = $token");

        while ($query_exist_tok->num_rows > 0) {
            $token = time() + random_int(2000000, 6000000);
            return;
        }

        $token = htmlspecialchars($token);


        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->Port       = 587;
            $mail->SMTPSecure = 'tls';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'emnetmusic.eu@gmail.com';
            $mail->Password   = 'rxnkmrtnmzvxjpgg';

            $mail->SMTPKeepAlive = true;
            $mail->setFrom("emnetmusic.eu@gmail.com", "Emnet Music");
            $mail->addAddress($email);

            //Content
            $mail->isHTML(true);
            $mail->Subject = "Emnet password notification";
            $mail->Body = "
            <h1> Hello from emnet </h1>
            <h3> We sent you this email because you requested for a password reset </h3>
            <br/><br/>
            <a href='https://emnetmusic.com/reset_password.php?token=$token&&email=$email'
            style='background-color: green; padding: 3px 5px; font-size: 20px; border-radius: 3px; color: white; text-decoration: none; width: 100%; text-align: center'> Click here </a>";

            // Set the timeout value for the SMTP connection
            $timeout = 50;  // timeout after 50 seconds
            $context = stream_context_create([
                'ssl' => [
                    'socket' => [
                        'timeout' => $timeout,
                    ],
                ],
            ]);
            stream_context_set_option($context, 'ssl', 'verify_peer', false);
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ];
            $mail->smtpConnect([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'verify_depth' => 3,
                    'cafile' => '/etc/ssl/certs/ca-certificates.crt',
                ],
            ]);


            if ($mail->send()) {
                $response = array('status' => 'success', 'message' => 'Email sent successfully');
                if ($query_tokens->num_rows > 0) {
                    $new_token = $conn->query("UPDATE tokens SET token = '$token' WHERE email = '$email'");
                } else {
                    $new_token = $conn->query("INSERT INTO tokens(user_id, email, token) values('{$get_user_id}', '{$email}', '{$token}')");
                }
            }
        } catch (Exception $e) {
            $response = array('status' => 'error', 'message' => "<span style='font-size: 14px'>Email could not be sent. Check your internet connection and try again <br><small>If error persists refresh this page</small></span>");
        }
    } else {
        $response = array('status' => 'error', 'message' => "Email address not found");
    }

    echo json_encode($response);
}
