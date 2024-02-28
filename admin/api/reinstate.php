<?php
require_once "../assets/access.php";
$reinstateID = $conn->real_escape_string($_POST['reinstate-id']);
$reinstateName = $conn->real_escape_string($_POST['reinstate-name']);
if (!existsWith($reinstateID, $reinstateName, 'artist_id', 'nickname', 'creators')) {
    $response = ['status' => 'error', 'message' => 'Artist not found'];
    outputInJSON($response);
    exit;
}
$update = $conn->query("UPDATE creators SET suspension = 0, period = 0 WHERE artist_id = $reinstateID");
if ($update) {
    $response = ['status' => 'success', 'message' => 'Artist Reinstated'];
} else {
    $response = ['status' => 'error', 'message' => "Update failed"];
}
outputInJSON($response);
