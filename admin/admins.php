<?php require_once "./assets/access.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "./assets/vendors.html" ?>
    <?php include "./assets/datatables.html" ?>
</head>

<body class="bg-dark">
    <?php include "./assets/navbar.php" ?>

    <div id="top_page"></div>

    <main class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="text-center text-uppercase">Admins Table</div>
                <div id="output" class="d-none"></div>
            </div>
            <div class="card-body">
                <?= ($admin_rank == "super admin") ? "<a href='./add_admin.php' class='btn btn-dark mb-1' style='background: linear-gradient(#160616, rgb(21, 22, 2))'>Add a new admin account</a>" : ""
                ?>

                <table class="table-responsive table table-bordered table-striped table-hover text-capitalize">
                    <thead class="text-light" style="background: linear-gradient(#161616, rgb(2, 22, 2))">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Nickname</th>
                            <th>Email</th>
                            <th>Profile</th>
                            <th>Admin rank</th>
                            <th>Phone number</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <?=
                            ($admin_rank == "super admin") ? "
                                <th>Block</th>
                                <th>Delete</th>" : ""
                            ?>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query_admins = $conn->query("SELECT * FROM admin WHERE NOT id = $admin_id");
                        while ($row = $query_admins->fetch_assoc()) {
                        ?>
                            <tr id="<?= $row['id']  ?>">
                                <td> <?= $row['id']  ?></td>
                                <td> <?= $row['lastname'] . " " . $row['firstname'] ?></td>
                                <td> <?= $row['nickname']   ?></td>
                                <td> <?= $row['email']  ?></td>
                                <td>

                                    <img id="img" src="./adminprofile/<?= $row['admin_profile']  ?>" width="100px" height="100px" class="rounded-pill">

                                </td>
                                <td> <?= $row['admin_rank']  ?></td>
                                <td> <?= $row['phone_number']  ?></td>
                                <td> <?= $row['status'] ?></td>

                                <td> <?=
                                        getDateTimeDiff($row['created_on']);
                                        ?></td>
                                <?php
                                if ($admin_rank == "super admin") {
                                ?>
                                    <td>
                                        <?php
                                        if ($row['block_tag'] == 0) {
                                        ?>
                                            <button id="<?= $row['admin_id'] ?>" class='btn bg-success blockBtn text-light' style="background: linear-gradient(#161616, rgb(2, 22, 2))" onclick="adminState(<?= $row['admin_id'] ?>, '<?= $row['id'] ?>',  '<?= $row['nickname'] ?>', 'block') ">Block
                                                <span class='spinner spinner-grow spinner-grow-sm d-none'></span>
                                            </button>
                                        <?php
                                        } else {
                                        ?>
                                            <button id="<?= $row['admin_id'] ?>" class="btn btn-primary unblockBtn" onclick="adminState(<?= $row['admin_id'] ?>, '<?= $row['id'] ?>',  '<?= $row['nickname'] ?>', 'unblock') ">Unblock
                                                <span class='spinner spinner-grow spinner-grow-sm d-none'></span>
                                            </button>
                                        <?php } ?>
                                    </td>
                                <?php
                                }
                                ?>

                                <?php
                                if ($data_admin['admin_rank'] == "super admin") {
                                ?>
                                    <td>
                                        <button class='btn bg-danger deleteBtn text-light' data-toggle="modal" data-target="#deleteModal" data-id="<?= $row['id'] ?>" value="<?= $row['nickname'] ?>" onclick="moveValues(this, document.getElementById('deleteID'), document.getElementById('deleteName'))">Delete
                                            <span class='admin-to-delete d-none'> <?= $row['nickname'] ?></span>
                                        </button>
                                    </td>
                                <?php } ?>

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
                    <h5 class="modal-title" id="exampleModalLabel">Are you sure you Block to delete <span id="deleteName"></span> </h5>
                    <button type="button" class="btn btn-close" data-toggle="modal" data-target="#deleteModal" data-bs-dismiss="modal" aria-label="Close"> <i class="fa fa-times"></i> </button>
                </div>
                <div class="modal-body">
                    <form id="deleteForm" method="post">
                        <input type="number" id="deleteID" class="field_1" name="delete-id" required>
                        <button class="deleteSend btn btn-danger" type="button">
                            <i class="fa fa-times"></i> Delete
                            <span class="spinner spinner-grow spinner-grow-sm d-none"></span>
                        </button>
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
        function adminState(buttonID, admin_id, admin_name, type) {
            const button = document.getElementById(`${buttonID}`);
            const loader = button.querySelector(`.spinner`);

            const confirm_ajax = confirm(`Are you sure you want to ${type} ${admin_name}`);
            if (confirm_ajax) {
                $.ajax({
                    url: "api/admin_state.php",
                    method: "POST",
                    timeout: 10000,
                    data: {
                        type: type,
                        admin_id: admin_id,
                    },
                    beforeSend: function() {
                        loader.classList.remove('d-none');
                    },
                    complete: function() {
                        loader.classList.add('d-none');
                    },
                    error: function(xhr, status, error) {
                        loader.classList.add('d-none');
                        if (status === 'timeout') {
                            alert('Request Timed Out');
                        } else {
                            alert('Error: ' + error);
                            console.log(error);
                        }
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            alert(response.message);
                            if (type == 'block') {
                                button.parentElement.innerHTML = `
                                <button id="${buttonID}" class="btn btn-primary unblockBtn" onclick="adminState(${buttonID}, '${admin_id}',  '${admin_name}', 'unblock')">Unblock
                                    <span class="spinner spinner-grow spinner-grow-sm d-none"></span>
                                </button>
                            `;
                            } else {
                                button.parentElement.innerHTML = `
                                <button id="${buttonID}" class="btn btn-primary blockBtn" style="background: linear-gradient(#161616, rgb(2, 22, 2))"  onclick="adminState(${buttonID}, '${admin_id}',  '${admin_name}', 'block')">Block
                                    <span class='spinner spinner-grow spinner-grow-sm d-none'></span>
                                </button>
                            `;
                            }
                        } else {
                            alert(response.message);
                        }
                    }
                });
            }
        }

        const deleteBtn = document.querySelector('.deleteSend'),
            deleteForm = $('#deleteForm'),
            deleteMsg = document.getElementById('deleteMsg');

        deleteBtn.addEventListener('click', () => {
            sendToBackend(deleteBtn, deleteForm, 'api/delete_admin.php', 50000, deleteMsg, document.getElementById('deleteID').value);
        });

        // blockSend.addEventListener("click", () => {
        //     adminState(blockSend, blockForm, 'api/admin_state.php', blockMsg, 'block');
        // });
    </script>
</body>

</html>