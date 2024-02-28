<?php
session_start();
require "../backend/connection.php";

if (isset($_SESSION['uniqueID'])) {
    $requestType = $_POST['requestType'];
    $user_id = $_SESSION['uniqueID'];

    if ($requestType == 'sender') {
        $receiverID = inputValidation($conn, $_POST['receiverID']);
        $lastMessageID = inputValidation($conn, $_POST['lastMessageID']);

        $query_new_chats;
        // to update chats we have to select all new records that have not being shown
        if (!empty($lastMessageID)) {
            $query_new_chats = $conn->query("SELECT * FROM chats LEFT JOIN users ON users.user_id = chats.sender_id WHERE chat_id > '{$lastMessageID}' AND sender_id = '{$user_id}' AND receiver_id = '{$receiverID}'");
        } else {
            $query_new_chats = $conn->query("SELECT * FROM chats LEFT JOIN users ON users.user_id = chats.sender_id WHERE sender_id = '{$user_id}' AND receiver_id = '{$receiverID}'");
        }

        if ($query_new_chats->num_rows > 0) {
            while ($fetch_chats = $query_new_chats->fetch_assoc()) {
                // IF THE USER IS THE SENDER

                if ($fetch_chats['sender_id'] == $user_id) {
                    $iv = $fetch_chats['iv'];
                    if (strlen($iv) < 16) {
                        $iv = str_pad($iv, 16, "\0");
                    }
                    // FOR TEXT ONLY MESSAGE
                    if ($fetch_chats['type'] == "text") {

?>
                        <div id="<?= $fetch_chats['chat_id'] ?>" class="chat outgoing">
                            <div class="time"><?= $fetch_chats['date'] ?></div>

                            <div class="msg">

                                <button class="more-out btn text-light no-btn-shadow" id="<?= $fetch_chats['chat_id'] ?>" data-type="<?= $fetch_chats['type'] ?>" onclick="moreForOut(this)"><i class="material-icons">more_vert</i>
                                </button>

                                <div class="msg-cont">

                                    <p class="msg-txt"> <?= openssl_decrypt($fetch_chats['message'], CIPHER_METHOD, HASH_KEY, 0, $iv); ?> </p>
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

                                    <p class="msg-txt"><?= openssl_decrypt($fetch_chats['message'], CIPHER_METHOD, HASH_KEY, 0, $iv); ?> </p>

                                    <?= $fetch_chats['liked'] == 1 ? '<small class="fa fa-heart like-alert"></small>' : "";
                                    ?>
                                </div>
                            </div>

                        </div>
                    <?php
                    }
                }
            }
        }
    } else {
        $receiverID = inputValidation($conn, $_POST['receiverID']);
        $lastMessageID = inputValidation($conn, $_POST['lastMessageID']);

        // to update chats we have to select all new records that have not being shown
        $query_new_chats = $conn->query("SELECT * FROM chats LEFT JOIN users ON users.user_id = chats.sender_id WHERE chat_id > '{$lastMessageID}' AND sender_id = '{$receiverID}' AND receiver_id = '{$user_id}'");

        if ($query_new_chats->num_rows > 0) {
            while ($fetch_chats = $query_new_chats->fetch_assoc()) {
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
                                    <p class="msg-txt"><?= openssl_decrypt($fetch_chats['message'], CIPHER_METHOD, HASH_KEY, 0, $iv); ?> </p>
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

                                    <p class="msg-txt"><?= openssl_decrypt($fetch_chats['message'], CIPHER_METHOD, HASH_KEY, 0, $iv); ?> </p>

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
    exit();
}
