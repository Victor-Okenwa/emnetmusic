<?php
/*-----------------------| |------------------------ 
This is page is for checking blocking
# It will send true if the user is blocked by the receiver
# else send false if the user is not blocked or unblocked
------------------------| |------------------------*/

session_start();
require_once "../backend/connection.php";

if (isset($_SESSION['uniqueID'])) {
    $auth_user = $_SESSION['uniqueID'];
    $blockerId = inputValidation($conn, $_POST['blockerID']);

    $query_blocks = $conn->query("SELECT * FROM blocks WHERE blocked_id = '{$auth_user}' AND blocker_id = '{$blockerId}' ");
    $num_block = $query_blocks->num_rows;

    if ($num_block > 0) {
        echo true;
    } else {
        echo false;
    }

    // $response = ['status' => 'error', 'message' => "User not found to unblock"];

    // header('Content-Type: application/json');
    // echo json_encode($response);
    $conn->close();
}
