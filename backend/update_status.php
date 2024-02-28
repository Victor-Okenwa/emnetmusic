<?php
session_start();
require_once 'connection.php';
if (isset($_SESSION['uniqueID'])) {
    $auth_user = $_SESSION['uniqueID'];
    $status = inputValidation($conn, $_POST['status']);

    $update = $conn->query("UPDATE users SET status = '{$status}' WHERE user_id = '{$auth_user}' ");

    if ($update) {
        $response = ['status' => 'success', 'message' => "Updated"];
    } else {
        $response = ['status' => 'error', 'message' => "Failed update"];
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}
