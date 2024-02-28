<?php
require_once "./assets/access.php";

$query_users = $conn->query("SELECT * FROM users");
$num_rows_users = mysqli_num_rows($query_users);

$query_audio = $conn->query("SELECT * FROM songs");
$num_rows_audio = mysqli_num_rows($query_audio);

$query_requests = $conn->query("SELECT * FROM requests where status = '0'");
$num_rows_requests = mysqli_num_rows($query_requests);

$query_artists = $conn->query("SELECT * from creators");
$num_rows_artists = mysqli_num_rows($query_artists);

$query_male = $conn->query("SELECT * FROM users where gender = 'male' ");
$query_female = $conn->query("SELECT * FROM users where gender = 'female' ");
$query_other = $conn->query("SELECT * FROM users where gender = 'other'");

$num_rows_male = mysqli_num_rows($query_male);
$num_rows_female = mysqli_num_rows($query_female);
$num_rows_other = mysqli_num_rows($query_other);

$male_percent = floor($num_rows_male / ($num_rows_users) * 100);
$female_percent = floor($num_rows_female / ($num_rows_users) * 100);
$other_percent = floor($num_rows_other / ($num_rows_users) * 100);

$query_streams = $conn->query("SELECT stream_id FROM streams")->num_rows;
$query_playlists = $conn->query("SELECT playlist_id FROM playlists")->num_rows;
$query_likes = $conn->query("SELECT like_id FROM likes")->num_rows;
$query_chats = $conn->query("SELECT chat_id FROM chats")->num_rows;
$query_blocks = $conn->query("SELECT block_id FROM blocks")->num_rows;
$query_clicks = $conn->query("SELECT click_id FROM clicks")->num_rows;
$query_artists_rl = $conn->query("SELECT artist_id FROM creators WHERE record_label != '' ")->num_rows;

?>
<!DOCTYPE html>
<html lang="en" id="index">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "./assets/vendors.html" ?>
    <script src="./js/chart.js"></script>
</head>

<body>

    <?php require_once "./assets/navbar.php" ?>

    <div id="top_page"></div>

    <main class="conatiner-fluid">

        <div class="records">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-sm-6 pb-3">
                        <div class="card text-white bg-primary rounded-sm">
                            <div class="card-body">
                                <div class="fa-2x mk-abel"><?= shortenDigits($num_rows_users) ?></div>
                                <div class="text-capitalize">Users</div>
                            </div>
                            <div class="card-footer"><a href="users.php" class="text-light btn">View records</a></div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 pb-3">
                        <div class="card text-white bg-warning rounded-sm">
                            <div class="card-body">
                                <div class="fa-2x mk-abel"><?= shortenDigits($num_rows_artists) ?></div>
                                <div class="text-capitalize">Artists</div>
                            </div>
                            <div class="card-footer"><a href="artists.php" class="text-light btn">View records</a></div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 pb-3">
                        <div class="card text-white bg-secondary rounded-sm">
                            <div class="card-body">
                                <div class="fa-2x mk-abel"><?= shortenDigits($num_rows_requests) ?></div>
                                <div class="text-capitalize">requests</div>
                            </div>
                            <div class="card-footer"><a href="requests.php" class="text-light btn">View records</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 pb-3">
                        <div class="card text-white bg-info rounded-sm">
                            <div class="card-body">
                                <div class="fa-2x mk-abel"><?= shortenDigits($num_rows_audio) ?></div>
                                <div class="text-capitalize">audios</div>
                            </div>
                            <div class="card-footer"><a href="audios.php" class="text-light btn">View records</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="traffic mt-5">
            <div class="container">

                <div class="gender-chart container mt-5">
                    <canvas id="genderChart" style="width: 80%; height:300px;"></canvas>
                </div>

                <div class="activity-details d-flex flex-column align-items-center mt-5">
                    <h4>Activities</h4>

                    <div class="row bg-dark text-white rounded-sm">

                        <div class="col-lg-4 col-md-6 col-sm-12 py-3">
                            <strong class="text-success" style="font-size: 18px">Stream details</strong>

                            <div class="">
                                <b>Total streams:</b>
                                <div><?= shortenDigits($query_streams) ?></div>
                            </div>

                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12 py-3">
                            <strong class="text-danger" style="font-size: 18px">Playlists details</strong>

                            <div>
                                <b>Total adds:</b>
                                <div><?= shortenDigits($query_playlists) ?></div>
                            </div>

                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12 py-3">
                            <strong class="text-info" style="font-size: 18px">Like details</strong>

                            <div>
                                <b>Total likes:</b>
                                <div><?= shortenDigits($query_likes) ?></div>
                            </div>

                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12 py-3">
                            <strong class="text-secondary" style="font-size: 18px">Chat details</strong>

                            <div>
                                <b>Total chats:</b>
                                <div><?= shortenDigits($query_chats) ?></div>
                            </div>

                            <div>
                                <b>Total blocks:</b>
                                <div><?= shortenDigits($query_blocks) ?></div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12 py-3">
                            <strong class="text-primary" style="font-size: 18px">Advert details</strong>

                            <div>
                                <b>Total clicks:</b>
                                <div><?= shortenDigits($query_clicks) ?></div>
                            </div>

                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12 py-3">
                            <strong class="text-warning" style="font-size: 18px">RL/EH</strong>

                            <div>
                                <b>Total Number of RL/EH:</b>
                                <div>20,000,000</div>
                            </div>

                            <div>
                                <b>Total Number of Artist with RL:</b>
                                <div><?= shortenDigits($query_artists_rl) ?></div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="tables mt-5">
                    <div class="table-responsive">
                        <h4 class="text-center">Top 5 Creators with highest posts</h4>

                        <table class="table table-success table-striped table border mb-0">
                            <thead class="table-light fw-semibold">
                                <tr class="align-middle text-center">
                                    <th>
                                        <div class="fa fa-music"></div>
                                    </th>
                                    <th>
                                        <i class="fa fa-image"></i>
                                    </th>
                                    <th><i class="fa fa-user"></i></th>
                                    <th><i class="fa fa-globe"></i></th>
                                    <th><i class="fa fa-envelope"></i></th>
                                    <th><i class="fa fa-joint"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sql_creators = $conn->query("SELECT * FROM users INNER JOIN creators ON users.user_id = creators.user_id order by creators.songs desc Limit 5");
                                while ($data_creator = mysqli_fetch_assoc($sql_creators)) {
                                ?>
                                    <tr class="align-middle">
                                        <td> <?= $data_creator['songs']  ?></td>
                                        <td> <img src="../userprofiles/<?= $data_creator['user_profile']  ?>" width="40px" height="40px"></td>
                                        <td> <?= $data_creator['nickname']  ?></td>
                                        <td> <?= $data_creator['country']  ?></td>
                                        <td> <?= $data_creator['email']  ?></td>
                                        <td> <?=
                                                getDateTimeDiff($data_creator["created_on"]) ?></td>
                                    </tr>
                                <?php
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>

                    <div class=" table-responsive mt-5">
                        <h4 class="text-center">Top 5 Songs with highest streams</h4>

                        <table class="table border-bottom-primary mb-0">
                            <thead class="bg-primary text-light table-group-divider table-active fw-semibold">
                                <tr class="align-middle text-center">

                                    <th><i class="fa fa-stream"></i></th>
                                    <th><i class="fa fa-image"></i></th>
                                    <th>Song name</th>
                                    <th><i class="fa fa-user"></i></th>
                                    <th><i class="fa fa-user-plus"></i></th>
                                    <th>Genre</th>
                                    <th><i class="fa fa-joint"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sql_streams = $conn->query("SELECT * FROM songs  order by streams desc Limit 5");
                                while ($data_streams = mysqli_fetch_assoc($sql_streams)) {
                                ?>
                                    <tr class="align-middle">
                                        <td> <?= $data_streams['streams']  ?></td>
                                        <td> <img src="../audio_thumbnails/<?= $data_streams['thumbnail']  ?>" width="40px" height="40px"></td>
                                        <td> <?= $data_streams['song_name']  ?></td>
                                        <td> <?= $data_streams['artist']  ?></td>
                                        <td> <?= ($data_streams['creator_id'] != 0) ? $data_streams['creator_name'] : $data_streams['admin_name'] ?></td>
                                        <td> <?= $data_streams['genre']  ?></td>
                                        <td> <?=
                                                getDateTimeDiff($data_streams["created_on"]) ?></td>
                                    </tr>
                                <?php
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>

                    <div class=" table-responsive mt-5">
                        <h4 class="text-center">Top 5 Songs with highest adds to playlists</h4>

                        <table class="table border mb-0">
                            <thead class="bg-secondary text-light table-group-divider table-active fw-semibold">
                                <tr class="align-middle text-center">
                                    <th><i class="fa fa-plus-square"></i></th>
                                    <th>
                                        <i class="fa fa-image"></i>
                                    </th>
                                    <th>Song name</th>
                                    <th><i class="fa fa-user"></i></th>
                                    <th><i class="fa fa-user-plus"></i></th>
                                    <th>Genre</th>
                                    <th><i class="fa fa-joint"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sql_streams = $conn->query("SELECT * FROM songs  order by adds desc Limit 5");
                                while ($data_streams = mysqli_fetch_assoc($sql_streams)) {
                                ?>
                                    <tr class="align-middle">
                                        <td> <?= $data_streams['adds']  ?></td>
                                        <td> <img src="../audio_thumbnails/<?= $data_streams['thumbnail']  ?>" width="40px" height="40px"></td>
                                        <td> <?= $data_streams['song_name']  ?></td>
                                        <td> <?= $data_streams['artist']  ?></td>
                                        <td> <?= ($data_streams['creator_id'] != 0) ? $data_streams['creator_name'] : $data_streams['admin_name'] ?></td>
                                        <td> <?= $data_streams['genre']  ?></td>
                                        <td> <?=
                                                getDateTimeDiff($data_streams["created_on"]) ?></td>
                                    </tr>
                                <?php
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>

    </main>

    <?php require_once "./assets/footer.html" ?>
    <?php require_once "./assets/scripts.html" ?>

    <script>
        const genderChart = document.getElementById('genderChart');

        function createChart(canvas, values, labels, chartType) {
            const ctx = canvas.getContext('2d');
            const myChart = new Chart(ctx, {
                type: chartType,
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Registered account',
                        data: values,
                        backgroundColor: ['#FF6384', '#36A2EB'],
                        hoverBackgroundColor: ['#c41239', '#1680c7'],
                        borderWidth: 1,
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        createChart(genderChart, [<?= $num_rows_male ?>, <?= $num_rows_female ?>], ['Male', 'Female', ], 'bar');
    </script>

</body>

</html>