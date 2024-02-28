<?php require_once "./assets/access.php"; ?>
<!DOCTYPE html>
<html lang="en">

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



        <div class="select-info position-fixed top-0 w-100 d-none" align="center" style="z-index: 2000; background: rgba(0,0,0,.30)">
            <h5 class="text-center"></h5>
            <form method="POST" class="d-block m-auto">
                <button type="button" class="btn btn-success text-light" id="acceptSelect">
                    Accept requests <span class="spinner spinner-grow spinner-grow-sm d-none"></span>
                </button>

                <button type="button" class="btn btn-danger text-light" id="rejectSelect">
                    Reject requests <span class="spinner spinner-grow spinner-grow-sm d-none"></span>
                </button>
            </form>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Requests from user</h3>
            </div>

            <div class="card-body overflow-auto">
                <table class="table-responsive table table-bordered table-striped text-capitalize">
                    <thead class="table-dark">
                        <tr>
                            <th>Check</th>
                            <th>ID</th>
                            <th>Nickname</th>
                            <th>Email</th>
                            <th>Ip Address </th>
                            <th>Sent</th>
                            <th>Accept</th>
                            <th>Reject</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query_requests = $conn->query("SELECT * FROM requests WHERE status = 0");
                        while ($row = $query_requests->fetch_assoc()) {
                        ?>
                            <tr id="<?= $row['request_id']  ?>">
                                <td>
                                    <?php if ($data_setting['request_accpt'] == 1 || $admin_rank == "super admin") { ?>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" id="check<?= $row['request_id']  ?>" class="switches custom-control-input" value="<?= $row['request_id'] ?>" <?= $row['selector'] == 1 ? "checked" : "" ?>>
                                            <label class="custom-control-label" for="check<?= $row['request_id']  ?>"></label>
                                        </div>
                                    <?php } ?>
                                </td>
                                <td> <?= $row['request_id']  ?></td>
                                <td> <?= $row['user_name'] ?></td>
                                <td> <?= $row['email']  ?></td>
                                <td> <?= $row['ip_address']  ?></td>

                                <td> <?=
                                        getDateTimeDiff($row['date']);
                                        ?></td>

                                <td>
                                    <?php if ($data_setting['request_accpt'] == 1 || $admin_rank == "super admin") { ?>
                                        <button class='btn bg-success text-light' data-id="<?= $row['request_id'] ?>" value="<?= $row['user_name'] ?>" onclick="moveValues(this, document.getElementById('acceptID'), document.getElementById('acceptName'))" data-toggle="modal" data-target="#acceptModal">Accept
                                        </button>
                                    <?php } ?>
                                </td>



                                <td>
                                    <?php if ($data_setting['request_rej'] == 1 || $admin_rank == "super admin") { ?>
                                        <button class='btn bg-danger deleteBtn text-light' data-toggle='modal' data-target='#rejectModal' data-id="<?= $row['request_id'] ?>" value="<?= $row['user_name'] ?>" onclick="moveValues(this, document.getElementById('rejectID'), document.getElementById('rejectName'))" data-toggle="modal" data-target="#rejectModal">Reject
                                            <span class='admin-to-delete d-none'> <?= $row['user_name'] ?></span>
                                        </button>
                                    <?php } ?>
                                </td>
                            </tr>

                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </main>


    <div class="modal fade" tabindex="-1" id="acceptModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to accept <span id="acceptName"></span> </h5>
                    <button type="button" class="btn btn-close" data-toggle="modal" data-target="#acceptModal" data-bs-dismiss="modal" aria-label="Close"> <i class="fa fa-times"></i> </button>
                </div>
                <div class="modal-body">
                    <form id="acceptForm" method="post">
                        <input type="number" id="acceptID" class="field_1" name="accept-id" required>
                        <button class="btn btn-info acceptSend" type="button">
                            <span class="spinner spinner-grow spinner-grow-sm d-none"></span>
                            Accept</button>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <div class="d-none" style="width:100%;" id="acceptMsg"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="rejectModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to accept <span id="rejectName"></span> </h5>
                    <button type="button" class="btn btn-close" data-toggle="modal" data-target="#rejectModal" data-bs-dismiss="modal" aria-label="Close"> <i class="fa fa-times"></i> </button>
                </div>
                <div class="modal-body">
                    <form id="rejectForm" method="post">
                        <input type="number" id="rejectID" class="field_1" name="reject-id" required>
                        <button class="btn btn-danger rejectSend" type="button">
                            <span class="spinner spinner-grow spinner-grow-sm d-none"></span>
                            Reject</button>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <div class="d-none" style="width:100%;" id="rejectMsg"></div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once "./assets/footer.html" ?>
    <?php require_once "./assets/scripts.html" ?>

    <script>
        $(document).ready(() => {
            var countChecked = document.querySelectorAll("input[type=checkbox]:checked").length;
            var selectInfo = document.querySelector(".select-info");
            var switches = [...document.querySelectorAll(".switches")];
            var acceptSend = document.querySelector(".acceptSend");
            var rejectSend = document.querySelector(".rejectSend");
            var multipleReject = document.getElementById("rejectSelect");
            var multipleAccept = document.getElementById("acceptSelect");

            function selectRequest(fieldID, value) {
                $.ajax({
                    url: "api/request_check.php",
                    method: 'POST',
                    timeout: 10000,

                    data: {
                        select_field: true,
                        select_id: fieldID,
                        state: value,
                    },
                    success: function(response) {
                        if (response.status !== 'success') {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        if (status === 'timeout') {
                            alert('Request timed out!');
                        } else {
                            alert('Error: ' + error);
                        }
                    },
                });
            }

            // counting number of switches checked and displaying number of checked
            if (countChecked > 0) {
                selectInfo.querySelector("h5").innerText = `${countChecked} box(es) checked`;
                selectInfo.classList.remove("d-none");

                for (let i = 0; i < switches.length; i++) {
                    if (switches[i].checked) {
                        switches[i].parentElement.parentElement.parentElement.classList.add("bg-dark", "text-light");
                    }
                }
            }

            for (let i = 0; i < switches.length; i++) {
                // getting change in cheked~true and cheked~false switches
                switches[i].addEventListener("change", () => {
                    countChecked = document.querySelectorAll("input[type=checkbox]:checked").length;

                    if (countChecked > 0) {
                        selectInfo.classList.remove("d-none");
                        selectInfo.querySelector("h5").innerText = `${countChecked} box(es) checked`;
                    } else {
                        selectInfo.classList.add("d-none");
                    }

                    if (switches[i].checked) {
                        switches[i].parentElement.parentElement.parentElement.classList.add("bg-dark", "text-light");
                        selectRequest(switches[i].value, 1);
                    } else {
                        switches[i].parentElement.parentElement.parentElement.classList.remove("bg-dark", "text-light");
                        selectRequest(switches[i].value, 0);
                    }

                });
            }


            acceptSend.onclick = function() {
                sendToBackend(acceptSend, $("#acceptForm"), 'api/accept_request.php', 50000, document.getElementById('acceptMsg'), document.getElementById('acceptID').value);
            }

            rejectSend.onclick = function() {
                sendToBackend(acceptSend, $("#rejectForm"), 'api/reject_request.php', 50000, document.getElementById('rejectMsg'), document.getElementById('rejectID').value);
            }

            function multiRequest(button, type) {
                const confirmRequest = confirm(`Sure you want to perform '${type}' request?`);
                if (!confirmRequest) return;
                let loader = button.querySelector(".spinner");

                $.ajax({
                    url: 'api/request_check.php',
                    method: "POST",
                    timeout: 10000,
                    data: {
                        multiple_requests: type,
                    },
                    beforeSend: () => {
                        loader.classList.remove("d-none");
                    },
                    complete: () => {
                        loader.classList.add("d-none");
                    },
                    success: (response) => {
                        if (response.status == 'success') {
                            document.location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        if (status == 'timeout') {
                            alert("Request timeout");
                        } else {
                            alert("Error: " + error);
                        }
                    }
                });
            }

            multipleReject.onclick = function() {
                multiRequest(this, 'reject');
            }

            multipleAccept.onclick = function() {
                multiRequest(this, 'accept');
            }
        });
    </script>
</body>

</html>