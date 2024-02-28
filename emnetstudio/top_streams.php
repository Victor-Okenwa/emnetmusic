<?php
include "access.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php 
include "./meta/header.php" ?>
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>
<body>

<body id="page-top" class="bg-light overflow-auto sidebar-toggled">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php if ($nums_creators > 0) {

            include "./meta/sidebar.php";
        ?>

        <?php  } else {
        } ?>
              <!-- Content Wrapper -->
              <div id="content-wrapper" class="d-flex flex-column ">


<!-- Main Content -->
                    <div id="content" class="bg-dark opacity-25">

                    <?php include "./meta/topbar.php" ?>

                        <div class="container mt-5 pt-5">
                            <h1 class="text-center" style="filter: drop-shadow(0 0 4px #219);">Top Ten streams on Emnet</h1>
                            <table class="table-responsive table table-striped text-light" id="dataTable" cellspacing="0">
                                    <thead class="text-light" style="background: linear-gradient(00deg ,#000000, rgb(28, 29, 28));">
                                        <tr>
                                            <th>Thumbnail</th>
                                            <th>Artist</th>
                                            <th>Poster</th>
                                            <th>Streams</th>
                                            <th>Genre</th>
                                            <th>Posted</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                            <?php
                            $num_on_top = 0;
                            $i = 0;
                            $top = "";

                                $query_streams = mysqli_query($conn, "SELECT * from songs order by streams desc LIMIT 10");
                                while($data_song = mysqli_fetch_assoc($query_streams)){
                                   if($data_song['creator_id'] == $unique_id){
                                       $num_on_top++;
                                   }
                                   $i++;
                                   if($data_song['admin_id'] != 0){
                                       $query_admin_profile = mysqli_query($conn, "SELECT * FROM admin WHERE admin_id = {$data_song['admin_id']}");
                                       $data_admin_profile = mysqli_fetch_assoc($query_admin_profile);
                                    }else{
                                       $query_user_profile = mysqli_query($conn, "SELECT * FROM users WHERE user_id = {$data_song['creator_id']}");
                                       $data_user_profile = mysqli_fetch_assoc($query_user_profile);
                                   }
                                   ?>
                                   <tr style="<?= $unique_id == $data_song['creator_id'] ? "background: linear-gradient(00deg, #0e072e, rgb(15, 1, 63));" : "" ?>">
                                            <td>
                                                <?php 
                                                    if($i == 1 && $unique_id == $data_song['creator_id']){
                                                        $top = "You are toping the list";
                                                    }
                                                ?>
                                                <span><?= $i?></span>
                                                <img src="../audio_thumbnails/<?= $data_song['thumbnail'] ?>" class="rounded-sm" width="100%" height="50" alt="">
                                            </td>
                                            <td>
                                              <?= $data_song['song_name'] ?>
                                            </td>
                                            <td>
                                            &bull; <?= $data_song['admin_id'] != 0 ? $data_admin_profile['nickname'] : $data_user_profile['nickname'] ?>
                                            </td>
                                            <td>
                                                <?= subNumber($data_song['streams'])?>
                                            </td>
                                            <td>
                                                <?= $data_song['genre']?>
                                            </td>
                                            <td>
                                                <?= getDateTimeDiff($data_song['created_on'])?>
                                            </td>
                                   </tr>
                                               
                                   
                                   <?php
                                }
                            ?>
                            </div>
                            <?php
                            echo  $num_on_top > 0 ? "<h6 class='text-center text-light''> {$num_on_top} of your posts made the list</h6>" : "<h6  class='text-center text-light'> {$num_on_top} of your posts made the list</h6>";
                        if($top){
                            echo "<p class='text-center text-warning'>$top</p>";
                        }
                            ?>
                            </tbody>
                            </table>
                    </div>
                    <?php include "./meta/footer.php" ?>

</div>
</div>
<a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php include "./meta/scripts.php" ?>
    <!-- <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    Page level custom scripts -->
    <!-- <script src="js/demo/datatables-demo.js"></script> -->
    
</body>
</html>