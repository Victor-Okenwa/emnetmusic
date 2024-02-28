<?php

/* ____________________|

# HERE WE ARE GETTING CREATOR'S AUDIOS

|____________________*/

require "access.php";


?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php include "./meta/header.php" ?>
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <style>
        #img {
            transition: 1s ease;
        }

        #img:hover {
            transform: scale(3);
        }
    </style>

</head>

<body id="page-top" class="bg-light overflow-auto sidebar-toggled">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php if ($nums_creators > 0) {

            include "./meta/sidebar.php";
        ?>

        <?php  } else {
        } ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column ">

            <!-- Main Content -->
            <div id="content" class="bg-dark opacity-25">

                <?php include "./meta/topbar.php" ?>

                <div class="container-fluid mt-5 pt-5">

                    <div class="card shadow mb-4 bg-dark" style="overflow-x: auto;">
                        <div class="card-header py-3 text-light" style="background: linear-gradient(00deg ,#000000, rgb(28, 29, 28));">
                            <h6 class="m-0 font-weight-bold text-light">My POSTS/Uploads</h6>
                        </div>
                        <div class="card-body text-light">
                            <?php $query_uploads = mysqli_query($conn, "SELECT * from songs WHERE creator_id = '$unique_id' order by song_id desc"); ?>
                            <div class="table-responsive">
                                <table class="table-responsive table table-bordered table-striped table-hover text-light" id="dataTable" width="100%" cellspacing="0">
                                    <thead class=" text-light" style="background: linear-gradient(00deg ,#000000, rgb(28, 29, 28));">
                                        <tr>
                                            <th>Song Name</th>
                                            <th>Artist</th>
                                            <th>Thumbnail</th>
                                            <th>Audio file</th>
                                            <th>Adds</th>
                                            <th>Streams</th>
                                            <th>Genre</th>
                                            <th>Age Limit</th>
                                            <th>Remix</th>
                                            <th>Posted</th>
                                            <th>Link</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php while ($fetch_uploads = mysqli_fetch_assoc($query_uploads)) {  ?>
                                            <tr>
                                                <td><?= $fetch_uploads['song_name'];  ?></td>
                                                <td><?= $fetch_uploads['artist'];  ?></td>
                                                <td><img src="../audio_thumbnails/<?= $fetch_uploads['thumbnail'];  ?>" id="img" class="rounded-sm" width="50px" height="40px"></td>
                                                <td><audio src="../audios/<?= $fetch_uploads['audio_file'];  ?>" controls></audio></td>
                                                <td><?= $fetch_uploads['adds'];  ?></td>
                                                <td><?= $fetch_uploads['streams'];  ?></td>
                                                <td><?= $fetch_uploads['genre'];  ?></td>
                                                <td><?= $fetch_uploads['remix'] == 0 ? "No age limit" : "18+"  ?></td>
                                                <td><?= $fetch_uploads['age_limit'] == 0 ? "Main song" : "Remix"  ?></td>
                                                <td><?= getDateTimeDiff($fetch_uploads['created_on'])  ?></td>

                                                <td class=" position-relative">
                                                    <!-- this table data is for song link and share button  -->
                                                    <small class="songLink position-absolute bg-light text-dark top-0 px-1 py-1 d-none" style="top: -50%; right: 45%; max-width: 480%; border-radius: 5px 5px 0px 5px">
                                                        <?= "$domain/view.php?song=" . trim($fetch_history_join['song_id']) ?>

                                                        <button class="linkBtn btn btn-md btn-success" id="" type="button" data-toggle="popover" data-target="#linkPopover"><i class=" fa fa-share"></i></button>
                                                </td>

                                                <td>
                                                    <!-- this table data is for editing -->
                                                    <a href="editAudio.php?songID=<?= trim(htmlspecialchars($fetch_uploads['song_id'], ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5)) ?>" class="btn text-light" style="background:linear-gradient(00deg ,#134aff, rgb(23, 37, 235));">Edit</a>
                                                </td>

                                                <td>
                                                    <!-- this table data is for deleting -->
                                                    <button data-toggle="modal" data-target="#deleteModal" value="<?= $fetch_uploads['song_id'] ?>" class="btn btn-danger deleteBtn" style="background: linear-gradient(00deg ,#ff1313, rgb(201, 13, 13));">Delete
                                                        <span class="audio-to-delete" hidden><?= $fetch_uploads['song_name'] ?></span>
                                                    </button>
                                                </td>

                                            </tr>
                                        <?php } ?>
                                    </tbody>

                                    <tfoot class="bg-secondary ">
                                        <tr>
                                            <th>Song Name</th>
                                            <th>Artist</th>
                                            <th>Thumbnail</th>
                                            <th>Audio file</th>
                                            <th>Adds</th>
                                            <th>Streams</th>
                                            <th>Genre</th>
                                            <th>Age Limit</th>
                                            <th>Remix</th>
                                            <th>Posted</th>
                                            <th>Link</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- End of Main Content -->

            <?php include "./meta/footer.php" ?>

            <!-------- Delete Modal --------->
            <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-542">
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header d-flex flex-column">
                                <h5 class="modal-title" id="exampleModalCenterTitle">Are you sure you want to delete <span class="audio-name"> </span></h5>
                            </div>
                            <form method="post" id="deleteForm">
                                <div class="modal-body">

                                    <input type="number" name="creator_id" value="<?= $unique_id  ?>" name="creator-id" hidden>
                                    <input type="number" class="deleteID" name="delete-id" hidden>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                                    <button class="btn btn-primary deleteSend" type="button">
                                        <span class="spinner-grow spinner-grow-sm d-none" id="loaderDelete"></span>
                                        Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-------- Delete Modal --------->


        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php include "./meta/scripts.php" ?>
    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

    <script>
        var deleteBtns = Array.from(document.querySelectorAll(".deleteBtn")),
            audioName = document.querySelector(".audio-name"),
            deleteID = document.querySelector(".deleteID"),
            loaderDelete = document.getElementById("loaderDelete"),
            infoDiv = document.querySelector(".info"),
            deleteForm = document.getElementById("deleteForm"),
            deleteSend = document.querySelector(".deleteSend");


        deleteBtns.forEach((btn) => {
            btn.addEventListener("click", () => {
                deleteID.value = btn.value;
                audioName.textContent = btn.querySelector(".audio-to-delete").textContent;
            })
        })

        deleteForm.onsubmit = (e) => {
            e.preventDefault();
        }



        let xhr = new XMLHttpRequest();

        deleteSend.onclick = () => {
            xhr.open('POST', 'deleteAudioApi.php', true);
            xhr.onload = () => {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let data = xhr.response;
                        if (data == "Delete Successful!") {
                            alert("Delete Successful");
                            window.location = window.location;
                        } else {
                            if (data != "") {
                                alert(data)

                            }
                        }
                    }
                }
            }

            xhr.onreadystatechange = function() {
                if (this.readyState < 4) {
                    loaderDelete.classList.remove("d-none");
                } else {
                    loaderDelete.classList.add("d-none")
                }
            }
            xhr.onerror = function() {
                messageInfo("Request error...");
            }
            // we have to send the form data through ajax to php
            let formData = new FormData(deleteForm);
            xhr.send(formData); //sending the form to php
        }

        const songLink = document.querySelectorAll(".songLink")
        const linkBtn = document.querySelectorAll(".linkBtn");
        const msg = encodeURIComponent("Hi, Checkout this music on emnetmusic please follow and stream");


        linkBtn.forEach((btn) => {
            btn.addEventListener("click", () => {
                var title = "Hi, am <?= $nickname ?>"
                var link = btn.parentElement.querySelector(".songLink").innerText.trim();
                if (navigator.share) {

                    navigator.share({
                        title: decodeURIComponent(title.toLocaleUpperCase()),
                        url: decodeURI(link),
                        text: decodeURIComponent(msg),
                    })

                } else {
                    alert("Navigator not found on this device")
                }

            })
        })

        // for (let i = 0; i < linkBtn.length; i++) {
        //     let show = false;
        //     linkBtn[i].onclick = function() {
        //         if (show) {
        //             songLink[i].classList.add("d-none");
        //             show = false;
        //         } else {
        //             songLink[i].classList.remove("d-none");
        //             show = true;
        //         }
        //     }
        // }
    </script>
</body>

</html>