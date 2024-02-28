<?php
session_start();
include_once "connection.php";

$logoutID = inputValidation($conn, $_POST['logoutID']);
if (isset($_SESSION['uniqueID']) && isset($logoutID)) {

    $status = "Offline Now";
    $sql = mysqli_query($conn, "UPDATE users SET status = '{$status}' WHERE user_id = '{$logoutID}'");

    if ($sql) {
        if (session_unset() && session_destroy()) {
            foreach ($_COOKIE as $cookieName => $cookieValue) {
                unset($_COOKIE[$cookieName]);
                setcookie($cookieName, '', time() - 3600, '/');
            }
            exit("success");
        } else
            exit("error");
    } else {
        exit("error");
    }
}
