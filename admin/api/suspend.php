<?php
require_once "../assets/access.php";
$suspendID = $conn->real_escape_string($_POST['suspend-id']);
$suspendDuration = $conn->real_escape_string($_POST['period']);
$response = [];

if (empty($suspendID)) {
    $response = ['status' => 'error', 'message' => 'No user ID provided'];
} else {
    if (isInDb($suspendID, 'artist_id', 'creators')) {
        function updateDb($creator_id, $indefinite, $period)
        {
            global $conn, $response;
            $update = $conn->query("UPDATE creators SET suspension = {$indefinite}, period = {$period} where artist_id = {$creator_id}");

            if ($update) {
                $response = ['status' => 'success', 'message' => "Update successful"];
            } else {
                $response = ['status' => 'error', 'message' => "Update falied"];
            }
            return;
        }

        if (isset($_POST['indefinite'])) {
            $indefinite = 1;
            updateDb($suspendID, $indefinite, 0);
        } else {
            $indefinite = 0;
            if (!empty($suspendDuration)) {
                if (strlen($suspendDuration) >= 4) {
                    $response = ['status' => 'error', 'message' => "Number count cannot be more than 3"];
                } elseif ($suspendDuration < 1 || $suspendDuration >= 720) {
                    $response = ['status' => 'error', 'message' => "Period cannot be less than 1 or more than 720"];
                } else {
                    updateDb($suspendID, 2, $suspendDuration);
                }
            } else {
                $response = ['status' => 'error', 'message' => 'Suspend duration cannot be empty'];
            }
        }
    } else {
        $response = ['status' => 'error', 'message' => 'User Not found'];
    }
}

outputInJSON($response);
