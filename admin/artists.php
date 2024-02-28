<?php require_once "./assets/access.php"; ?>
<!DOCTYPE html>
<html lang="en" id="artists">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "./assets/vendors.html" ?>
    <?php require_once "./assets/datatables.html" ?>
</head>

<body>
    <?php require_once "./assets/navbar.php" ?>
    <div id="top_page"></div>

    <main class="container-fluid">
        <a href="suspendedArtists.php" class="btn btn-warning mb-2">
            There are <?= mysqli_num_rows(mysqli_query($conn, "SELECT * FROM creators WHERE NOT suspension = 0"))  ?>
            suspended artists</a>


        <div class="card">
            <div class="card-header text-light" style="background: linear-gradient(360deg ,rgb(2, 164, 204), rgb(3, 109, 128))">
                <h3>Current Artists</h3>
            </div>
            <div class="card-body overflow-auto">
                <table class=" table-responsive table table-bordered table-striped table-hover">
                    <thead class="text-light" style="background: linear-gradient(360deg ,rgb(2, 164, 204), rgb(3, 109, 128))">
                        <tr class="">
                            <!-- <th>Chk</th> -->
                            <th>ID</th>
                            <th>Name</th>
                            <th>Nickname</th>
                            <th>No. songs</th>
                            <th>Email</th>
                            <th>Profile</th>
                            <th>Conutry</th>
                            <th>Contact</th>
                            <th>Gender</th>
                            <th>Joined</th>
                            <th>Suspend</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query_creators = $conn->query("SELECT * from users INNER JOIN creators WHERE users.user_id = creators.user_id and creators.suspension = 0");
                        while ($row = $query_creators->fetch_assoc()) {
                        ?>
                            <tr id="<?= $row['user_id'] ?>">
                                <td> <?= $row['artist_id']  ?></td>
                                <td> <?= $row['surname'] . " " . $row['firstname'] . " " . $row['othername']  ?></td>
                                <td> <?= $row['nickname']   ?></td>
                                <td> <?= shortenDigits($row['songs'])  ?></td>
                                <td> <?= $row['email'] ?></td>
                                <td>

                                    <img id="img" src="../userprofiles/<?= $row['user_profile']  ?>" width="100px" height="100px" class="rounded-pill">

                                </td>

                                <td> <?= $row['country']  ?></td>
                                <td> <?= $row['phone_number'] ?></td>
                                <td> <?= $row['gender']  ?></td>
                                <td> <?=
                                        getDateTimeDiff($row['created_on']);
                                        ?>
                                </td>
                                <td>
                                    <?php if ($data_setting['creator_sus'] == 1 || $data_admin['admin_rank'] == "super admin") { ?>
                                        <button class="btn suspendBtn text-light" style="background: linear-gradient(360deg ,rgb(2, 164, 204), rgb(3, 109, 128))" data-toggle="modal" data-target="#suspendModal" data-id="<?= $row['artist_id'] ?>" value="<?= $row['nickname'] ?>" onclick="moveValues(this, document.getElementById('suspendID'), document.getElementById('suspendName'))">
                                            Suspend
                                        </button>
                                    <?php } ?>
                                </td>

                                <td>
                                    <?php
                                    if ($data_setting['creator_delete'] == 1 || $data_admin['admin_rank'] == "super admin") {
                                    ?>
                                        <button class="btn bg-danger deleteBtn text-light" data-toggle="modal" data-target="#deleteModal" data-id="<?= $row['user_id'] ?>" value="<?= $row['nickname'] ?>" onclick="moveValues(this, document.getElementById('deleteID'), document.getElementById('deleteName'))">Delete
                                        </button>
                                    <?php
                                    }
                                    ?>

                                </td>
                            </tr>

                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!------------------------------ Modals ---------------------------->
    <!-------- Suspend Modal --------->
    <div class="modal fade" id="suspendModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Suspend <span id="suspendName"></span> </h5>
                    <button type="button" class="btn btn-close" data-toggle="modal" data-target="#suspendModal" data-bs-dismiss="modal" aria-label="Close"> <i class="fa fa-times"></i> </button>
                </div>

                <div class="modal-body">
                    <form id="suspendForm" method="post">
                        <input type="text" id="suspendID" class="field_1" name="suspend-id" required>
                        <input type="number" id="suspensionPeriod" class="form-control" name="period" placeholder="Suspension period in days" required>
                        <div class="form-group form-inline row mt-2 col-12">
                            <input type="checkbox" id="indefinite" name="indefinite" class="custom-checkbox">
                            <label class="col-md-2" for="indefinite">Indefinite</label>
                        </div>

                        <button class="btn btn-info suspendSend" type="button">
                            <span class="spinner spinner-grow spinner-grow-sm d-none"></span>
                            Suspend</button>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <div class="d-none" style="width:100%;" id="suspendMsg"></div>
                </div>
            </div>
        </div>
    </div>

    <!-------- Delete Modal --------->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to delete <span id="deleteName"></span>'s handle </h5>
                    <button type="button" class="btn btn-close" data-toggle="modal" data-target="#deleteModal" data-bs-dismiss="modal" aria-label="Close"> <i class="fa fa-times"></i> </button>
                </div>

                <div class="modal-body">
                    <form id="deleteForm" method="post">
                        <input type="text" id="deleteID" class="field_1" name="delete-id" required>
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


    <?php require_once "./assets/footer.html" ?>
    <?php require_once "./assets/scripts.html" ?>

    <script>
        $(document).ready(function() {
            const suspendBtn = document.querySelector('.suspendSend'),
                suspendForm = $('#suspendForm'),
                suspendMsg = document.getElementById('suspendMsg'),
                suspensionPeriod = document.getElementById("suspensionPeriod"),
                indefinite = document.getElementById("indefinite");

            indefinite.addEventListener("change", () => {
                if (indefinite.checked) {
                    suspensionPeriod.value = "";
                    suspensionPeriod.setAttribute("disabled", true);
                } else {
                    suspensionPeriod.removeAttribute("disabled");
                }
            });

            suspendBtn.addEventListener('click', () => {
                sendToBackend(suspendBtn, suspendForm, 'api/suspend.php', 50000, suspendMsg, document.getElementById('suspendID').value);
            });

            const deleteBtn = document.querySelector('.deleteSend'),
                deleteForm = $('#deleteForm'),
                deleteMsg = document.getElementById('deleteMsg');

            deleteBtn.addEventListener('click', () => {
                sendToBackend(deleteBtn, deleteForm, 'api/delete_artist.php', 50000, deleteMsg, document.getElementById('deleteID').value);
            });

        });

        // outputMessage(suspendMsg, 'Testing mesage', 5000, 'success');
    </script>
</body>

</html>