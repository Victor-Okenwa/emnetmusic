<?php

/*  
----------------------------------------------------------------------------------
IN THIS SCRIPT I'M CHECKING FOR UPDATES ON THE SPECIFIC CHAT 
UNDER THE CURRENT SENDER THIS SCRIPT WILL 
----------------------------------------------------------------------------------
*/

session_start();
require "../backend/connection.php";

if (isset($_SESSION['uniqueID'])) {

    $userID = $_SESSION['uniqueID'];
    $senderID = inputValidation($conn, $_POST['senderID']);

    $output = "";

    // QUERY CHAT TABLE
    $query_chat = $conn->query("SELECT * FROM chats WHERE (receiver_id = '{$userID}' AND sender_id = '{$senderID}')");


    $num_rows_chat = $query_chat->num_rows;
    $array_status = [];
    if ($num_rows_chat > 0) {
        while ($fetch_chats = $query_chat->fetch_assoc()) {
            array_push($array_status, $fetch_chats['read_status']);
        }

        if (in_array(0, $array_status)) {
            // there is an unread message
            $output = 1;
        } else {
            // there no unread message
            $output = 0;
        }
    }



    echo $output;
} else {
    echo "Not Sender";
}
