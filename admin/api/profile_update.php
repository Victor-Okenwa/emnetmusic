<?php
require_once "../assets/access.php";
$firstname = inputValidation($conn, $_POST['firstname']);
$lastname = inputValidation($conn, $_POST['lastname']);
$nickname = inputValidation($conn, $_POST['nickname']);
$contact = inputValidation($conn, $_POST['contact']);
$profile = trim($_POST['profile']);

if (!empty($firstname) && !empty($lastname)  && !empty($nickname) && !empty($contact)) {
    $filename = '';
    function updateProfile($firstname, $lastname, $nickname, $contact, $filename, $condition)
    {
        global $conn, $admin_id;
        if (!$condition) {
            $update = $conn->query("UPDATE admin SET 
            firstname = '$firstname',
            lastname = '$lastname',
            nickname = '$nickname',
            phone_number = '$contact'
            where id = $admin_id
            ");

            if ($update) {
                return true;
            }
        } else {
            $update = $conn->query("UPDATE admin SET 
            firstname = '$firstname',
            lastname = '$lastname',
            nickname = '$nickname',
            phone_number = '$contact',
            admin_profile = '$filename'
            where id = $admin_id
            ");

            if ($update) {
                return true;
            }
        }
    }

    if (!isInDb($nickname, "nickname", 'users') || !isInDb($nickname, "nickname", 'admin')) {
        if ($profile !== 'data:,') {
            $filename = uniqid() . random_int(100, 9999) . $admin_name . '.' . 'jpg';
            $profile = base64_decode(str_replace('data:image/jpeg;base64,', '', $profile));

            $image_folder = '../admin_profile/';
            if (file_put_contents($image_folder . $filename, $profile)) {
                if (updateProfile($firstname, $lastname, $nickname, $contact, $filename, true)) $response = ['status' => 'success', 'message' => "Profile updated"];
                else $response = ['status' => 'error', 'message' => "Failed to update profile"];
            }
        } else {
            if (updateProfile($firstname, $lastname, $nickname, $contact, $filename, false)) $response = ['status' => 'success', 'message' => "Profile updated"];
            else $response = ['status' => 'error', 'message' => "Failed to update profile"];
        }
    } else {
        $response = ['status' => 'error', 'message' => "The nickname already exists"];
    }
} else {
    $response = ['status' => 'error', 'message' => "Field is allowed empty"];
}

outputInJSON($response);
