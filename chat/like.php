<?php
session_start();
require "../backend/connection.php";

$likeStatus = inputValidation($conn, $_POST['likeStatus']);
$messageID = inputValidation($conn, $_POST['messageID']);

// QUERY CHAT TABLE
$query_chat = $conn->query("SELECT liked FROM chats WHERE chat_id = {$messageID} ");

$fetch_liked = $query_chat->fetch_assoc();
if ($likeStatus == 0) {
    $update_like = $conn->query("UPDATE chats SET liked = {$likeStatus} WHERE chat_id = {$messageID}");
} else {
    $update_like = $conn->query("UPDATE chats SET liked =  {$likeStatus} WHERE chat_id = {$messageID}");
}

echo $fetch_liked['liked'];
