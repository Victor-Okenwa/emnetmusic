<?php
require "../assets/access.php";

$rejectID = inputValidation($conn, $_POST['reject-id']);

if (!empty($rejectID)) {
    if (isInDb($rejectID, 'request_id', 'requests')) {
        $delete_request = $conn->query("DELETE FROM requests WHERE request_id = $rejectID");

        if ($delete_request) {
            $response = ['status' => 'success', 'message' => "Rejected user's request"];
        } else {
            $response = ['status' => 'error', 'message' => "Failed to reject user's request"];
        }
    } else {
        $response = ['status' => 'error', 'message' => "Field not found"];
    }
} else {
    $response = ['status' => 'error', 'message' => "Field empty"];
}

outputInJSON($response);
