<?php
session_start();
require "./connection.php";


if (isset($_POST['query'])) {
    // $option =  $_POST['option'];

    // $query = "";
    // if ($option == "") {
    //     $inpText = $_POST['query'];
    //     $query = mysqli_query($conn, "SELECT * FROM songs WHERE song_name LIKE '%$inpText%'");

    //     if (mysqli_num_rows($query) > 0) {
    //         while ($row = $query->fetch_assoc()) {
    //             echo "<a class='list-item text-suggest'> " .  $row['song_name'] . " </a>";
    //         }
    //     } else {
    //         echo "<p class='mb-2'> No record </p>";
    //     }
    // }
    // if ($option == "artist") {
    //     $inpText = $_POST['query'];
    //     $query = mysqli_query($conn, "SELECT * FROM songs WHERE artist LIKE '%$inpText%' ");

    //     if (mysqli_num_rows($query) > 0) {
    //         while ($row = $query->fetch_assoc()) {
    //             echo "<a class='list-item text-suggest> " .  $row['artist'] . " </a>";
    //         }
    //     } else {
    //         echo "<p class='mb-2'> No record </p>";
    //     }
    // }
    // if ($option == "genre") {
    //     $inpText = $_POST['query'];
    //     $query = mysqli_query($conn, "SELECT * FROM songs WHERE genre LIKE '%$inpText%' ");

    //     if (mysqli_num_rows($query) > 0) {
    //         while ($row = $query->fetch_assoc()) {
    //             echo "<a class='text-suggest > " .  $row['song_name'] . " </a>";
    //         }
    //     } else {
    //         echo "<p> No record </p>";
    //     }
    // }
    // if ($option == "remix") {
    //     $inpText = $_POST['query'];
    //     $query = mysqli_query($conn, "SELECT * FROM songs WHERE song_name LIKE '%$inpText%' and remix = '1' ");

    //     if (mysqli_num_rows($query) > 0) {
    //         while ($row = $query->fetch_assoc()) {
    //             echo "<a class='text-suggest > " .  $row['song_name'] . " </a>";
    //         }
    //     } else {
    //         echo "<p> No record </p>";
    //     }
    // }
}
