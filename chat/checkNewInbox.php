<?php
session_start();
require_once "../backend/connection.php";

if (isset($_SESSION['uniqueID'])) {
    $auth_user = $_SESSION['uniqueID'];
    $lastInboxId = inputValidation($conn, $_POST['lastInboxID']);

    $query_new_chat = $conn->query("SELECT * FROM chats WHERE chat_id > {$lastInboxId} AND receiver_id = '{$auth_user}' ");

    if ($query_new_chat->num_rows > 0) {
        echo 1;
    } else {
        echo 0;
    }
}
