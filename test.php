<?php
require_once('./backend/connection.php');

setcookie("test", "Testing123", time() + 60 * 60 * 24 * 30, '/', '', true, true);




// if(isset($_POST['send'])){
// for ($i = 0; $i < 30; $i++) {
//         $unique_id = time() . random_int(1000000000, 9999999999);
//         $bot_name = "dummy{$i}";
//         $bot_email = "dummy{$i}@gmail.com";
//         $bot_password = "dummy{$i}23";
//         $hash_password = password_hash($bot_password, PASSWORD_DEFAULT);
//         $insert_bot = $conn->query("INSERT INTO users (user_id, firstname, nickname, email, password, country, gender) 
//                VALUES ('$unique_id', '$bot_name', '$bot_name', '$bot_email', '$hash_password', 'Nigeria', 'Male')");

//         if ($insert_bot) {
//                 $insert_bot_artist = $conn->query("INSERT INTO creators (user_id, nickname, email) VALUES (
//                         '$unique_id',
//                         '$bot_name',
//                         '$bot_email'
//                 )");
//         }
// }
// }

// $today  = new DateTime();
// $account_date = "2023-10-17 08:05:14";
// $expires_after = 3;

// $request_expires = "$account_date + $expires_after days";
// $account_date = date_create($account_date);
// $request_expires = date_create($request_expires);




// $difference = date_diff($account_date, $today)->days;

// // echo  $today > $difference ? 'True' : 'False';

// // echo $difference->days . "<br>";

// $days_left = date_diff($request_expires, $today)->days;
// echo $days_left . '<br>';
// if ($request_expires > $today) {
//         echo "Account expired $days_left(s) ago";
// } else {
//         echo "Account expires in $days_left(s)";
// }

// highlight_string("--" . var_export($difference, true) . "--");

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;

// require 'PHPMailer/src/PHPMailer.php';
// require 'PHPMailer/src/SMTP.php';
// require "PHPMailer/src/Exception.php";

// $mail = new PHPMailer(true);

// $email = "okenwavictor003@gmail.com";
// try {
//         $mail->SMTPSecure = "ssl";
//         $mail->Host = 'smtp.gmail.com';
//         $mail->Port = '465';
//         $mail->Username   = 'emnetmusic.eu@gmail.com'; // SMTP account username
//         $mail->Password   = 'rxnkmrtnmzvxjpgg';
//         $mail->SMTPKeepAlive = true;
//         $mail->Mailer = "smtp";
//         $mail->IsSMTP(); // telling the class to use SMTP  
//         $mail->SMTPAuth   = true;                  // enable SMTP authentication  
//         $mail->CharSet = 'utf-8';
//         $mail->SMTPDebug  = 0;

//         $mail->setFrom("emnetmusic.eu@gmail.com", "Emnet Music");
//         $mail->addAddress($email, 'name');

//         $mail->isHTML(true);
//         $mail->Subject = "Email verification message";
//         $mail->Body = "
//         <b>This message is to verify your email account</b>
//         <p>Your verification code is:
//                 <b style='font-size: 25px; font-family:'Lucida Sans''>98772</b>
//         </p>
//         ";

//         // Set the timeout value for the SMTP connection
//         $timeout = 50;  // timeout after 50 seconds
//         $context = stream_context_create([
//                 'ssl' => [
//                         'socket' => [
//                                 'timeout' => $timeout,
//                         ],
//                 ],
//         ]);

//         $mail->SMTPOptions = array(
//                 'ssl' => array(
//                         'verify_peer' => false,
//                         'verify_peer_name' => false,
//                         'allow_self_signed' => true
//                 )
//         );

//         if ($mail->send()) {
//                 echo "Email sent sucessfully";
//         }
// } catch (Exception $e) {
//         echo  $e;
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
</head>

<body>
        <form action="" method="post">
                <input type="submit" name="send" value="Send">
        </form>
</body>

</html>

<!-- <script>
        var isLoading = false;
        var currentPage = 1;
        var resultsPerPage = 1;

        loadMoreResults();
        $("#loadmorebtn").click(function() {
                // console.error(e)
                // if (parseInt($(window).scrollTop().toFixed(1)) + $(window).height() >= $(document).height()) {
                loadMoreResults();
                // }
        })

        function loadMoreResults() {
                isLoading = true;
                $("#loadmorebtn").show();

                $.ajax({
                        url: 'send.php',
                        method: 'GET',
                        data: {
                                page: currentPage,
                                perPage: resultsPerPage,
                        },
                        success: function(data) {
                                $("#results").after();
                                currentPage++;
                                isLoading = false;
                                $("#loadmorebtn").html("Load more");
                        },
                        error: function() {
                                isLoading = false;
                                $("#loadmorebtn").html("Failed to load more results");
                        },
                        beforeSend: function() {
                                $("#loadmorebtn").html("Loading <span class='spinner-grow spinner-grow-sm'></span>");
                        }
                })
        }
</script> -->