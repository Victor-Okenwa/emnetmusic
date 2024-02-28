<?php

/*  
----------------------------------------------------------------------------------
IN THIS FILE I'M UPDATING THE CHAT PAGE EVERYTIME THE 
CHECK UPDATE SCRIPT SEES AN UNREAD CHAT
----------------------------------------------------------------------------------
*/

session_start();
require "../backend/connection.php";
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$resultsPerPage = isset($_POST['perPage']) ? $_POST['perPage'] : 20;
$offset = ($page - 1) * $resultsPerPage;

$output = "";

if (!isset($_SESSION['uniqiue_id'])) {
    $senderID = inputValidation($conn, $_POST['senderID']);
    $receiverID = inputValidation($conn, $_POST['receiverID']);

    // JOINING THE USERS TABLE WITH THE CHAT ON LEFT ON RECEIVER FOR STATUS UPDATES 
    $query_user_chat = $conn->query("SELECT * FROM chats LEFT JOIN users ON users.user_id = chats.sender_id WHERE (sender_id = '{$senderID}' AND receiver_id = '{$receiverID}') OR (sender_id = '{$receiverID}' AND receiver_id = '{$senderID}') order by chat_id desc LIMIT $offset, $resultsPerPage");
    // Rem LOOk INTO THIS BECAUSE DELETE UPLOAD IS MLFNG

    if ($query_user_chat->num_rows > 0) {

        while ($fetch_chats = $query_user_chat->fetch_assoc()) {
            if ($fetch_chats['visibility'] != 1) {
                // IF THE USER IS THE SENDER
                if ($fetch_chats['sender_id'] == $senderID) {
                    // FOR TEXT ONLY MESSAGE
                    if ($fetch_chats['type'] == "text") {
                        $iv = $fetch_chats['iv'];

                        if (strlen($iv) < 16) {
                            $iv = str_pad($iv, 16, "\0");
                        }
?>

                        <div id="<?= $fetch_chats['chat_id'] ?>" class="chat outgoing">
                            <div class="time"><?= $fetch_chats['date'] ?></div>

                            <div class="msg">

                                <button class="more-out btn text-light no-btn-shadow" id="<?= $fetch_chats['chat_id'] ?>" data-type="<?= $fetch_chats['type'] ?>" onclick="moreForOut(this)"><i class="material-icons">more_vert</i>
                                </button>

                                <div class="msg-cont">

                                    <p class="msg-txt"> <?= openssl_decrypt($fetch_chats['message'], CIPHER_METHOD, HASH_KEY, 0, $iv) ?> </p>
                                    <?= $fetch_chats['liked'] == 1 ? '<small class="fa fa-heart like-alert"></small>' : "";
                                    ?>
                                </div>
                            </div>

                        </div>

                    <?php
                        // FOR IMAGE ONLY MESSAGE
                    } elseif ($fetch_chats['type'] == "img") {

                    ?>

                        <div id="<?= $fetch_chats['chat_id'] ?>" class="chat outgoing">
                            <div class="time"><?= $fetch_chats['date'] ?></div>

                            <div class="msg">

                                <button class="more-out btn text-light no-btn-shadow" id="<?= $fetch_chats['chat_id'] ?>" data-type="<?= $fetch_chats['type'] ?>" onclick="moreForOut(this)"><i class="material-icons">more_vert</i>
                                </button>

                                <div class="msg-cont">

                                    <div class="msg-img">
                                        <img src="uploads/<?= $fetch_chats['image'] ?>" class="msg-img-display" alt="">
                                    </div>

                                    <?= $fetch_chats['liked'] == 1 ? '<small class="fa fa-heart like-alert"></small>' : "";
                                    ?>
                                </div>

                            </div>

                        </div>

                    <?php
                        // IF IT IS BOTH TEXT AND IMAGE
                    } else {
                    ?>
                        <div id="<?= $fetch_chats['chat_id'] ?>" class="chat outgoing">
                            <div class="time"><?= $fetch_chats['date'] ?></div>

                            <div class="msg">

                                <button class="more-out btn text-light no-btn-shadow" id="<?= $fetch_chats['chat_id'] ?>" data-type="<?= $fetch_chats['type'] ?>" onclick="moreForOut(this)"><i class="material-icons">more_vert</i>
                                </button>


                                <div class="msg-cont">

                                    <div class="msg-img">
                                        <img src="uploads/<?= $fetch_chats['image'] ?>" class="msg-img-display" alt="">
                                    </div>

                                    <p class="msg-txt"><?= openssl_decrypt($fetch_chats['message'], CIPHER_METHOD, HASH_KEY, 0, $fetch_chats['iv']); ?> </p>

                                    <?= $fetch_chats['liked'] == 1 ? '<small class="fa fa-heart like-alert"></small>' : "";
                                    ?>
                                </div>
                            </div>

                        </div>
                    <?php
                    }

                    // IF THE USER IS THE RECEIVER
                } else {
                    $sender_profile = trim($fetch_chats['user_profile']);
                    $iv = $fetch_chats['iv'];
                    if (strlen($iv) < 16) {
                        $iv = str_pad($iv, 16, "\0");
                    }
                    if ($fetch_chats['type'] == "text") {
                    ?>
                        <div id="<?= $fetch_chats['chat_id'] ?>" class="chat incoming">
                            <div class="time"><?= $fetch_chats['date'] ?></div>

                            <div class="full-info">

                                <div class="msg">

                                    <img src="<?= $sender_profile == '' ? '../images/profile.png' : "../userprofiles/{$sender_profile}" ?>" alt="">
                                    <div class="msg-cont">
                                        <p class="msg-txt"><?= openssl_decrypt($fetch_chats['message'], CIPHER_METHOD, HASH_KEY, 0, $fetch_chats['iv']); ?> </p>
                                    </div>
                                </div>

                                <div class="side-opts d-flex flex-column justify-content-between py-4">
                                    <i class="btn like fa fa-heart <?= $fetch_chats['liked'] == 1 ? "liked" : ""  ?>" onclick="likeMsg(this)"></i>
                                    <i class="more-in material-icons btn text-light" id="<?= $fetch_chats['chat_id'] ?>" onclick="moreForIn(this)" data-type="<?= trim($fetch_chats['type']) ?>">more_vert</i>
                                </div>
                            </div>

                        </div>
                    <?php
                    } elseif ($fetch_chats['type'] == "img") {
                    ?>
                        <div id="<?= $fetch_chats['chat_id'] ?>" class="chat incoming">
                            <div class="time"><?= $fetch_chats['date'] ?></div>

                            <div class="full-info">

                                <div class="msg">

                                    <img src="<?= $sender_profile == '' ? '../images/profile.png' : "../userprofiles/{$sender_profile}" ?>" alt="">

                                    <div class="msg-cont">
                                        <div class="msg-img">
                                            <img src="uploads/<?= $fetch_chats['image'] ?>" class="msg-img-display" alt="">
                                        </div>
                                    </div>

                                </div>

                                <div class="side-opts d-flex flex-column justify-content-between py-4">
                                    <i class="btn like fa fa-heart" onclick="likeMsg(this)"></i>
                                    <i class="more-in material-icons btn text-light" id="<?= $fetch_chats['chat_id'] ?>" onclick="moreForIn(this)" data-type="<?= trim($fetch_chats['type']) ?>">more_vert</i>
                                </div>
                            </div>

                        </div>
                    <?php
                    } else {
                    ?>
                        <div id="<?= $fetch_chats['chat_id'] ?>" class="chat incoming">
                            <div class="time"><?= $fetch_chats['date'] ?></div>

                            <div class="full-info">

                                <div class="msg">

                                    <img src="<?= $sender_profile == '' ? '../images/profile.png' : "../userprofiles/{$sender_profile}" ?>" alt="">

                                    <div class="msg-cont">
                                        <div class="msg-img">
                                            <img src="uploads/<?= $fetch_chats['image'] ?>" class="msg-img-display" alt="">
                                        </div>

                                        <p class="msg-txt"><?= openssl_decrypt($fetch_chats['message'], CIPHER_METHOD, HASH_KEY, 0, $fetch_chats['iv']); ?> </p>

                                    </div>

                                </div>

                                <div class="side-opts d-flex flex-column justify-content-between py-4">
                                    <i class="btn like fa fa-heart" onclick="likeMsg(this)"></i>
                                    <i class="more-in material-icons btn text-light" id="<?= $fetch_chats['chat_id'] ?>" onclick="moreForIn(this)" data-type="<?= trim($fetch_chats['type']) ?>">more_vert</i>
                                </div>
                            </div>

                        </div>
        <?php
                    }
                }
            }
        }
    } else {
        ?>

        <p class="no-chat mk-aladin text-center bg-black shadow shadow-lg rotate180">! Your chat is protected and
            encrypted !</p>
    <?php
        return 0;
    }
    $conn->close();

    ?>

<?php

} else {
    die("<p class='alert alert-danger text-center'>! Session not found !</p>");
}

?>