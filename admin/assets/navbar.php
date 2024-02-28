<header class="navigation-bar">
    <div class="topbar shadow-lg">
        <div class="first">
            <button type="button" id="navbar-toggler" class="btn btn-transparent" title="Toggle sidebar"><i class="fa fa-bars"></i></button>
            <a href="index.php"><i class="fa fa-long-arrow-alt-left"></i> Dashboard</a>
        </div>

        <div class="last">
            <div class="profile">
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                        <?php if ($admin_image !== "") {
                        ?>
                            <img src="./admin_profile/<?= $admin_image ?>" class="" alt="Profile">
                        <?php
                        } else {
                        ?>
                            <i class="fa fa-user"></i>
                        <?php
                        }
                        ?>
                    </button>

                    <div class="dropdown-menu dropdown-menu-left" style="max-width: 60px; margin-left: -50px">
                        <a class=" dropdown-item" href="profile.php"><i class="fa fa-user"></i> <span>Profile</span></a>
                        <a class="dropdown-item" href="settings.php"><i class="fa fa-cogs"></i> <span>Settings</span></a>
                        <div class="dropdown-divider"></div>
                        <button type="button" id="logout" class="dropdown-item btn">Logout</a>
                    </div>
                </div>

            </div>

            <div class="notification">
                <a href="requests.php" class="btn btn-transparent"><i class="fa fa-bell"></i></a>
                <small id="requestCounter" class="badge badge-counter bg-primary text-light d-none"></small>
            </div>

        </div>
    </div>

    <div class="sidebar">
        <ul>

            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="nav-icon fa fa-tachometer-alt "></i>
                    <span class="nav-text">dashboard</span>
                </a>
            </li>

            <li class="nav-title"></li>

            <li class="nav-item">
                <a class="nav-link" href="users.php">
                    <i class="nav-icon fa fa-users "></i>
                    <span class="nav-text">users</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="artists.php">
                    <i class="nav-icon fa fa-pen"></i>
                    <span class="nav-text">artists</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="requests.php">
                    <i class="nav-icon fa fa-inbox "></i>
                    <span class="nav-text">requests</span>
                </a>
            </li>

            <li class="nav-title"></li>

            <li class="nav-item">
                <a class="nav-link" href="create_audio.php">
                    <i class="nav-icon fa fa-file-audio"></i>
                    <span class="nav-text">create audio</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="audios.php">
                    <i class="nav-icon fa fa-music"></i>
                    <span class="nav-text">audios</span>
                </a>
            </li>

            <li class="nav-title"></li>

            <li class="nav-item">
                <a class="nav-link" href="admins.php">
                    <i class="nav-icon fa fa-file-audio"></i>
                    <span class="nav-text">admins</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="ad_manager.php">
                    <i class="nav-icon fa fa-ad"></i>
                    <span class="nav-text">ad manager</span>
                </a>
            </li>
        </ul>
    </div>

</header>

<script>
    $(document).ready(() => {
        const requestCounter = document.getElementById("requestCounter");
        setInterval(() => {
            $.ajax({
                url: "api/request_count.php",
                method: "GET",

                success: function(response) {
                    if (response.status = 'found') {
                        if (requestCounter.classList.contains('d-none')) {
                            requestCounter.classList.remove('d-none');
                        }
                        requestCounter.innerText = response.message;
                    } else {
                        if (!requestCounter.classList.contains('d-none')) {
                            requestCounter.classList.add('d-none');
                        }
                        requestCounter.innerText = '';
                    }

                }
            });
        }, 2000);
    });
</script>