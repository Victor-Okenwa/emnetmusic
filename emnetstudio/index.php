<?php
include "access.php";
$num_rows_male = 0;
$num_rows_female = 0;
$num_rows_other = 0;
if ($nums_creators > 0) {
    $query_all_songs = mysqli_query($conn, "SELECT * FROM songs");
    $query_songs = mysqli_query($conn, "SELECT * FROM songs WHERE creator_id = $unique_id");
    $query_song_id = mysqli_query($conn, "SELECT song_id FROM songs WHERE creator_id = $unique_id");
    $query_playlists = mysqli_query($conn, "SELECT * FROM playlists where poster_id = $unique_id");

    $query = mysqli_query($conn, "SELECT * FROM users");
    $num_rows_all_songs = mysqli_num_rows($query_all_songs);
    $num_rows_songs = mysqli_num_rows($query_songs);
    $num_rows_playlists = mysqli_num_rows($query_playlists);

    // Copy from here -->
    $query_followers = mysqli_query($conn, "SELECT * from users WHERE user_id = '{$unique_id}'");
    $fetch_num_followers = mysqli_fetch_assoc($query_followers);
    $num_followers = $fetch_num_followers['followers'];
    // Copy to here -->

    $t_streams = 0;
    while ($fetch_song = mysqli_fetch_array($query_songs)) {
        $t_streams += $fetch_song['streams'];

        // $query_f_playlists = mysqli_query($conn, "SELECT * from playlists WHERE song_id = {$song_ids2}");
        // echo $num_rows_playlists = mysqli_num_rows($query_f_playlists);
        // foreach ($fetch_song as $idp_id) {
        //     if (in_array($idp_id, $song_ids)) {
        //         foreach ($song_ids as $val) {
        //         }
        //     }
        // }
        // $sql_get2 = mysqli_query($conn, "SELECT* FROM playlists INNER JOIN users ON playlists.uniqueID = admintable.unique_id WHERE commentID = $comID");
    }

    $num_rows_male = 0;
    $num_rows_female = 0;
    $num_rows_other = 0;
    while ($fetch_playlist = mysqli_fetch_array($query_playlists)) {
        $query_users_gen = mysqli_query($conn, "SELECT * FROM users WHERE user_id = {$fetch_playlist['user_id']}");
        // echo $fetch_playlist['user_id'];
        // $query_playlists_female = mysqli_query($conn, "SELECT * FROM users WHERE user_id = {$fetch_playlist['user_id']} and gender = 'female' ");
        // $query_playlists_other = mysqli_query($conn, "SELECT * FROM users WHERE user_id = {$fetch_playlist['user_id']} and gender = 'other' ");

        while ($lay_playlist = mysqli_fetch_assoc($query_users_gen)) {
            if ($lay_playlist['gender'] == 'male') {
                $num_rows_male += 1;
            } elseif ($lay_playlist['gender'] == 'female') {
                $num_rows_female += 1;
            } else {
                $num_rows_other += 1;
            }
        }
        // $sql_get2 = mysqli_query($conn, "SELECT* FROM playlists INNER JOIN users ON playlists.uniqueID = admintable.unique_id WHERE commentID = $comID");
    }

    if ($num_rows_songs > 0) {
        $posts_percent = round(($num_rows_songs / 86400) * 100, 3);
    } else {
        $posts_percent = 0;
    }
    if ($t_streams  > 0) {
        $stream_percent = round(($t_streams / 86400) * 100, 3);
    } else {
        $stream_percent = 0;
    }

    if ($num_rows_playlists  > 0) {
        $add_percent = round(($num_rows_playlists / 86400) * 100, 3);
    } else {
        $add_percent = 0;
    }
    // if($t_streams  > 0){
    //     $pay_stream_percent = round(($t_streams / 2000) * 100, 3);
    // }else{
    //     $pay_stream_percent = 0;
    // }
    // if($num_rows_playlists  > 0){
    //     $pay_add_percent = round(($num_rows_playlists / 1000) * 100, 3);
    // }else{
    //     $pay_add_percent = 0;
    // }

    // $num_rows_male;
    // $num_rows_female;
    // $num_rows_other;

    // $add_percent = floor(($num_rows_female / $num_rows) * 100);
    if ($num_rows_male > 0 && $num_rows_playlists > 0) {
        $male_percent = floor(($num_rows_male / $num_rows_playlists) * 100);
    } else {
        $male_percent = 0;
    }
    if ($num_rows_female > 0 && $num_rows_playlists > 0) {
        $female_percent = floor(($num_rows_female / $num_rows_playlists) * 100);
    } else {
        $female_percent = 0;
    }
    if ($num_rows_other > 0 && $num_rows_playlists > 0) {
        $other_percent = floor(($num_rows_other / $num_rows_playlists) * 100);
    } else {
        $other_percent = 0;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php include "./meta/header.php" ?>

</head>

<body id="page-top">

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
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php
                include "./meta/topbar.php";

                if ($nums_creators < 1) { ?>

                    <div class="container">

                        <div class="alert-message show">
                            <div class="sec-bg"></div>
                            <div class="info-text" style="width: 100%">
                                <?php
                                $request_status = $fetch_request['status'];

                                switch ($request_status) {
                                    case "":
                                        echo  '';
                                        break;
                                    case 0:
                                        echo  '
                                            <div class=" alert-info rounded-sm" id="info">
                                                Your request has been received; we will send you a message shortly<small> &bull; <br> 
                                                Refresh the page incase of new information</small>
                                            </div>';
                                        break;
                                        // case 1:
                                        //     echo  '<div class="info bg-info p-2" id="info">Your request has been accepted 
                                        //     <a href="payment.php">Click here</a> to pay
                                        //     <div class="small text-danger">' . requestExpiration($fetch_request['date'])  . '</div>
                                        //     </div>';
                                        //     break;
                                    case 2:
                                        break;

                                    default:
                                        "Please send a request";
                                }
                                ?>

                                <?php if ($request_status == "") { ?>
                                    <p class="mb-3">Do you want to be a creator?</p>
                                    <button class="btn btn-check btn-light mb-3" data-toggle="modal" data-target="#policyModal">Read
                                        our policy</button>
                                <?php } ?>
                            </div>
                            <br>
                        </div>

                    </div>
                    <!-- Begin Page Content -->
                <?php } else { ?>

                    <div class="container-fluid mt-5 pt-5">
                        <!-- Copy from here -->
                        <!-- Total songs -->
                        <div class="row justify-content-center">
                            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                                <div class="card border-left-info shadow h-100 py-2" style="background: #333; border: none">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Audio posted
                                                </div>
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col-auto">
                                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-200"><?= subNumber($num_rows_songs) ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-music fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Followers -->
                            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2" style="background: #333; border: none">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    Followers</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-400"><?= subNumber($num_followers) ?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- total streams and adds -->
                            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2" style="background: #333; border: none">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    total streams/adds</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-400"><?= subNumber($t_streams) ?> / <?= subNumber($num_rows_playlists) ?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-upload fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Content Row -->
                        </div>


                        <!-- Pie Chart -->
                        <div class="row justify-content-center">
                            <div class="col-lg-6 col-md-12">
                                <div class="card shadow mb-4" style="background: #333; border: none">
                                    <!-- Favourites -->
                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background: #000">
                                        <h6 class="m-0 font-weight-bold text-light">Favorites Adds by gender</h6>
                                    </div>
                                    <!-- Card Body -->
                                    <div class="card-body">
                                        <div class="chart-pie pt-4 pb-2 text-light">
                                            <canvas id="myGenderChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Copy to here -->


                        <div class="row mb-3">
                            <div class="col-md-6 col-sm-12 border-right py-3 overflow-auto" style="background: #000">
                                <h4 style="color: #0441ff"> My top 5 stream</h4>
                                <table class="table col-12 w-100">
                                    <thead class="text-light" style="background: linear-gradient(00deg, #0741ff, rgb(0, 55, 158));">
                                        <tr>
                                            <th>
                                                <div class="fa fa-image"></div> Song
                                            </th>
                                            <th>Artist(s)</th>
                                            <th>Genre</th>
                                            <th>streams</th>
                                        </tr>
                                        </th>
                                    <tbody class="text-light">
                                        <?php $query_streams = $conn->query("SELECT * FROM songs WHERE creator_id = $unique_id order by streams desc limit 5");
                                        if ($query_streams->num_rows > 0) {
                                            while ($row_stream = $query_streams->fetch_assoc()) {
                                        ?>
                                                <tr>
                                                    <td>
                                                        <img src="../audio_thumbnails/<?= $row_stream['thumbnail'] ?>" width="30" alt="">
                                                        <?= $row_stream['song_name'] ?>
                                                    </td>
                                                    <td>
                                                        <?= $row_stream['artist'] ?>
                                                    </td>
                                                    <td>
                                                        <?= $row_stream['genre'] ?>
                                                    </td>
                                                    <td>
                                                        <?= subNumber($row_stream['streams']) ?>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6 col-sm-12 py-3 overflow-auto" style="background: #000">
                                <h4 style="color: #ffb007"> My top 5 adds(likes)</h4>
                                <table class="table col-12 w-100">
                                    <thead class="text-light" style="background: linear-gradient(00deg, #ffb007, rgb(212, 148, 9));">
                                        <tr>
                                            <th>
                                                <div class="fa fa-image"></div> Song
                                            </th>
                                            <th>Artist(s)</th>
                                            <th>Genre</th>
                                            <th>Adds</th>
                                        </tr>
                                        </th>
                                    <tbody class="text-light">
                                        <?php $query_adds = $conn->query("SELECT * FROM songs WHERE creator_id = $unique_id order by adds desc limit 5");
                                        if ($query_adds->num_rows > 0) {
                                            while ($row_adds = $query_adds->fetch_assoc()) {
                                        ?>
                                                <tr>
                                                    <td>
                                                        <img src="../audio_thumbnails/<?= $row_adds['thumbnail'] ?>" width="30" alt="">
                                                        <?= $row_adds['song_name'] ?>
                                                    </td>
                                                    <td>
                                                        <?= $row_adds['artist'] ?>
                                                    </td>
                                                    <td>
                                                        <?= $row_adds['genre'] ?>
                                                    </td>
                                                    <td>
                                                        <?= subNumber($row_adds['adds']) ?>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Content Row -->
                        <div class="row">
                            <div class="col-md-6 col-sm-12 border-right py-3 overflow-auto" style="background: #000">
                                <h4 style="color: #ccc"> My top posts</h4>
                                <table class="table col-12 w-100">
                                    <thead class="text-dark" style="background: linear-gradient(00deg, #fafafa, #ccc);">
                                        <tr>
                                            <th>
                                                <div class="fa fa-image"></div> Song
                                            </th>
                                            <th>Artist(s)</th>
                                            <th>Genre</th>
                                            <th>str/ads</th>
                                        </tr>
                                        </th>
                                    <tbody class="text-light">
                                        <?php
                                        $query_both = $conn->query("SELECT * FROM songs WHERE creator_id = $unique_id order by streams and adds desc limit 5");
                                        if ($query_both->num_rows > 0) {
                                            while ($row_both = $query_both->fetch_assoc()) {
                                        ?>
                                                <tr>
                                                    <td>
                                                        <img src="../audio_thumbnails/<?= $row_both['thumbnail'] ?>" width="30" alt="">
                                                        <?= $row_both['song_name'] ?>
                                                    </td>
                                                    <td>
                                                        <?= $row_both['artist'] ?>
                                                    </td>
                                                    <td>
                                                        <?= $row_both['genre'] ?>
                                                    </td>
                                                    <td>
                                                        <?= subNumber($row_both['streams']) ?> / <?= subNumber($row_both['adds']) ?>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Content Column -->
                            <div class="col-md-6 col-sm-12 mb-4">

                                <!-- Project Card Example -->
                                <div class="card shadow mb-4" style="background: #000">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-black-50 fw-bolder">Activity</h6>
                                    </div>
                                    <div class="card-body">
                                        <h4 class="small font-weight-bold">Posts per day <span class="float-right"><?= $posts_percent ?>%</span></h4>
                                        <div class="progress mb-4">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: <?= $posts_percent ?>%" aria-valuenow="<?= $posts_percent ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <h4 class="small font-weight-bold">Streams per day <span class="float-right"><?= $stream_percent ?>%</span></h4>
                                        <div class="progress mb-4">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $stream_percent ?>%" aria-valuenow="<?= $stream_percent ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <h4 class="small font-weight-bold">Adds per day <span class="float-right"><?= $add_percent ?>%</span></h4>
                                        <div class="progress mb-4">
                                            <div class="progress-bar" role="progressbar" style="width: <?= $add_percent ?>%" aria-valuenow="<?= $add_percent ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <!-- <p class="detail small fw-bolder">Pay in target (2000 streams and/or 1000 adds)</p>
                                        <h4 class="small font-weight-bold">Stream target<span class="float-right"><?= $pay_stream_percent ?>%</span></h4>
                                        <div class="progress mb-4">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: <?= $pay_stream_percent ?>%" aria-valuenow="<?= $pay_stream_percent ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <h4 class="small font-weight-bold">Adds target <span class="float-right"><?= $pay_add_percent ?>%</span></h4>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: <?= $pay_add_percent ?>%" aria-valuenow="<?= $pay_add_percent ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div> -->
                                    </div>
                                </div>

                            </div>


                        </div>

                    </div>
                <?php } ?>

                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <?php include "./meta/footer.php" ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Policy Modal-->
    <div class="modal fade" id="policyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 1000000;">
        <div class="modal-dialog w-100">
            <div class=" modal-content modal-fullscreen-lg-down" style="width: 100% !important;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Our Policy</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>&bull; &nbsp; Do not post any other audio that is not yours. <br>
                        <span class="mt-1 ml-3">&dash; This could get you barred.</span>
                    </p>
                    <p>&bull; &nbsp; Audio or thumbnail repost are not allowed. <br>
                        <span class="mt-1 ml-3">&dash; This could get you suspended.</span>
                    </p>

                    <form method="post" id="creatorForm" class="d-flex flex-column">
                        <input type="number" name="user_id" value="<?= $unique_id ?>" hidden>
                        <input type="text" name="nickname" value="<?= $nickname  ?>" hidden>
                        <input type="email" name="email" value="<?= $email  ?>" hidden>
                        <button type="button" href="#" id="creatorLink" class="btn btn-primary text-light d-none animated--grow-in">Become an artist</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <input id="acceptTerms" class="btn btn-ghost-secondary ml-3" style="scale: 1.4;" type="checkbox" required>
                    <label for="policy" class="fw-semibold small">Accept terms & conditions</label>
                </div>
            </div>
        </div>
    </div>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php include "./meta/scripts.php" ?>



    <script>
        const acceptTerms = document.getElementById("acceptTerms");
        const creatorLink = document.getElementById("creatorLink");
        const creatorForm = document.getElementById("creatorForm");
        const info = document.getElementById("info");

        acceptTerms.addEventListener('change', () => {
            if (acceptTerms.checked) {
                creatorLink.classList.remove('d-none');
            } else {
                creatorLink.classList.add('d-none');
            }
        })


        creatorForm.onsubmit = (e) => {
            e.preventDefault();
        }



        let xhr = new XMLHttpRequest();

        creatorLink.onclick = () => {
            xhr.open('POST', 'send_request.php', true);
            xhr.onload = () => {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let data = xhr.response;
                        if (data == "Your request has been received!") {
                            alert(data);
                            window.location = window.location;
                        } else {
                            if (data != "") {

                            }
                        }
                    }
                }
            }
            xhr.onerror = function() {
                messageInfo("Request error...");
            }
            // we have to send the form data through ajax to php
            let formData = new FormData(creatorForm);
            xhr.send(formData); //sending the form to php
        }


        function messageInfo(info) {
            info.textContent = `${info}`;
        }
    </script>

    <!-- chart canvas design -->
    <script>
        var ctx = document.getElementById("myGenderChart");
        var myPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ["Male", "Female", "Other"],
                datasets: [{
                    data: [<?= $num_rows_male ?>, <?= $num_rows_female ?>, <?= $num_rows_other ?>],
                    backgroundColor: ['#4e73df', 'pink', '#36b9cc'],
                    hoverBackgroundColor: ['#2e59d9', 'deeppink', '#2c9faf'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: true
                },
                cutoutPercentage: 80,
            },
        });
    </script>

</body>

</html>