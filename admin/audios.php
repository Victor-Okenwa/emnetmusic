<?php require_once "./assets/access.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "./assets/vendors.html" ?>
    <?php include "./assets/datatables.html" ?>
</head>

<body>
    <?php include "./assets/navbar.php" ?>

    <div id="top_page"></div>

    <main class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3>Audios On Emnet</h3>
            </div>
            <div class="card-body overflow-auto">
                <table class="table-responsive table table-bordered table-striped table-hover">
                    <thead class="text-light" style="background: linear-gradient(200deg ,#196edd, rgb(1, 68, 214));">
                        <tr class="">
                            <th>ID</th>
                            <th>Song name</th>
                            <th>Artist(s)</th>
                            <th>Poster Name</th>
                            <th>Thumbnail</th>
                            <th>Audio File</th>
                            <th>Genre</th>
                            <th>Age Limit</th>
                            <th>Remix</th>
                            <th>Streams</th>
                            <th>Adds <span style="font-size: 9px;">Added to playlist</span></th>
                            <th>Created</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query_songs = $conn->query("SELECT * FROM songs");
                        while ($row = $query_songs->fetch_assoc()) {
                        ?>
                            <tr id="<?= $row['song_id']  ?>">
                                <td> <?= $row['song_id']  ?></td>
                                <td> <?= $row['song_name'] ?></td>
                                <td> <?= $_SESSION['adminUniqueID'] == $row['admin_id'] ? "You" : $row['artist'] ?>
                                </td>
                                <td> <?= (empty($row['creator_name'])) ? $row['admin_name'] : $row['creator_name']  ?></td>
                                <td>

                                    <img id="img" src="../audio_thumbnails/<?= $row['thumbnail']  ?>" width="100px" height="100px" class="rounded-pill">

                                </td>
                                <td>
                                    <audio controls src="../audios/<?= $row['audio_file'] ?>"></audio>
                                </td>
                                <td> <?= $row['genre']  ?></td>
                                <td> <?= ($row['age_limit'] == 0) ? "All ages" : "18+"  ?></td>
                                <td> <?= ($row['remix'] == 0) ? "Main" : "Remix"  ?></td>
                                <td> <?= $row['streams']  ?></td>
                                <td> <?= $row['adds']  ?></td>
                                <td> <?=
                                        getDateTimeDiff($row['created_on']);
                                        ?>
                                </td>
                                <td>
                                    <?php
                                    if ($_SESSION['adminUniqueID'] == $row['admin_id']) {
                                    ?>
                                        <a href="edit_audio.php?songID=<?= $row['song_id']  ?>" class="btn bg-info deleteBtn text-light">Edit
                                        </a>
                                    <?php
                                    } else {
                                        echo "";
                                    };
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    if ($_SESSION['adminUniqueID'] == $row['admin_id'] || $data_admin['admin_rank'] == "super admin") {
                                    ?>
                                        <button class="btn bg-danger deleteBtn text-light" data-toggle="modal" data-target="#deleteModal" data-id="<?= $row['song_id'] ?>" value="<?= $row['song_name'] ?>" onclick="moveValues(this, document.getElementById('deleteID'), document.getElementById('deleteName'))">Delete
                                            <span class="audio-to-delete d-none"><?= $row['song_name'] ?></span>
                                        </button>
                                    <?php
                                    } else {
                                        echo "";
                                    };
                                    ?>

                                </td>
                            </tr>

                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div class="modal fade" tabindex="-1" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to delete <span id="deleteName"></span> </h5>
                    <button type="button" class="btn btn-close" data-toggle="modal" data-target="#deleteModal" data-bs-dismiss="modal" aria-label="Close"> <i class="fa fa-times"></i> </button>
                </div>
                <div class="modal-body">
                    <form id="deleteForm" method="post">
                        <input type="number" id="deleteID" class="field_1" name="delete-id" required>
                        <button class="btn btn-info deleteSend" type="button">
                            <span class="spinner spinner-grow spinner-grow-sm d-none"></span>
                            Delete</button>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <div class="d-none" style="width:100%;" id="deleteMsg"></div>
                </div>
            </div>
        </div>
    </div>

    <?php include "./assets/footer.html" ?>
    <?php include "./assets/scripts.html" ?>

    <script>
        $(document).ready(() => {
            const deleteBtn = document.querySelector('.deleteSend'),
                deleteForm = $('#deleteForm'),
                deleteMsg = document.getElementById('deleteMsg');

            deleteBtn.addEventListener('click', () => {
                sendToBackend(deleteBtn, deleteForm, 'api/delete_audio.php', 50000, deleteMsg, document.getElementById('deleteID').value);
            });
        });
    </script>
</body>

</html>