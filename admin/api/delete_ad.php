<?php
require_once "../assets/access.php";
$ad_id = inputValidation($conn, $_POST['id']);


if (isInDb($ad_id, 'ad_id', 'advert')) {
    $query_advert = $conn->query("SELECT * FROM advert WHERE ad_id = $ad_id");

    $ad_folder = '../../ad_thumbnail/';
    while ($row = $query_advert->fetch_assoc()) {
        $query_clicks = $conn->query("SELECT * FROM clicks WHERE ad_id = $ad_id");

        $ad_image = $row['ad_img'];
        if (file_exists($ad_folder . $ad_image)) {
            unlink($ad_folder . $ad_image);
        }

        if (mysqli_num_rows($query_clicks) > 0) {
            $delete_click = $conn->query("DELETE FROM clicks WHERE ad_id = $ad_id");

            if (!$delete_click) {
                $response = ['status' => 'error', 'message' => "Failed delete clicks"];
            }
        }
    }

    $deleteADS = $conn->query("TRUNCATE TABLE `emnetdb`.`advert`");

    if ($deleteADS) {
        $response = ['status' => 'success', 'message' => "Advert table hs been cleared"];
    } else {
        $response = ['status' => 'error', 'message' => "Advert not deleted"];
    }
} else {
    $response = ['status' => 'error', 'message' => "Advert not found"];
}

outputInJSON($response);
