<?php
session_start();
require_once "../backend/connection.php";

if (isset($_SESSION['uniqueID'])) {
    $auth_user = $_SESSION['uniqueID'];
    $lastInboxId = inputValidation($conn, $_POST['lastInboxID']);
    $lastSender = inputValidation($conn, $_POST['lastSender']);
    $existingIds =  $_POST['existingIds'];
    $html = "";
    $i;
    $output = [];



    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $resultsPerPage = isset($_POST['perPage']) ? $_POST['perPage'] : 1;
    $offset = ($page - 1) * $resultsPerPage;

    $query_new_chat = $conn->query("SELECT * FROM chats WHERE chat_id > {$lastInboxId} AND receiver_id = '{$auth_user}'");

    if ($query_new_chat->num_rows > 0) {

        while ($row_new_chat = $query_new_chat->fetch_assoc()) {
            $new_sender_id = $row_new_chat['sender_id'];

            $query_this_user = $conn->query("SELECT * FROM users Where user_id = '{$new_sender_id}'");
            $row_user = $query_this_user->fetch_assoc();

            $chat_id = $row_new_chat['chat_id'];
            $sender_name = $row_user['nickname'];

            $userprofile = $row_user['user_profile'];
            $userstatus = $row_user['status'];

            $query_read_status = $conn->query("SELECT read_status FROM chats WHERE receiver_id = '{$auth_user}' AND read_status = 0 AND sender_id = '{$new_sender_id}'");

            $unread_count = $query_read_status->num_rows;

            $unread_count > 0 ? $display = '' : $display = 'd-none';

            !empty($userprofile) ? $userprofile = "../userprofiles/{$userprofile}" : $userprofile = "../images/profile.png";
            $userstatus == 'offline' ? $userstatus = 'offline' : $userstatus = '';

            $message = openssl_decrypt($row_new_chat['message'], CIPHER_METHOD, HASH_KEY, 0, $row_new_chat['iv']);


            ((strlen($sender_name) > 28) ? $new_name = substr($sender_name, 0, 28) . '...' :  $new_name = $sender_name);
            ((strlen($message) > 28) ? $new_msg = substr($message, 0, 28) . '...' :  $new_msg = $message);

            if (in_array($new_sender_id, $existingIds)) {

                $html = "
                <div class='box' id='$new_sender_id' data-msg-id='$chat_id' data-sender-id='$new_sender_id'>

                    <a href='chat.php?receiverID=$new_sender_id' class='message'>
                        <div class='inbox-msg'>
                            <img src='$userprofile' alt=''>

                            <i class='fa fa-circle unread $display' title='$unread_count message(s)'></i>

                            <div class='user-info'>
                                <span class='text-capitalize'>$new_name</span>
                                <p style='font-style: italic;'>$new_msg</p>
                            </div>
                        </div>
                        <div class='status $userstatus'>
                            <i class='fa fa-circle'></i>
                        </div>
                    </a>

                    <div class='dropdown'>
                        <a class='mk-flex btn text-light' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                            <i class='material-icons'>more_vert</i>
                        </a>
                        <div class='dropdown-menu dropdown-menu-left animated--grow-in'>
                            <a class='dropdown-item text-light btn block-btn' id='$new_sender_id'>
                                Block User
                            </a>
                            <div class='dropdown-divider'></div>
                            <a class='dropdown-item text-light' id='$new_sender_id'>
                                Clear Chat
                            </a>
                        </div>
                    </div>
                </div>
            ";
                $output = ['type' => 2, 'value' => $html, 'sender_id' => $new_sender_id];
            } else {
                $html =
                    "
                    <div class='box' id='$new_sender_id' data-msg-id='$chat_id' data-sender-id='$new_sender_id'>

                        <a href='chat.php?receiverID=$new_sender_id' class='message'>
                            <div class='inbox-msg'>
                                <img src='$userprofile' alt=''>

                                <i class='fa fa-circle unread $display' title='$unread_count message(s)'></i>

                                <div class='user-info'>
                                    <span class='text-capitalize'>$new_name</span>
                                    <p style='font-style: italic;'>$new_msg</p>
                                </div>
                            </div>
                            <div class='status $userstatus'>
                                <i class='fa fa-circle'></i>
                            </div>
                        </a>

                        <div class='dropdown'>
                            <a class='mk-flex btn text-light' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                <i class='material-icons'>more_vert</i>
                            </a>
                            <div class='dropdown-menu dropdown-menu-left animated--grow-in'>
                                <a class='dropdown-item text-light btn block-btn' id='$new_sender_id'>
                                    Block User
                                </a>
                                <div class='dropdown-divider'></div>
                                <a class='dropdown-item text-light' id='$new_sender_id'>
                                    Clear Chat
                                </a>
                            </div>
                        </div>
                    </div>
                 ";
                $output = ['type' => 1, 'value' => $html];
            }
        }
    }

    echo json_encode($output);
} else {
    exit();
}
