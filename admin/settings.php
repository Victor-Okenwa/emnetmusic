<?php require "./assets/access.php";
if ($data_admin['admin_rank'] !== "super admin") {
    die;
} ?>
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
                <h5 class="text-center">Set Admin Previledges</h5>
            </div>
            <div class="card-body">
                <div style="width: 100%; height: 70px">
                    <div id="output" class="d-none"></div>
                </div>
                <form>
                    <div class="form-group row">
                        <div class="custom-control custom-switch col-md-6 col-sm-12">
                            <input class="custom-control-input" name="creatorSus" id="creatorSus" type="checkbox" value="<?= $data_setting['creator_sus'] == 1 ? '1' : '0' ?>" <?= $data_setting['creator_sus'] == 1 ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="creatorSus">Allow admins to suspend creators</label>
                        </div>

                        <div class="custom-control custom-switch col-md-6 col-sm-12">
                            <input class="custom-control-input" name="creatorDel" id="creatorDel" type="checkbox" value="<?= $data_setting['creator_delete'] == 1 ? '1' : '0' ?>" <?= $data_setting['creator_delete'] == 1 ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="creatorDel">Allow admins to delete creators' account</label>
                        </div>
                    </div>

                    <div class="form-group row my-3">
                        <div class="custom-control custom-switch col-md-6 col-sm-12">
                            <input class="custom-control-input" name="requestAccept" id="requestAccept" type="checkbox" value="<?= $data_setting['request_accpt'] == 1 ? '1' : '0' ?>" <?= $data_setting['request_accpt'] == 1 ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="requestAccept">Allow admins to accept requests</label>
                        </div>

                        <div class="custom-control custom-switch col-md-6 col-sm-12">
                            <input class="custom-control-input" name="requestReject" id="requestReject" type="checkbox" value="<?= $data_setting['request_rej'] == 1 ? '1' : '0' ?>" <?= $data_setting['request_rej'] == 1 ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="requestReject">Allow admins to reject requests</label>
                        </div>
                    </div>

                    <div class="form-group row my-3">
                        <!-- <div class="custom-control custom-switch col-md-6 col-sm-12">
                            <input class="custom-control-input" name="requestWait" id="requestWait" type="checkbox" value="<?= $data_setting['request_wait'] == 1 ? '1' : '0' ?>" <?= $data_setting['request_wait'] == 1 ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="requestWait">Allow admins to "Back wait" requests</label>
                        </div> -->
                        <div class="custom-control custom-switch col-md-6 col-sm-12">
                            <input class="custom-control-input" name="creatorReinstate" id="creatorReinstate" type="checkbox" value="<?= $data_setting['creator_reinstate'] == 1 ? '1' : '0' ?>" <?= $data_setting['creator_reinstate'] == 1 ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="creatorReinstate">Allow admins to reinstate suspended creators</label>
                        </div>

                        <div class="custom-control custom-switch col-md-6 col-sm-12">
                            <input class="custom-control-input" name="createAudio" id="createAudio" type="checkbox" value="<?= $data_setting['crt_audio'] == 1 ? '1' : '0' ?>" <?= $data_setting['crt_audio'] == 1 ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="createAudio">Allow admins to post audios</label>
                        </div>

                    </div>
                    <div class="form-group row my-3">

                        <div class="custom-control custom-switch col-md-6 col-sm-12">
                            <input class="custom-control-input" name="autoAccpt" id="autoAcct" type="checkbox" value="<?= $data_setting['auto_acct'] == 1 ? '1' : '0' ?>" <?= $data_setting['auto_acct'] == 1 ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="autoAcct">Auto Accept requests</label>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </main>

    <?php include "./assets/footer.html" ?>
    <?php include "./assets/scripts.html" ?>

    <script>
        $(document).ready(() => {
            const output = document.getElementById("output"),
                creatorSus = document.getElementById("creatorSus"),
                creatorDel = document.getElementById('creatorDel'),
                requestAccept = document.getElementById('requestAccept'),
                requestReject = document.getElementById('requestReject'),
                // requestWait = document.getElementById('requestWait'),
                createAudio = document.getElementById('createAudio'),
                autoAcct = document.getElementById('autoAcct');

            // checkboxes.forEach(checkbox => {
            //     checkbox.onchange = () => {
            //         if (checkbox.value == 0) {
            //             checkbox.value = 1;
            //         } else {
            //             checkbox.value = 0;
            //         }
            //     }
            // });

            function sendUpdate(checkbox, fieldName) {
                if (checkbox.value == 0) {
                    checkbox.value = 1;
                } else {
                    checkbox.value = 0;
                }
                $.ajax({
                    url: "api/settings.php",
                    method: 'POST',
                    timeout: 5000,
                    data: {
                        type: fieldName,
                        value: checkbox.value,
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            outputMessage(output, `Updated ${fieldName}`, 3500, 'success');
                        } else {
                            outputMessage(output, `${response.message}`, 3500, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        if (status == 'timeout') {
                            outputMessage(output, 'Request timed out', 3500, 'error');
                        } else {
                            outputMessage(output, 'An error occured' + error, 3500, 'error');
                        }
                    }
                });
            }

            creatorSus.onchange = function() {
                sendUpdate(creatorSus, 'creator_suspend');
            }
            creatorDel.onchange = function() {
                sendUpdate(creatorDel, 'creator_delete');
            }

            requestAccept.onchange = function() {
                sendUpdate(requestAccept, 'creator_accept');
            }

            requestReject.onchange = function() {
                sendUpdate(requestReject, 'creator_reject');
            }

            // requestWait.onchange = function() {
            // sendUpdate(creatorSus, 'creator_wait');
            // }

            createAudio.onchange = function() {
                sendUpdate(createAudio, 'create_audio');
            }

            creatorReinstate.onchange = function() {
                sendUpdate(creatorReinstate, 'creator_reinstate');
            }

            autoAcct.onchange = function() {
                sendUpdate(autoAcct, 'auto_accept');
            }
        });
    </script>
</body>

</html>