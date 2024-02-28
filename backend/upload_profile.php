<?php
# *********** PAGE INFORMATION *****************#
#-- here we are updating user profile wether new or old
session_start();
require_once "connection.php";

if (!isset($_SESSION["uniqueID"])) {
    exit;
} else {
    $user_id = $_SESSION['uniqueID'];
    $userName = $_SESSION['nickname'];

    if (isset($_POST['imgfile'])) {
        function generateFilename($extension)
        {
            global $userName;
            $filename = uniqid() . random_int(100, 9999);
            return $filename . $userName . '.' . $extension;
        }

        $new_profile =  $_POST['imgfile'];
        $new_profile = str_replace('data:image/jpeg;base64,', '', $new_profile);
        $new_profile = base64_decode($new_profile);
        $filename = generateFilename('jpg');

        $queryUserProfile = $conn->query("SELECT * FROM users WHERE user_id = '{$user_id}' ");
        $fetchProfile = $queryUserProfile->fetch_assoc();
        $current_image = trim($fetchProfile['user_profile']);
        $folder = "../userprofiles/";

        if ($current_image !== "") {
            if (file_exists("{$folder}{$current_image}")) {
                unlink("{$folder}{$current_image}");
            }
        }


        $update_profile =  $conn->query("UPDATE users SET
                user_profile = '{$filename}'
                 WHERE user_id = '{$user_id}' ");

        if ($update_profile) {
            $success = file_put_contents($folder . $filename, $new_profile);
            echo "success";
        }
    } elseif (isset($_POST['details'])) {
        $firstname = inputValidation($conn, $_POST['firstname']);
        $surname = inputValidation($conn, $_POST['surname']);
        $othername = inputValidation($conn, $_POST['othername']);
        $contact = inputValidation($conn, $_POST['contact']);
        $country = inputValidation($conn, $_POST['country']);
        $gender = inputValidation($conn, $_POST['gender']);

        $update_details = $conn->query("UPDATE users SET 
        firstname = '{$firstname}',
        surname = '{$surname}',
        othername = '{$othername}',
        phone_number = '{$contact}',
        country = '{$country}',
        gender = '{$gender}'
        where user_id = '{$user_id}'
        ");


        if ($update_details) {
            echo 'success';
            return;
        } else {
            echo 'Update failed';
            return;
        }
    } else {

        $recordLabel = inputValidation($conn, $_POST['recordLabel']);

        $update_creator = $conn->query("UPDATE creators SET record_label = '{$recordLabel}' WHERE user_id = '{$user_id}'");

        if ($update_creator) {
            echo 'success';
        } else {
            echo 'Failed update';
        }
    }
}
