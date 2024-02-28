<?php
session_start();
require("../backend/connection.php");


if (isset($_SESSION["uniqueID"])) {
    $unique_id = $_SESSION['uniqueID'];

    $query_user = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '{$_SESSION["uniqueID"]}' ");

    if (mysqli_num_rows($query_user) > 0) {
        $fetch_user = mysqli_fetch_assoc($query_user);
        $userid = $fetch_user['user_id'];
        $email = $fetch_user['email'];
        $nickname = $fetch_user['nickname'];
        $_SESSION['nickname'] = $fetch_user['nickname'];
        $img = $fetch_user['user_profile'];
    }
} else {
    header("Location: ../index.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=yes">
    <link rel="shortcut icon" sizes="392x6592" href="../logos/Emnet_Logo2.png" type="image/x-icon">
    <title>Emnet chat</title>
    <link rel="stylesheet" href="../css/all.css">
    <link rel="stylesheet" href="../css/boot4.css">
    <link rel="stylesheet" href="../css/fonts.css">
    <link rel="stylesheet" href="../iconfont/material-icons.css">
    <link rel="stylesheet" href="chat.css">

    <script src="../js/jquery3.6.0.js"></script>
    <script src="../js/bootstrap4.js"></script>
</head>

<body>
    <div class="wrapper">

        <?php
        $receiverID = inputValidation($conn, $_GET['receiverID']);
        $query_exists_receiver = $conn->query("SELECT * FROM users WHERE user_id = '{$receiverID}'");
        $query_artist_receiver = $conn->query("SELECT user_id FROM creators where user_id = '{$receiverID}'");
        $query_blocks = $conn->query("SELECT * FROM blocks WHERE blocked_id = '{$unique_id}' AND blocker_id = '{$receiverID}' ");
        $num_block = $query_blocks->num_rows;
        $num_creator = $query_artist_receiver->num_rows;

        if (isset($receiverID) && $query_exists_receiver->num_rows > 0) {
            if ($_SESSION['uniqueID'] == $receiverID) {
                echo '<p class="no-chat mk-aladin text-center">Cannot message Me </p>';
            } else {
        ?>

                <?php
                include_once "../connectivity.php"
                ?>

                <section class="chat-area">
                    <!-- 
                    <div class="view-msg-image animated animated--grow-in">
                        <img src="" id="msg-img-to-display" alt="Display-Image">
                        <div class="options d-flex align-items-center justify-content-center">
                            <a id="image-src" class="btn text-light text-center" href="" download><i class="fa fa-download"></i><span class="ml-2">Download</span></a>
                        </div>
                    </div> -->

                    <div class="full-options">
                        <div class="options">
                            <button type="button" id="copy_msg" title="Copy" class="btn"><i class="fa fa-link"></i></button>
                            <!-- <button type="button" id="edit_msg" title="Edit" class="btn" data-toggle="modal" data-target="#EditModal"><i class="fa fa-edit"></i></button> -->
                            <button type="button" id="delete_msg" title="Delete" class="btn"><i class="fa fa-trash"></i></button>


                            <div class="" id="em-htm"></div>
                            <div class="" id="em-type"></div>
                        </div>
                    </div>

                    <div data-msg-id="" class="full-options-in">
                        <div class="options">
                            <button type="button" id="copy_msg_in" title="Copy" class="btn"><i class="fa fa-link"></i></button>

                            <button type="button" id="delete_msg_in" title="Delete" class="btn"><i class="fa fa-trash"></i></button>


                            <div class="" id="em-htm-in"></div>
                            <div class="" id="em-type-in"></div>
                        </div>
                    </div>

                    <header>
                        <a href="<?= $num_creator > 0 ? "../artist_page.php/$receiverID" : '/' ?>" class="back-icon" title="Back to artist profile"><i class="fa fa-chevron-left"></i></a>

                        <div class="headerArea">


                        </div>

                    </header>
                    <audio src="../tone/click_sound.mp3" id="toneAudio"></audio>
                    <audio src="../tone/alert.wav" id="postAudio"></audio>

                    <div id="loadermore" class="mk-abel text-light text-center" style="display:none;">Loading more ...</div>


                    <div class="chat-box">

                    </div>



                    <button type="button" class="to-bottom fa fa-caret-down p-2" onclick="scrollButtom(this)"></button>
                    <form action="#" id="msg-box">
                        <div id="infoText" class="alert alert-danger fade"></div>
                        <?php if ($num_block < 1) {
                        ?>
                            <div class="icons">
                                <span id="image-modal-caller" data-target="#ImageModal" data-toggle="modal"><i class=" fa fa-image image"></i></span>
                                <!-- REM TO CODE FOR AUDIO INPUT -->
                            </div>
                        <?php } ?>


                        <div class="type-box">
                            <input type="number" class="em-id-type" id="sender_id" value="<?= $unique_id ?>">
                            <input type="number" class="em-id-type" id="receiver_id" value="<?= $receiverID ?>">
                            <?php if ($num_block < 1) {
                            ?>
                                <textarea id="text-box" cols="30" rows="3" placeholder="Type message here..."></textarea>
                                <button type="button" title="Send message" class="send btn" id="text-send-btn" disabled><i class="fa fa-paper-plane send-text-icon"></i>
                                    <span class="spinner-grow spinner-grow-sm d-none send-text-loader"></span></button>
                            <?php  } ?>

                        </div>

                    </form>
                </section>

                <?php if ($num_block < 1) { ?>

                    <!----------- Modals ----------->

                    <!-- FOR EDIT MODAL -->
                    <div class="modal fade" id="EditModal" tabindex="" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <button type="button" class="btn btn-close" data-toggle="modal" data-target="#EditModal" data-bs-dismiss="modal" aria-label="Close"> <i class="fa fa-times"></i> </button>
                                </div>

                                <form id="edit_message">
                                    <div class="modal-body">
                                        <h5>Edit Message</h5>
                                        <div class="mk-flex">
                                            <input type="number" id="msg_id">

                                            <div class="form-group">
                                                <textarea class="" id="modal_msg"></textarea>
                                            </div>


                                            <input type="file" class="" name="imgfile" id="msg_file">
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-success d-flex align-items-center mk-abel" id="editBtn">
                                            <span>Submit</span>
                                            <span class="spinner-grow spinner-grow-sm ml-1 d-none" id="loaderUpload"></span>
                                        </button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>

                    <!-- FOR IMAGE POST MODAL -->
                    <div class="modal fade fadeOut" id="ImageModal" tabindex="" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <button type="button" class="btn btn-close" data-toggle="modal" data-target="#ImageModal" data-bs-dismiss="modal" aria-label="Close"> <i class="fa fa-times"></i> </button>
                                </div>
                                <!-- multipart/form-data -->
                                <div class="alert alert-danger text-center" id="imageText"></div>
                                <form id="image_form" method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <h5>Upload image</h5>
                                        <div class="mk-flex">


                                            <div class="form-group">
                                                <div class="upload-box">
                                                    <img src="" alt="Preview Image" id="previewImage">
                                                    <input type="file" name="image_file" id="image_file" accept="image/*" hidden>
                                                    <p>Browse file upload <small>Maximum: 2mb</small></p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <textarea class="form-control" id="image_msg" name="image_msg" placeholder="Attach message* optional"></textarea>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="modal-footer d-flex justify-content-between">

                                        <input type="number" class="em-id-type" id="image_sender_id" name="image_sender_id" value="<?= $unique_id ?>">
                                        <input type="number" class="em-id-type" id="image_receiver_id" name="image_receiver_id" value="<?= $receiverID ?>">
                                        <button type="button" class="btn btn-success d-flex align-items-center mk-abel float-right" id="imageUploadBtn">
                                            <span class="btn-txt">Submit</span>
                                            <span class="spinner-grow spinner-grow-sm ml-1 d-none" id="imageLoader"></span>
                                        </button>

                                        <div class="info-on-upload d-none">
                                            <span class="spinner-border spinner-border-sm"></span>
                                            <i></i>
                                        </div>

                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>

                <?php } ?>

                <!----------- Modals ----------->


        <?php
            }
        } else {
            echo '<p class="no-chat mk-aladin text-center bg-black">User Id not found please go back</p>';
        }
        ?>
    </div>

    <script src="../connectivity.js"></script>
    <script src="./chat.js"></script>

</body>

</html>