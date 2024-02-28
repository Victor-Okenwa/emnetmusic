<?php
require "../assets/access.php";

if ($admin_rank == 'super admin') {
    $type = inputValidation($conn, $_POST['type']);
    $block_id = inputValidation($conn, $_POST['admin_id']);

    function updateAdmin($value)
    {
        global $conn, $block_id;
        if (isInDb($block_id, 'id', 'admin')) {
            $update = $conn->query("UPDATE admin set block_tag = $value where id = $block_id");
            if ($update) return true;
            else return false;
        } else {
            return false;
        }
    }

    if ($type == 'block') {
        if (updateAdmin(1)) {
            $response = ['status' => 'success', 'message' => "Admin blocked"];
        } else {
            $response = ['status' => 'error', 'message' => "Request failed or not found"];
        }
    } elseif ($type == 'unblock') {
        if (updateAdmin(0)) {
            $response = ['status' => 'success', 'message' => "Admin unblocked"];
        } else {
            $response = ['status' => 'error', 'message' => "Request failed or not found"];
        }
    } else {
        $response = ['status' => 'error', 'message' => "Response not found"];
    }
} else {
    $response = ['status' => 'error', 'message' => "Request blocked"];
}
outputInJSON($response);
