<?php
require_once "../assets/access.php";
$email = inputValidation($conn, $_POST['email']);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "../../PHPMailer/src/Exception.php";
require_once '../../PHPMailer/src/PHPMailer.php';
require_once '../../PHPMailer/src/SMTP.php';

if (existsWith($email, $admin_id, 'email', 'id', 'admin')) {
    $token = random_int(1000, 80000);

    while (isInDb($token, 'token', 'tokens')) {
        $token = random_int(1000, 80000);
        if (!isInDb($token, 'token', 'tokens')) {
            break;
        }
    }

    $token = htmlspecialchars($token);

    $mail = new PHPMailer(true);

    try {
        //Server settings
        // $mail->SMTPDebug = 0;
        // $mail->isSMTP();
        // $mail->Host       = 'smtp.gmail.com';
        // $mail->Port       = 587;
        // $mail->SMTPSecure = 'tls';
        // $mail->SMTPAuth   = true;
        // $mail->Username   = 'emnetmusic.eu@gmail.com';
        // $mail->Password   = 'rxnkmrtnmzvxjpgg';

        $mail->SMTPSecure = "ssl";
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = '465';
        $mail->Username   = 'emnetmusic.eu@gmail.com'; // SMTP account username
        $mail->Password   = 'rxnkmrtnmzvxjpgg';
        $mail->SMTPKeepAlive = true;
        $mail->Mailer = "smtp";
        $mail->IsSMTP(); // telling the class to use SMTP  
        $mail->SMTPAuth   = true;                  // enable SMTP authentication  
        $mail->CharSet = 'utf-8';
        $mail->SMTPDebug  = 0;
        $mail->setFrom("emnetmusic.eu@gmail.com", "Emnet Music");
        $mail->addAddress($email);
        // https://emnetmusic.com/reset_password.php?token=$token&&email=$email'
        //Content
        $mail->isHTML(true);
        $mail->Subject = "Emnet request notification";
        $mail->Body = "
        <h1> Hello from emnet </h1>
        <h3> We sent you this email because you requested for a passkey reset </h3>
        <br/><br/>
        <a href='https://emnetmusic.com/admin/reset_password.php?token=$token&&email=$email'
        style='padding:1.5% 7%;background: #7ee0f8;border-radius:15px; font-size:17px; font-weight:bold; text-decoration: none; width: 100%; text-align: center'> Click here </a>";

        // Set the timeout value for the SMTP connection
        // $timeout = 50;  // timeout after 50 seconds
        // $context = stream_context_create([
        //     'ssl' => [
        //         'socket' => [
        //             'timeout' => $timeout,
        //         ],
        //     ],
        // ]);
        // stream_context_set_option($context, 'ssl', 'verify_peer', false);
        // $mail->SMTPOptions = [
        //     'ssl' => [
        //         'verify_peer' => false,
        //         'verify_peer_name' => false,
        //     ],
        // ];
        // $mail->smtpConnect([
        //     'ssl' => [
        //         'verify_peer' => false,
        //         'verify_peer_name' => false,
        //         'verify_depth' => 3,
        //         'cafile' => '/etc/ssl/certs/ca-certificates.crt',
        //     ],
        // ]);


        if ($mail->send()) {
            $response = array('status' => 'success', 'message' => 'Email sent successfully');
            if (existsWith($email, $admin_unique_id, 'email', 'user_id', 'tokens')) {
                $new_token = $conn->query("UPDATE tokens SET token = '$token' WHERE email = '$email'");
            } else {
                $new_token = $conn->query("INSERT INTO tokens(user_id, email, token) values($admin_unique_id, '{$email}', $token)");
            }
        }
    } catch (Exception $e) {
        $response = array('status' => 'error', 'message' => "Email could not be sent. Check your internet connection and try again <br><small>If error persists refresh this page</small>");
    }
} else {
    $response = ['status' => 'error', 'message' => "Email not found"];
}


outputInJSON($response);
