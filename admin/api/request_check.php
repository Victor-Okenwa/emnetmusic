<?php
require "../assets/access.php";
if (isset($_POST['select_field'])) {
    $selectID = inputValidation($conn, $_POST['select_id']);
    $state = inputValidation($conn, $_POST['state']);

    if (isInDb($selectID, 'request_id', 'requests')) {
        $update = $conn->query("UPDATE requests SET selector = $state WHERE request_id = $selectID");

        if ($update) {
            $response = ['status' => 'success', 'message' => "Selected"];
        } else {
            $response = ['status' => 'error', 'message' => "failed"];
        }
    }
} elseif (isset($_POST['multiple_requests'])) {

    if (isInDb(1, 'selector', 'requests')) {
        if ($_POST['multiple_requests'] == 'accept') {
            $query_requests = $conn->query("SELECT * FROM requests WHERE selector = 1");
            $field_count = $query_requests->num_rows;
            $count = 0;
            if ($field_count > 0) {
                do {
                    $row = $query_requests->fetch_assoc();
                    $user_id = $row['user_id'];
                    $user_name = $row['user_name'];
                    $email = $row['email'];

                    $insert_creator = $conn->query("INSERT into creators(user_id, nickname, email) values('{$user_id}', '{$user_name}', '{$email}')");
                    $conn->query("DELETE FROM requests WHERE user_id = '$user_id'");
                    $count++;
                } while ($count < $field_count);
                if ($insert_creator) {
                    $response = ['status' => 'success', 'message' => "Accepted selected requests"];
                } else {
                    $response = ['status' => 'error', 'message' => "Failed to accept selected requests"];
                }
            } else {
                $response = ['status' => 'error', 'message' => "The request you sent does not exist"];
            }
        } else {
            $delete_requets = $conn->query("DELETE FROM requests WHERE selector = 1");

            if ($delete_requets) {
                $response = ['status' => 'success', 'message' => "Rejected selected requests"];
            } else {
                $response = ['status' => 'error', 'message' => "Failed to reject selected requests"];
            }
        }
    } else {
        $response = ['status' => 'error', 'message' => "No field found with requirements"];
    }
} else {
    $response = ['status' => 'error', 'message' => "No request found"];
}
outputInJSON($response);
