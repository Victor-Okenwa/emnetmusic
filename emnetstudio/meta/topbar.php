<!-- Topbar -->
<nav class="navbar navbar-expand navbar-dark bg-black topbar mb-4 fixed-top">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    <?php if ($nums_creators > 0) { ?>
        <a href="../index.php" class="btn text-light border-1 border-light"><i class="fa fa-home"></i></a>

        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">

        <?php } else {
        ?>
            <a href="../index.php" class="btn text-light border-1 border-light"><i class="fa fa-arrow-left"></i></a>
        <?php  } ?>

        <!-- Nav Item - User Information -->
        <?php
        if (!isset($_SESSION['ID'])) {
        ?>
            <a href="./login.php" class="login-signin btn text-white-50 border-success rounded-pill">Login /
                Sigin</a>

        <?php } else { ?>
            <div class="em-user nav-item border-white">
                <a class="nav-link mk-flex btn text-light" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small user-name mk-text-color2 text-lowercase"><?php (strlen($nickname) > 20) ? $subname = substr($nickname, 0, 20) . '...' : $subname = $nickname;
                                                                                                                        echo $subname; ?>
                    </span>
                    <?php if ($img == 0) { ?>
                        <img class="user-icon rounded-circle" src="../images/profile.png" width="30px" height="30px">
                    <?php } else { ?>
                        <img class="user-icon rounded-circle" width="30px" height="30px" src="../userprofiles/<?php echo $img ?>" alt="your profile">
                    <?php } ?>
                </a>
                <!-- Dropdown - User Information -->

            </div>
        <?php } ?>

        </ul>

</nav>
<!-- End of Topbar -->