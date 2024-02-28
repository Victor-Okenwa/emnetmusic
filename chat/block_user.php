<?php

/*-----------------------| |------------------------ 
This is page is for blocking and unblocking
------------------------| |------------------------*/
session_start();
require_once "../backend/connection.php";

if (isset($_SESSION['uniqueID'])) {
    $auth_user = $_SESSION['uniqueID'];
    $query_auth_user = $conn->query("SELECT nickname from users where user_id = '{$auth_user}'");
    $auth_name = $query_auth_user->fetch_assoc()['nickname'];

    // Block user code block
    if (isset($_POST['toBlockID'])) {
        $ToBlockId = inputValidation($conn, $_POST['toBlockID']);

        $query_block_id = $conn->query("SELECT nickname from users where user_id = '{$ToBlockId}'");
        $block_name = $query_block_id->fetch_assoc()['nickname'];

        if ($query_block_id->num_rows > 0) {
            $block = $conn->query("INSERT INTO BLOCKS (BLOCKER_ID, BLOCKED_ID) VALUES('{$auth_user}', '{$ToBlockId}')");

            if ($block) {
                $response = ['status' => 'success', 'message' => "Blocked $block_name"];
            } else {
                $response = ['status' => 'error', 'message' => "Block request on $block_name failed"];
            }
        } else {
            $response = ['status' => 'error', 'message' => "User not found to block"];
        }
    }
    // Unblock user code block
    elseif (isset($_POST['unblockID'])) {
        $ToUnblockID = inputValidation($conn, $_POST['unblockID']);
        $response = ['status' => 'error', 'message' => $ToUnblockID . $auth_name];

        $query_blocked_id = $conn->query("SELECT nickname from users where user_id = '{$ToUnblockID}'");
        $blocked_name = $query_blocked_id->fetch_assoc()['nickname'];

        if ($query_blocked_id->num_rows > 0) {

            $delete_block = $conn->query("DELETE FROM BLOCKS WHERE BLOCKER_ID = '{$auth_user}' AND BLOCKED_ID = '{$ToUnblockID}' ");

            if ($delete_block) {
                $response = ['status' => 'success', 'message' => "Unblocked $blocked_name"];
            } else {

                $response = ['status' => 'error', 'message' => "Failed to unblock $blocked_name"];
            }
        } else {
            $response = ['status' => 'error', 'message' => "User not found to unblock"];
        }
    } else {
        die;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    exit;
}
