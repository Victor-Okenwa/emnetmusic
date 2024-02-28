<?php require_once "./assets/access.php"; ?>
<!DOCTYPE html>
<html lang="en" id="artists">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require "./assets/vendors.html" ?>
    <?php require "./assets/datatables.html" ?>
</head>

<body>
    <?php require "./assets/navbar.php" ?>
    <div id="top_page"></div>

    <main class="container-fluid">
        <div id="reinstateInfo" class="d-none" style="width: 100%; position: fixed; z-index: 30; "></div>

        <div class="card border-warning">
            <div class="card-header bg-warning">
                <h3>Suspended Artists</h3>
            </div>

            <div class="card-body overflow-auto">
                <table class=" table-responsive table table-bordered table-striped table-hover" style="width: 100%">
                    <thead class="table-dark">
                        <tr class="">
                            <th>ID</th>
                            <th>Name</th>
                            <th>Nickname</th>
                            <th>No. songs</th>
                            <th>Email</th>
                            <th>Profile</th>
                            <th>Conutry</th>
                            <th>Contact</th>
                            <th>Gender</th>
                            <th>Status</th>
                            <th>Reinstate</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query_creators = mysqli_query($conn, "SELECT * from users INNER JOIN creators WHERE users.user_id = creators.user_id and not creators.suspension = 0");
                        while ($row = $query_creators->fetch_assoc()) {
                        ?>
                            <tr id="<?= $row['artist_id']  ?>">
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
                                        suspensionExpiration($row['created_on'], $row['period'], $row['suspension'], $row['artist_id']);
                                        ?>
                                </td>
                                <td>
                                    <?php
                                    if ($data_setting['creator_reinstate'] == 1 || $data_admin['admin_rank'] == "super admin") {
                                    ?>
                                        <button class="btn btn-ghost-warning btn-warning reviveBtn text-dark reinstateBtn">Reinstate
                                            <span class="spinner spinner-grow spinner-grow-sm d-none"></span>
                                            <form id="reinstateForm" method="post">
                                                <input type="number" id="reinstateID" class="field_1" name="reinstate-id" value="<?= $row['artist_id'] ?>">
                                                <input type="text" id="reinstateName" class="field_1" name="reinstate-name" value="<?= $row['nickname'] ?>">
                                            </form>
                                        </button>
                                    <?php
                                    }
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    if ($data_admin['admin_rank'] == "super admin") {
                                    ?>
                                        <button class="btn bg-info editBtn text-light" data-toggle="modal" data-target="#editModal" value="<?= $row['artist_id'] ?>">Edit
                                            <span class="creator-to-edit d-none"><?= $row['nickname'] ?></span>
                                            <span class="edit-condition d-none"><?= $row['period'] == 0 && $row['suspension'] == 1 ? "infinite" : "{$row['period']}" ?></span>
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

    <?php require_once "./assets/footer.html" ?>
    <?php require_once "./assets/scripts.html" ?>

    <script>
        $(document).ready(() => {
            var reinstateBtn = document.querySelectorAll(".reinstateBtn");

            reinstateBtn.forEach(btn => {
                btn.onclick = function() {
                    var reinstateInfo = document.getElementById('reinstateInfo');
                    var reinstateForm = $('#reinstateForm');
                    var artistID = document.getElementById("reinstateID").value;
                    var artistName = document.getElementById("reinstateName").value;
                    var conf = confirm(`Are you sure you want to reinstate ${artistName}`);

                    if (conf) {

                        sendToBackend(btn, reinstateForm, './api/reinstate.php', 5000, reinstateInfo, artistID);
                    }
                }
            });
        });
    </script>
</body>

</html>