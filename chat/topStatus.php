<?php
session_start();
require "../backend/connection.php";

if (isset($_SESSION['uniqueID'])) {

    $userID = $_SESSION['uniqueID'];
    $receiverID = inputValidation($conn, $_POST['receiverID']);


    $query_sender = checkUserExists($userID);
    $query_receiver = checkUserExists($receiverID);


    $output = "";
    $array_read_status = [];
    $count = 0;
    $is_zero = 0;
    $profile = "";

    if ($query_receiver > 0 && $query_sender > 0) {

        // JOINING THE USERS TABLE WITH THE CHAT ON LEFT ON RECEIVER FOR STATUS UPDATES 
        $query_receiver_id = $conn->query("SELECT * FROM users WHERE user_id = '{$receiverID}'");
        $query_user_chat = $conn->query("SELECT * FROM chats WHERE receiver_id = '{$userID}'");

        $fetch_user_info = $query_receiver_id->fetch_assoc();

        $receiverName = trim($fetch_user_info['nickname']);

        // counting the string gotten from DB [to check if there is a profile or not]
        $count_profile_string = str_word_count(trim($fetch_user_info['user_profile']));
        if ($count_profile_string > 0) $profile = "../userprofiles/" . trim($fetch_user_info['user_profile']);

        $status = strtolower(trim($fetch_user_info['status']));

        if ($status == "active now")  $status = "Online";
        else $status = "Offline";

        if ($query_user_chat->num_rows > 0) {
            while ($fetch_read_user_chat = $query_user_chat->fetch_assoc()) {
                array_push($array_read_status, $fetch_read_user_chat['read_status']);
            }
        }

        // CHECKING IF THERE IS AN UNREAD MESSAGE[0] THEN ADDING THE COUNT TO $is_zero
        for ($count = 0; $count < count($array_read_status); $count++) {
            if ($array_read_status[$count] == '0') {
                $is_zero++;
            }
        }

        if (in_array(0, $array_read_status)) {

            $output .= "
        <div class='details mk-abel'>
            <img src=' " . ($profile == '' ? '../images/profile.png' : $profile) . " ' alt=''>
            <div class='name'>
                <span>" . $receiverName  . "</span>
                <p>" . $status . "</p>
            </div>
        </div>

        <div class='ico'>
            <a href='inbox.php' class='text-light material-icons' title='$is_zero unread messages'>chat</a>
            <small></small>
        </div> ";
        } else {
            $output .= "
        <div class='details mk-abel'>
            <img src=' " . ($profile == '' ? '../images/profile.png' : $profile) . " ' alt=''>
            <div class='name'>
                <span>" . $receiverName  . "</span>
                <p>" . $status . "</p>
            </div>
        </div>

        <div class='ico'>
            <a href='inbox.php'  class='text-light material-icons' title='No unread status'>chat</a>
        </div> ";
        }
    } else {
        echo ("<span class='text-light mk-abel'>Invalid sender or receiver</span>");
    }
    echo $output;
}
