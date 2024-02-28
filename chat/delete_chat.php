<?php
session_start();
require_once "../backend/connection.php";

$messageID = inputValidation($conn, $_POST['messageID']);

$query_chats = $conn->query("SELECT * FROM chats WHERE chat_id = $messageID");


if ($query_chats->num_rows > 0) {

    $fetch_chat = $query_chats->fetch_assoc();

    $msg_type = $fetch_chat['type'];

    if ($msg_type == 'img' || $msg_type == 'text/img') {
        $file = $fetch_chat['image'];
        $folder = "uploads/";

        if (file_exists($folder . $file)) {
            if (unlink($folder . $file)) {
                $invisible = $conn->query("UPDATE chats SET visibility = 1 WHERE chat_id = {$messageID}");

                if ($invisible) {
                    $response = array('status' => 'success', 'message' => "Message Deleted", 'message_id' => $messageID);
                } else {
                    $response = array('status' => 'error', 'message' => "Delete Failed");
                }
            }
        }
    } else {
        $invisible = $conn->query("UPDATE chats SET visibility = 1 WHERE chat_id = {$messageID}");
        if ($invisible) {
            $response = array('status' => 'success', 'message' => "Message Deleted", 'message_id' => $messageID);
        } else {
            $response = array('status' => 'error', 'message' => "Delete Failed");
        }
    }
} else {

    $response = array('status' => 'error', 'message' => "Message Not Found");
}

header("Content-Type: application/json");
echo json_encode($response);
