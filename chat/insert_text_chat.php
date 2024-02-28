<?php
session_start();
require("../backend/connection.php");

// The Encryption identification vector 125-bits algorithm
$iv = openssl_random_pseudo_bytes(16);


while (true) {
    if (strlen($iv) !== 16) {
        $iv = str_pad($iv, 16, "\0");
    } else {
        break;
    }
}

$senderID = inputValidation($conn, $_POST['senderID']);
$receiverID = inputValidation($conn, $_POST['receiverID']);
$message = trim($_POST['message']);

if (!empty($senderID) && !empty($receiverID) && !empty($message)) {

    $message = openssl_encrypt($message, CIPHER_METHOD, HASH_KEY, $options, $iv);

    $query_sender = $conn->query("SELECT * FROM users WHERE user_id = '{$senderID}'");
    $query_receiver = $conn->query("SELECT * FROM users WHERE user_id = '{$receiverID}'");

    if ($query_sender->num_rows > 0 && $query_receiver->num_rows > 0) {

        $data_from_sender = $query_sender->fetch_assoc();
        $data_from_receiver = $query_receiver->fetch_assoc();

        $sender_name = $data_from_sender['nickname'];
        $receiver_name = $data_from_receiver['nickname'];

        $type = "text";
        $date = date("d M Y h:i A");
        // sender_name, receiver_name,
        $insert_msg = $conn->query("INSERT INTO chats(sender_id, receiver_id, message, iv, type, date) 
        VALUES('$senderID', '$receiverID', '$message', '$iv' ,'$type', '$date')");

        if ($insert_msg) {

            $response = array('status' => 'success', 'message' => 'Message was inserted');
        } else {

            $response = array('status' => 'error', 'message' => mysqli_error($conn));
        }
    }
} else {
    $response = array('status' => 'error', 'message' => "Message empty");
}

header("Content-Type: application/json");
echo json_encode($response);
$conn->close();
