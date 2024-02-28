<?php
session_start();
require_once "../backend/connection.php";

if (isset($_SESSION['uniqueID'])) {
    $auth_user = $_SESSION['uniqueID'];
    $toClearId = inputValidation($conn, $_POST['toClearId']);

    $query_chats = $conn->query("SELECT * FROM chats WHERE (sender_id = '{$auth_user}' AND receiver_id = '{$toClearId}')OR(sender_id = '{$toClearId}' AND '{$auth_user}') ");

    if ($query_chats->num_rows > 0) {



        while ($row = $query_chats->fetch_assoc()) {
            $type = $row['type'];
            if (str_contains($type, 'img')) {
                $image = 'uploads/' . $row['image'];
                if (file_exists($image)) {
                    unlink($image);
                }
            }
        }

        $clear = $conn->query("DELETE FROM chats WHERE (sender_id = '{$auth_user}' AND receiver_id = '{$toClearId}') OR (sender_id = '{$toClearId}' AND '{$auth_user}') ");

        if ($clear) {
            $reponse = ['status' => 'success', 'message' => "Cleared"];
        } else {
            $reponse = ['status' => 'error', 'message' => "Failed to clear"];
        }
    } else {

        $reponse = ['status' => 'error', 'message' => "No chat found"];
    }


    header('Content-Type: application/json');
    echo json_encode($reponse);
}
