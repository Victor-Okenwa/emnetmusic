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
                <h3>Users On Emnet</h3>
            </div>
            <div class="card-body overflow-auto">
                <table class="table-responsive table table-bordered table-striped table-hover">
                    <thead class="text-light" style="background: linear-gradient(20deg ,#333, rgb(58, 58, 58));">
                        <tr>
                            <th>ID</th>
                            <th>Name(S/F/O)</th>
                            <th>Nickname</th>
                            <th>Email</th>
                            <th>Profile</th>
                            <th>Phone Number</th>
                            <th>Country</th>
                            <th>Location</th>
                            <th>Ip Address</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Active since</th>
                            <th>Status</th>
                            <?=
                            ($data_admin['admin_rank'] == "super admin") ? "<th>Delete</th>" : ""
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query_users = $conn->query("SELECT * FROM users");
                        while ($row = $query_users->fetch_assoc()) {
                        ?>
                            <tr id="<?= $row['id']  ?>">
                                <td> <?= $row['id']  ?></td>
                                <td> <?= $row['surname'] . " " . $row['firstname'] . " " . $row['othername'] ?></td>
                                <td> <?= $row['nickname']  ?></td>
                                <td> <?= $row['email']  ?></td>
                                <td>
                                    <img id="img" src="../userprofiles/<?= $row['user_profile']  ?>" width="100px" height="100px" class="rounded-pill">
                                </td>
                                <td> <?= $row['phone_number']  ?></td>
                                <td> <?= $row['country']  ?></td>
                                <td> <?= $row['location']  ?></td>
                                <td> <?= $row['ip_address']  ?></td>
                                <td> <?php echo date("Y") - intval($row['birthday'], 10)  ?></td>
                                <td> <?= $row['gender']  ?></td>
                                <td> <?=
                                        getDateTimeDiff($row['acct_date']);
                                        ?></td>
                                <td> <?= $row['status']  ?></td>
                                <?php
                                if ($data_admin['admin_rank'] == "super admin") { ?>
                                    <td>
                                        <button class="btn bg-danger deleteBtn text-light" data-toggle="modal" data-target="#deleteModal" data-id="<?= $row['id'] ?>" value="<?= $row['nickname'] ?>" onclick="moveValues(this, document.getElementById('deleteID'), document.getElementById('deleteName'))">Delete
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
                    <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to delete <span id="deleteName"></span>'s handle </h5>
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
        const deleteBtn = document.querySelector('.deleteSend'),
            deleteForm = $('#deleteForm'),
            deleteMsg = document.getElementById('deleteMsg');

        deleteBtn.addEventListener('click', () => {
            sendToBackend(deleteBtn, deleteForm, 'api/delete_user.php', 50000, deleteMsg, document.getElementById('deleteID').value);
        });
    </script>

</body>

</html>