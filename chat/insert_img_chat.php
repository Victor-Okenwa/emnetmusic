<?php
session_start();
require "../backend/connection.php";

$iv = openssl_random_pseudo_bytes(16);
while (true) {
    if (strlen($iv) !== 16) {
        $iv = str_pad($iv, 16, "\0");
    } else {
        break;
    }
}

$senderID = inputValidation($conn, $_POST['image_sender_id']);
$receiverID = inputValidation($conn, $_POST['image_receiver_id']);
$message = inputValidation($conn, $_POST['image_msg']);
$image = $_POST['imageFile'];

$type = "img";
$date = date("d M Y h:i A");

if (!empty($senderID) && !empty($receiverID) && !empty($image)) {

    $query_sender = $conn->query("SELECT * FROM users WHERE user_id = '{$senderID}'");
    $query_receiver = $conn->query("SELECT * FROM users WHERE user_id = '{$receiverID}'");

    if ($query_sender->num_rows > 0 && $query_receiver->num_rows > 0) {
        function generateFilename($extension)
        {
            $filename = uniqid() . random_int(100, 9999);
            return $filename . '.' . $extension;
        }

        $data_from_sender = $query_sender->fetch_assoc();
        $data_from_receiver = $query_receiver->fetch_assoc();

        $sender_name = $data_from_sender['nickname'];
        $receiver_name = $data_from_receiver['nickname'];
        $image = str_replace('data:image/jpeg;base64,', '', $image);
        $image = base64_decode($image);
        $filename = generateFilename('jpg');

        $folder = "uploads/";

        if (!empty($message)) {
            $message = openssl_encrypt($message, CIPHER_METHOD, HASH_KEY, $options, $iv);
            $type = "text/img";
        }
       
        $insert_msg = $conn->query("INSERT INTO chats (sender_id, receiver_id, message, iv, image, type, date)      VALUES ('{$senderID}', '{$receiverID}', '{$message}', '{$iv}', '{$filename}', '{$type}', '{$date}')");

        if ($insert_msg) {
            if (file_put_contents($folder . $filename, $image)) {
                $response = array('status' => 'success', 'message' => "");
            } else {
                $response = array('status' => 'error', 'message' => "Image Upload error");
            }
        } else {
            $response = array('status' => 'error', 'message' => "Upload error");
        }
    } else {

        $response = array('status' => 'error', 'message' => "User Not found");
    }
} else {

    $response = array('status' => 'error', 'message' => "File cannot be empty");
}
header("Content-Type: application/json");
echo json_encode($response);
$conn->close();
