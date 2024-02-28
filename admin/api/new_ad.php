<?php
require_once "../assets/access.php";

$company = inputValidation($conn, $_POST['company']);
$link = trim(mysqli_real_escape_string($conn, $_POST['link']));
$period = trim(mysqli_real_escape_string($conn, $_POST['period']));
$thumbnail = $_POST['thumbnail'];
function generateFilename()
{
    $filename = uniqid() . random_int(100, 9999);
    return $filename . '.' . 'jpg';
}

if (!empty($link) && !empty($period)) {

    if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z-0-9+&\/%?=~_|!:,.;]*[a-z0-9+&@#\/%=~_|]/i", $link)) {


        if ($thumbnail !== 'data:,') {
            $thumbnail = base64_decode(str_replace('data:image/jpeg;base64,', '', $thumbnail));
            $ad_name = generateFilename();

            $ad_folder = '../../ad_thumbnail/';
            if (file_put_contents($ad_folder . $ad_name, $thumbnail)) {
                $query = $conn->query("SELECT * FROM advert");

                if (mysqli_num_rows($query) < 1) {
                    $sql = mysqli_query($conn, "INSERT INTO advert (name, ad_img, ad_link, ad_duration) values ('{$company}', '{$ad_name}', '{$link}', '{$period}')");
                    if ($sql) {
                        $response = ['status' => 'success', 'message' => "AD Updated"];
                    } else {
                        $response = ['status' => 'error', 'message' => 'AD update failed'];
                    }
                } else {
                    $row_ad = mysqli_fetch_assoc($query);
                    $sql = mysqli_query($conn, "UPDATE advert SET 
                            name = '{$company}',
                            ad_img = '{$ad_name}',
                            ad_link = '{$link}',
                            ad_duration = '{$period}'
                            where ad_id = {$row_ad['ad_id']}
                    ");

                    if ($sql) {
                        if (file_exists($ad_folder . $row_ad['ad_img'])) {
                            unlink($ad_folder . $row_ad['ad_img']);
                        }
                        $response = ['status' => 'success', 'message' => "AD Updated"];
                    } else {
                        $response = ['status' => 'error', 'message' => 'AD update failed'];
                    }
                }
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Thumbnail is empty'];
        }
    } else {
        $response = ['status' => 'error', 'message' => "Link provided is not a valid HTTP"];
    }
} else {
    $response = ['status' => 'error', 'message' => "Required input fields cannot be empty"];
}

outputInJSON($response);
