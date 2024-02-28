<?php

/*  
----------------------------------------------------------------------------------
IN THIS FILE I'M UPDATING THE SPECIFIC CHAT READ STATUS
BY THE SPECIFIC SENDER
----------------------------------------------------------------------------------
*/

session_start();
require "../backend/connection.php";
$userID = $_SESSION["uniqueID"];
$senderID = inputValidation($conn, $_POST['senderID']);

// echo $senderID;

if (isset($_SESSION["uniqueID"])) {

    $query_sender = checkUserExists($userID);
    $query_receiver = checkUserExists($senderID);

    if ($query_receiver > 0 && $query_sender > 0) {
        // QUERY CHAT TABLE
        $query_read_chat = $conn->query("SELECT * FROM chats WHERE receiver_id = '{$userID}' AND sender_id = '{$senderID}' AND read_status = 0");

        $num_rows = $query_read_chat->num_rows;
        if ($num_rows > 0) {
            // updating read status to read that is {1} if it is 0 that is unread
            $update_read_status = $conn->query("UPDATE chats SET 
                read_status = 1
                WHERE receiver_id = '{$userID}' AND sender_id = '{$senderID}'
            ");
        }
    }
}
