<?php

require "./connection.php";
session_start();

if (isset($_SESSION['uniqueID'])) {
    $response;
    $userID = $_SESSION['uniqueID'];
    $query_chats_unread = $conn->query("SELECT read_status FROM chats WHERE receiver_id = '{$userID}' AND read_status = 0");
    if ($query_chats_unread->num_rows > 0)
        $response = ['status' => 'bg-danger', 'count' => $query_chats_unread->num_rows];
    else {
        $response = ['status' => 'bg-success', 'count' => $query_chats_unread->num_rows];
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    return;
}
