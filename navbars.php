<header>
    <!-- -------- upper navigation ------ -->
    <nav class="upper-navigation mk-flex navbar ambientDiv">
        <div class="first mk-flex">
            <div class="menu-btn btn mr-2 navbar-toggler" onclick="navView(this)">
                <i class="material-icons menu-icon">menu</i>
                <i class="material-icons cancel-icon">cancel</i>
            </div>

            <a href="" class=" navbar-brand logo mk-flex">
                <img src="./logos/Emnet_Logo2.png" alt="emnet">
                <span>emnet</span>
            </a>

        </div>

        <div class="last mk-flex">
            <!--  ambient selector -->
            <div class="em-mode mr-3 btn" title="theme mode" onclick="ambient(this)">
                <i class="fa fa-moon"></i>
            </div>

            <div class="em-sort">
                <div class="dropdown" id="sort-menu">
                    <button class="btn text-light" id="sort-btn" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">All Genre <i class="fas fa-caret-down"></i>
                    </button>
                    <div class="dropdown-menu" style="overflow-y: auto !important; max-height: 70vh">
                        <option class="dropdown-item" selected="" value="">All genre</option>
                        <option class="dropdown-item" value="Afro Pop">Afro Beat</option>
                        <option class="dropdown-item" value="Alternative rock">Alternative rock</option>
                        <option class="dropdown-item" value="Amapiano">Amapiano</option>
                        <option class="dropdown-item" value="Ambient music">Ambient music</option>
                        <option class="dropdown-item" value="Blues">Blues</option>
                        <option class="dropdown-item" value="Classical music">Classical music</option>
                        <option class="dropdown-item" value="Contemporary R&B">Contemporary R&B</option>
                        <option class="dropdown-item" value="Country music">Country music</option>
                        <option class="dropdown-item" value="Dance music">Dance music</option>
                        <option class="dropdown-item" value="Disco">Disco</option>
                        <option class="dropdown-item" value="Dubstep">Dubstep</option>
                        <option class="dropdown-item" value="Easy listening">Easy listening</option>
                        <option class="dropdown-item" value="Electro">Electro</option>
                        <option class="dropdown-item" value="Electronic dance music">Electronic dance music</option>
                        <option class="dropdown-item" value="Electronic music">Electronic music</option>
                        <option class="dropdown-item" value="Emo">Emo</option>
                        <option class="dropdown-item" value="Folk music">Folk music</option>
                        <option class="dropdown-item" value="Funk">Funk</option>
                        <option class="dropdown-item" value="Gospel">Gospel</option>
                        <option class="dropdown-item" value="Grunge">Grunge</option>
                        <option class="dropdown-item" value="Heavy metal">Heavy metal</option>
                        <option class="dropdown-item" value="Hip Hop">Hip hop</option>
                        <option class="dropdown-item" value="House music">House music</option>
                        <option class="dropdown-item" value="Indie rock">Indie rock</option>
                        <option class="dropdown-item" value="Industrial music">Industrial music</option>
                        <option class="dropdown-item" value="Instrumental">Instrumental</option>
                        <option class="dropdown-item" value="Jazz">Jazz</option>
                        <option class="dropdown-item" value="K-pop">K-pop</option>
                        <option class="dropdown-item" value="Latin music">Latin music</option>
                        <option class="dropdown-item" value="Latin pop">Latin pop</option>
                        <option class="dropdown-item" value="Musical theatre">Musical theatre</option>
                        <option class="dropdown-item" value="New-age music">New-age music</option>
                        <option class="dropdown-item" value="Opera">Opera</option>
                        <option class="dropdown-item" value="Pop music">Pop music</option>
                        <option class="dropdown-item" value="Pop rock">Pop rock</option>
                        <option class="dropdown-item" value="Punk rock">Punk rock</option>
                        <option class="dropdown-item" value="Rapping">Rapping</option>
                        <option class="dropdown-item" value="Reggae">Reggae</option>
                        <option class="dropdown-item" value="Rock">Rock</option>
                        <option class="dropdown-item" value="Rhythm and blues">Rhythm and blues</option>
                        <option class="dropdown-item" value="Singing">Singing</option>
                        <option class="dropdown-item" value="Ska">Ska</option>
                        <option class="dropdown-item" value="Soul music">Soul music</option>
                        <option class="dropdown-item" value="Techno">Techno</option>
                        <option class="dropdown-item" value="Trance music">Trance music</option>
                        <option class="dropdown-item" value="Vocal music">Vocal music</option>
                        <option class="dropdown-item" value="World music">World music</option>
                    </div>

                </div>
            </div>

            <!-- user profile -->

            <?php
            if (!isset($_SESSION['ID'])) {
            ?>
                <a href="./login.php" class="login-signin btn text-white-50 border-success rounded-pill">Login /
                    Sigup</a>

            <?php } else { ?>
                <div class="em-user nav-item dropdown">
                    <a class="nav-link mk-flex btn text-light" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small user-name mk-text-color2 text-lowercase"><?php (strlen($nickname) > 20) ? $subname = substr($nickname, 0, 20) . '...' : $subname = $nickname;
                                                                                                                            echo $subname; ?>
                        </span>
                        <?php if ($img == 0) { ?>
                            <img class="user-icon rounded-circle" src="./images/profile.png">
                        <?php } else { ?>
                            <img class="user-icon rounded-circle" width="40px" height="30px" src="./userprofiles/<?php echo $img ?>">
                        <?php } ?>
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in mk-bg-dark" aria-labelledby="userDropdown">
                        <!-- CHANGE MADE HERE -->
                        <a href="./chat/inbox.php" class="dropdown-item text-light d-flex align-center">
                            <small id="unread_count" class="mr-2 d-inline-block" style="height: 100%;font-size: 100%; font-weight: 400; color: #fff;padding: 2px 5px;border-radius: 30px;"></small>
                            Inbox
                        </a>

                        <a class="dropdown-item text-light" href="profile.php">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-light" href="" data-toggle="modal" data-target="#logout">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout
                        </a>
                    </div>
                </div>
            <?php } ?>
            <!-- user profile -->

        </div>
    </nav>


</header>
<!--X------ upper navigation --------X-->

<!-- -------- side navigation ------ -->
<div class="side-navigation">
    <ul class="navbar-nav">
        <li class="nav-item" id="index" title="Home">
            <a href="./index.php" class="mk-flex">
                <i class="fa fa-home"></i>
                <span>Home</span>
            </a>
        </li>

        <li class="nav-item" id="search" title="Search">
            <a class="mk-flex searchLink">
                <i class="fa fa-search"></i>
                <span>Search</span>
            </a>
        </li>

        <?php if (isset($_SESSION['ID'])) { ?>
            <li class="nav-item" id="playlist" title="Playlists">
                <a href="./playlists.php" class="mk-flex">
                    <i class="fa fa-music"></i>
                    <span>Playlists</span>
                </a>
            </li>
        <?php } ?>

        <?php if (isset($_SESSION['ID'])) {
        ?>
            <li class="nav-item" id="dashboard-page" title="My dashboard">
                <a href="./dashboard.php" class="mk-flex">
                    <i class="material-icons">dashboard</i>
                    <span>Dashboard</span>
                </a>
            </li>
        <?php }
        ?>

        <?php if (isset($_SESSION['ID'])) { ?>
            <?php if ($user_type == 'team') { ?>
                <li class="nav-item" id="find-artist" title="Source and find the artist you are looking for">
                    <a href="./find-artist.php" class="mk-flex">
                        <i class="fa fa-user-tag"></i>
                        <span>Find artists</span>
                    </a>
                </li>
            <?php } else {
            ?>
                <li class="nav-item" title="emnet studios">
                    <a href="./emnetstudio/index.php" id="studio" class="mk-flex">
                        <i class="fa fa-microphone-alt"></i>
                        <span>Emnet Studios</span>
                    </a>
                </li>
            <?php
            } ?>
        <?php } ?>


        <hr class="my-4">

        <div class="em-about mk-flex">
            <h4 class="text-light">About Us</h4>

            <p class="text-grey"> Emnet is a music streaming platform designed by emark ultimate to promote young talented artists.
            </p>
            <a href="https://www.emnetmusic.com/emnetdevelopers.html">Developer Team</a>
            <span class="right">&copy; emnet ultimate 2023</span>
        </div>
    </ul>

</div>
<!--X------ side navigation --------X-->

<script>
    const currentDIR = location.pathname;
    if (currentDIR.includes('search')) {
        document.getElementById('search').classList.add('active');
    } else if (currentDIR.includes('dashboard')) {
        document.getElementById('dashboard-page').classList.add('active');
        // console.log(document.getElementById('dashboard'))
    } else if (currentDIR.includes('find-artist')) {
        if (document.getElementById('find-artist'))
            document.getElementById('find-artist').classList.add('active');
    } else {
        document.getElementById('index').classList.add('active');
    }
    document.addEventListener('DOMContentLoaded', () => {});
    <?php if (isset($_SESSION['uniqueID'])) { ?>
        document.addEventListener("DOMContentLoaded", () => {
            var unReadIndcator = document.getElementById("unread_count");
            setInterval(() => {
                $.ajax({
                    type: 'POST',
                    url: 'backend/inboxUpdate.php',
                    dataType: 'json',
                    success: function(data) {
                        unReadIndcator.textContent = data.count;
                        if (data.count == 0) {
                            if (unReadIndcator.classList.contains("bg-danger")) {
                                unReadIndcator.classList.replace("bg-danger", "bg-success");
                            } else {
                                unReadIndcator.classList.add("bg-success");
                            }
                        } else {
                            if (unReadIndcator.classList.contains("bg-success")) {
                                unReadIndcator.classList.replace("bg-success", "bg-danger");
                            } else {
                                unReadIndcator.classList.add("bg-danger");
                            }
                        }
                    }
                });
            }, 1000)
        })
    <?php } ?>
</script>