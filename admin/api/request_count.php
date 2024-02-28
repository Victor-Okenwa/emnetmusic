<?php
require_once "../assets/access.php";
$count = $conn->query("SELECT request_id from requests")->num_rows;

$numb = $count;

if ($count > 0) {
    if ($count <= 999) {
        $numb = $count;
    } elseif ($count <= 999000) {
        $numb  = floatval($count / 1000) . "k";
    } elseif ($count <= 999000000) {
        $numb  = floatval($count / 1000000) . "m";
    } elseif ($count <= 999000000000) {
        $numb  = floatval($count / 1000000000) . "B";
    } elseif ($count <= 999000000000000) {
        $numb  = floatval($count / 1000000000000) . "T";
    }

    $response = ['status' => 'found', 'message' => $numb];
} else {
    $response = ['status' => 'not found', 'message' => ""];
}
outputInJSON($response);
