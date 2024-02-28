<?php
session_start();
require "../backend/connection.php";

if (isset($_SESSION['uniqueID'])) {
    $auth_user = $_SESSION['uniqueID'];
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $resultsPerPage = isset($_GET['perPage']) ? $_GET['perPage'] : 20;
    $offset = ($page - 1) * $resultsPerPage;

    // Query for chats databse to check if there is any chat id that the user is involved
    $query_all_chats = $conn->query("SELECT chat_id FROM chats WHERE (receiver_id = '{$auth_user}') OR (sender_id = '{$auth_user}') ORDER by sent_on DESC");


    if ($query_all_chats->num_rows > 0) {
        // Query for users table to check all user_id that are not the current user
        $query_users = $conn->query("SELECT user_id,nickname FROM users LEFT JOIN chats on (chats.sender_id or chats.receiver_id) = users.user_id WHERE NOT user_id = '{$auth_user}' LIMIT $offset, $resultsPerPage");

        if ($query_users->num_rows) {

            while ($row_users_id = $query_users->fetch_assoc()) {
                $query_blocked = $conn->query("SELECT block_id from blocks where blocked_id = '{$row_users_id['user_id']}' ");

                if ($query_blocked->num_rows < 1) {
                    // Query for selecting chats where the sender and the currrent user is involved
                    $query_user_chat = $conn->query("SELECT * FROM chats WHERE (receiver_id = '{$auth_user}' AND sender_id = '{$row_users_id['user_id']}') OR (receiver_id = '{$row_users_id['user_id']}' AND sender_id = '{$auth_user}') ORDER BY chat_id DESC");

                    $message = null;

                    if ($query_user_chat->num_rows > 0) {
                        $senderID = $row_users_id['user_id'];
                        // Query to select the sender's user fields
                        $query_sender = $conn->query("SELECT * FROM users WHERE user_id = '{$senderID}' ");

                        $row_sender = $query_sender->fetch_assoc();
                        $userprofile = $row_sender['user_profile'];

                        $username = $row_users_id['nickname'];
                        $userstatus = strtolower($row_sender['status']);

                        $row_chat = $query_user_chat->fetch_assoc();
                        if ($userstatus == "active now")  $userstatus = "online";
                        else $userstatus = "offline";
                        // query to check if there is any unread message
                        $query_read_status = $conn->query("SELECT read_status FROM chats WHERE receiver_id = '{$auth_user}' AND read_status = 0 AND sender_id = '{$senderID}'");

                        $unread_count = $query_read_status->num_rows;

                        $messageType = $row_chat['type'];
                        if ($messageType == "img") {
                            $lastmessage = "Image";
                        } else {
                            $lastmessage = openssl_decrypt($row_chat['message'], CIPHER_METHOD, HASH_KEY, 0, $row_chat['iv']);
                        }

                        if ($query_user_chat->num_rows < 1) {
                            $message = 'No chat available';
                        } else {
                            if ($row_chat['sender_id'] == $auth_user) {
                                $message =  'You: ' . $lastmessage;
                            } else {
                                $message = $lastmessage;
                            }
                        }
                        // ((strlen($message) > 28) ? $new_msg = substr($message, 0, 28) . '...' :  $new_msg = $message);

?>

                        <div class="box" id="<?= $row_chat['chat_id'] ?>" data-msg-id="<?= $row_chat['chat_id'] ?>" data-sender-id="<?= $row_chat['sender_id'] ?>">
                            <a href="chat.php?receiverID=<?= $senderID ?>" class="message">
                                <div class="inbox-msg">
                                    <img src="<?= !empty($userprofile) ? "../userprofiles/{$userprofile}" : "../images/profile.png" ?>" alt="">

                                    <i class="fa fa-circle unread <?= $unread_count > 0 ? '' : 'd-none' ?>" title="<?= $unread_count ?> message(s)"></i>



                                    <div class="user-info">
                                        <span class="text-capitalize"><?= shortenTextArtist($username) ?></span>
                                        <p style="font-style: italic;"><?= shortenTextArtist($message) ?></p>
                                    </div>
                                </div>
                                <div class="status <?= $userstatus == 'offline' ? 'offline' : '' ?>">
                                    <i class="fa fa-circle"></i>
                                </div>
                            </a>

                            <div class="dropdown">
                                <a class="mk-flex btn text-light" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item text-light btn block-btn" data-user-id="<?= $senderID ?>" onclick="callBlock(this)">
                                        Block User
                                    </a>
                                    <li class="dropdown-divider" style="border-top: 1px solid #494949c5 !important"></li>
                                    <a class="dropdown-item text-light" id="<?= $senderID ?>" onclick="clearChat(this)">
                                        Clear Chat
                                    </a>
                                </div>
                            </div>
                        </div>

            <?php
                    }
                }
            }
        } else {
            ?>
            <p class="no-01 no-more text-center" style="font-size:larger; margin-bottom: 200px">No More inbox</p>
        <?php

        }
    } else {
        ?>
        <p class="no-01 text-center" style="font-size:larger">No chat available</p>
<?php

    }
} else {
    exit();
}
