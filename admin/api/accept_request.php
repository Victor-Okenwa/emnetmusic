<?php
require "../assets/access.php";
$acceptID = inputValidation($conn, $_POST['accept-id']);

if (!empty($acceptID)) {
    if (isInDb($acceptID, 'request_id', 'requests')) {

        $fetch_records = $conn->query("SELECT * FROM requests WHERE request_id = $acceptID")->fetch_assoc();
        $user_id = $fetch_records['user_id'];
        $user_name = $fetch_records['user_name'];
        $email = $fetch_records['email'];

        $insert_creator = $conn->query("INSERT into creators(user_id, nickname, email) values('$user_id', '$user_name', '$email')");

        if ($insert_creator) {
            $conn->query("DELETE FROM requests WHERE request_id = $acceptID");
            $response = ['status' => 'success', 'message' => "User is now an artist!"];
        } else {
            $response = ['status' => 'error', 'message' => "Failed to accept user"];
        }
    } else {

        $response = ['status' => 'error', 'message' => "Request not found"];
    }
} else {
    $response = ['status' => 'error', 'message' => "Field empty"];
}
outputInJSON($response);
