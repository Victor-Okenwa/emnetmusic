<?php
/*  
----------------------------------------------------------------------------------
IN THIS SCRIPT I'M CHECKING FOR UPDATES ON VISIBILTY DELETES
IN ORDER TO REMOVE THEM
----------------------------------------------------------------------------------
*/

session_start();
require "../backend/connection.php";

if (isset($_SESSION['uniqueID'])) {
    $userID = $_SESSION['uniqueID'];
    $senderID = inputValidation($conn, $_POST['senderID']);

    $output;

    $query_visibility = $conn->query("SELECT chat_id FROM chats WHERE (receiver_id = '{$userID}' AND sender_id = '{$senderID}' AND visibility = 1)");
    $num_rows_visibility = $query_visibility->num_rows;
    $array_visibility_id = [];

    if ($num_rows_visibility > 0) {
        $output = 2;
        // $fetch_visibilty = $query_visibility->fetch_assoc()['chat_id'];
        while ($fetch_visibilty = $query_visibility->fetch_assoc()) {
            array_push($array_visibility_id,  $fetch_visibilty['chat_id']);
        }

        $output = ['status' => 1, 'message_id' => $array_visibility_id];

        header('Content-Type: application/json');
        echo json_encode($output);

        usleep(5000);

        foreach ($array_visibility_id as $id) {
            $delete = $conn->query("DELETE FROM chats WHERE chat_id = {$id} and visibility = 1");
        }
    }
}
