<?php

# *********** PAGE INFORMATION *****************#
#-- here we are updating user profile wether new or old
session_start();
require_once "connection.php";

$response = array('status' => 'error', 'message' => "Failed to update password, try again");

if (!isset($_SESSION["uniqueID"]) && !isset($user) && !isset($imageURL)) {
    die();
} else {

    $userId = $_SESSION['uniqueID'];
    $imageURL = mysqli_real_escape_string($conn, $_POST['profileDeletePrevURL']);

    $query_user_profile = $conn->query("SELECT user_profile FROM users WHERE user_id = '{$userId}'");

    if ($query_user_profile->num_rows > 0) {
        if (file_exists("../userprofiles/{$imageURL}")) {
            if (unlink("../userprofiles/{$imageURL}")) {
                $null = "";
                $update_profile = $conn->query("UPDATE users SET user_profile = '{$null}' where user_id = '{$userId}'");
                if ($update_profile) {
                    $response = array('status' => 'success', 'message' => "");
                }
            } else {
                $response = array('status' => 'error', 'message' => "Failed to delete profile");
            }
        } else {
            $response = array('status' => 'error', 'message' => "File Not found");
        }
    } else {
        $response = array('status' => 'error', 'message' => "User not found");
    }
}
header('Content-Type: application/json');
echo json_encode($response);
exit;
