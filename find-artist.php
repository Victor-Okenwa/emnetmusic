<?php
session_start();
include_once "backend/connection.php";
if (!isset($_SESSION["uniqueID"])) {
    header('location:/');
}
if ($user_type == "team") {
} else {
    header('location:/');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=top">
    <meta id="<?= isset($_SESSION['uniqueID']) ? $_SESSION['uniqueID'] : "" ?>" class="user_id">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=yes">
    <link rel="shortcut icon" sizes="392x6592" href="./logos/Emnet_Logo2.png" type="image/x-icon">

    <!-- -------------- Font awesome REM(insert for web letter) --------- -->
    <link rel="stylesheet" href="./css/all.css">

    <!-- -------------- Google material icons REM(insert for web letter) --------- -->
    <link rel="stylesheet" href="./iconfont/material-icons.css">


    <!-- -------------- boostrap REM(insert for web letter) --------- -->
    <link rel="stylesheet" href="./css/boot4.css">

    <!-- -------------- custom --------- -->
    <link rel="stylesheet" href="./css/style.css">

    <title>Emnet music</title>
</head>

<body>

    <body class="paused" id="find_artist">
        <!-- ----------------------- HEADER ------------------------ -->
        <?php require "./navbars.php" ?>
        <!--x----------------------- HEADER ------------------------x-->

        <!-- ----------------------- MAIN ------------------------ -->

        <main id="main">

            <div class="search-box">
                <div class="search">
                    <input type="text" name="search" class="search-input" placeholder="Search here..." autocomplete="nickname">
                    <button class="search-btn btn" disabled>
                        <i class="searchIcon fa fa-search text-light"></i>
                        <i class="clearIcon fa fa-times text-light d-none"></i>
                    </button>
                </div>
            </div>

            <div class="artists">
                <div class="head-info">
                    <div>Profile</div>
                    <div>Name</div>
                    <div>Songs</div>
                </div>

                <div class="body-info">

                </div>
                <div class="loading-info d-none"></div>
            </div>

        </main>
        <?php include "modals.php" ?>

        <!-- ----- jQuery(insert for web letter) ----- -->
        <script src="./js/jquery3.6.0.js"></script>

        <!-- ----- popper(insert for web letter) ----- -->
        <script src="./js/popper1.160.min.js"></script>

        <!-- ----- boostrap(insert for web letter) ----- -->
        <script src="./js/bootstrap4.js"></script>

        <script src="./js/lazyload.js"></script>
        <!-- ----- custom ----- -->
        <script src="./js/main.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const body = document.getElementById('find_artist'),
                    searchInput = document.querySelector('.search-input'),
                    searchBtn = document.querySelector('.search-btn'),
                    searchIcon = searchBtn.querySelector('.searchIcon'),
                    clearIcon = searchBtn.querySelector('.clearIcon'),
                    bodyInfo = document.querySelector(".body-info"),
                    loadingInfo = document.querySelector('.loading-info');

                clearIcon.onclick = () => {
                    searchInput.value = '';
                    clearIcon.classList.add('d-none');
                    searchIcon.classList.remove('d-none');
                    searchBtn.setAttribute('disabled', '');
                }

                searchInput.oninput = () => {
                    const currentValue = searchInput.value.trim();
                    const valueLength = searchInput.value.trim().length;

                    if (valueLength > 0) {
                        if (searchBtn.disabled) {
                            searchBtn.removeAttribute('disabled');
                        }
                        searchIcon.classList.add('d-none');
                        clearIcon.classList.remove('d-none');
                        $.ajax({
                            url: "backend/fetch-artists.php",
                            timeout: 20000,
                            method: 'POST',
                            data: {
                                type: 'search',
                                value: searchInput.value.toLocaleLowerCase(),
                            },
                            beforeSend: () => {
                                bodyInfo.innerHTML = '';
                                loadingInfo.textContent = 'Loading...';
                                loadingInfo.classList.remove('d-none');
                            },
                            complete: () => {
                                loadingInfo.classList.add('d-none');
                                loadingInfo.textContent = '';
                            },
                            success: (data) => {
                                bodyInfo.innerHTML = '';
                                if (data.length > 0) {
                                    data.map(artist => {
                                        $(".body-info").append(artist.message);
                                    });
                                } else {
                                    const noRecords = `
                                        <p class="text-light text-center mt-2">No record found</p>
                                    `;
                                    $(".body-info").html(noRecords);
                                }

                            },
                            error: (jqXHR, status, error) => {
                                if (status == 'timeout') {
                                    loadingInfo.textContent = 'Search timed out';
                                    loadingInfo.classList.remove('d-none');
                                } else {
                                    loadingInfo.textContent = `Error: ${error}`;
                                    loadingInfo.classList.remove('d-none');
                                }
                            }
                        });
                    } else {
                        searchBtn.setAttribute('disabled', '');
                        searchIcon.classList.remove('d-none');
                        clearIcon.classList.add('d-none');
                        loadContent(currentPage);
                    }
                }

                var currentPage = 1;
                var resultsPerPage = 10;
                var xhr = new XMLHttpRequest();

                function loadContent(page) {
                    $.ajax({
                        url: 'backend/fetch-artists.php',
                        method: 'POST',
                        timeout: 30000,
                        data: {
                            type: 'initial',
                            page: page
                        },
                        beforeSend: () => {
                            if (document.querySelectorAll('.no-record').length > 0) {
                                return xhr.abort();
                            } else {
                                loadingInfo.textContent = 'Loading...';
                                loadingInfo.classList.remove('d-none');
                            }
                        },
                        complete: () => {
                            loadingInfo.classList.add('d-none');
                            loadingInfo.textContent = '';
                        },
                        success: function(data) {
                            if (data.length > 0) {
                                data.map(artist => $(".body-info").append(artist.message));
                            }
                            if (data.status == "0") {
                                if (document.querySelectorAll('.no-record').length > 0) {
                                    xhr.abort();
                                } else {
                                    const noRecords = `
                                        <p class="no-record text-light text-center mt-2 mk-abel">No more record found</p>
                                    `;
                                    $(".body-info").append(noRecords);
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            loadingInfo.textContent = 'Failed to load content' + error;
                            loadingInfo.classList.remove('d-none');
                        }
                    });
                }

                loadContent(currentPage);
                body.addEventListener('scroll', () => {
                    let currentPosition = Math.round(body.scrollTop + body.clientHeight);
                    if (currentPosition >= body.scrollHeight) {
                        currentPage++;
                        loadContent(currentPage);
                    }
                });
            });
        </script>
    </body>

</html>