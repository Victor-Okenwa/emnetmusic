<?php
require "../assets/access.php";
if ($data_admin['admin_rank'] !== "super admin") {
    $response = ['status' => 'error', 'message' => 'Not a known admin'];
} else {
    $fieldType = inputValidation($conn, $_POST['type']);
    $value = inputValidation($conn, $_POST['value']);
    function updateField($field, $value)
    {
        global $conn;

        $update = $conn->query("UPDATE setting SET $field = '{$value}'");
        if ($update) return true;
        else return false;
    }

    if ($fieldType == 'creator_suspend') {
        if (updateField('creator_sus', $value)) {

            $response = ['status' => 'success', 'message' => ''];
        } else {
            $response = ['status' => 'error', 'message' => "$fieldType update failed"];
        }
    } elseif ($fieldType == 'creator_delete') {
        if (updateField('creator_delete', $value)) {

            $response = ['status' => 'success', 'message' => ''];
        } else {
            $response = ['status' => 'error', 'message' => "$fieldType update failed"];
        }
    } elseif ($fieldType == 'creator_accept') {
        if (updateField('request_accpt', $value)) {

            $response = ['status' => 'success', 'message' => ''];
        } else {
            $response = ['status' => 'error', 'message' => "$fieldType update failed"];
        }
    } elseif ($fieldType == 'creator_reject') {
        if (updateField('request_rej', $value)) {

            $response = ['status' => 'success', 'message' => ''];
        } else {
            $response = ['status' => 'error', 'message' => "$fieldType update failed"];
        }
    } elseif ($fieldType == 'creator_wait') {
        if (updateField('request_wait', $value)) {

            $response = ['status' => 'success', 'message' => ''];
        } else {
            $response = ['status' => 'error', 'message' => "$fieldType update failed"];
        }
    } elseif ($fieldType == 'create_audio') {
        if (updateField('crt_audio', $value)) {

            $response = ['status' => 'success', 'message' => ''];
        } else {
            $response = ['status' => 'error', 'message' => "$fieldType update failed"];
        }
    } elseif ($fieldType == 'creator_reinstate') {
        if (updateField('creator_reinstate', $value)) {

            $response = ['status' => 'success', 'message' => ''];
        } else {
            $response = ['status' => 'error', 'message' => "$fieldType update failed"];
        }
    } elseif ($fieldType == 'auto_accept') {
        if (updateField('auto_acct', $value)) {

            $response = ['status' => 'success', 'message' => ''];
        } else {
            $response = ['status' => 'error', 'message' => "$fieldType update failed"];
        }
    } else {
        $response = ['status' => 'error', 'message' => "Field not found"];
    }
}
outputInJSON($response);
