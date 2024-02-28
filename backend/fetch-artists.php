<?php
require_once "./connection.php";
if ($_POST['type'] == 'initial') {
    header("Content-Type: application/json");
    $page = isset($_POST["page"]) ? $_POST["page"] : 1;
    $resultsPerPage = isset($_POST["resultsPerPage"]) ? $_POST["resultsPerPage"] : 10;
    $offset = ($page - 1) * $resultsPerPage;
    $response = [];

    $query_artists = $conn->query("SELECT * FROM creators ORDER BY songs DESC LIMIT $offset, $resultsPerPage");
    if ($query_artists->num_rows > 0) {
        while ($row = $query_artists->fetch_assoc()) {
            $artist_id = $row["user_id"];
            $user_name = $row["nickname"];
            $artist_songs = $row["songs"];
            $fetch_artist = $conn->query("SELECT user_profile FROM users WHERE nickname = '$user_name'")->fetch_assoc();
            $user_profile = $fetch_artist['user_profile'];
            $user_profile = $user_profile !== '' ? "userprofiles/$user_profile" : 'images/profile.png';

            $user_object = [
                'status' => $page,
                'message' =>
                "<a href='artist.php/$user_name'>
                        <div class='artist-img'><img src='$user_profile'></div>
                        <div class='artist-name text-capitalize'>
                            <p>$user_name</p>
                        </div>
                        <div class='artist-songs'>
                            <p> $artist_songs</p>
                        </div>
                    </a>"
            ];
            array_push($response, $user_object);
        }
    } else {
        $response = ['status' => '0'];
    }
    echo json_encode($response);
} else if ($_POST["type"] == "search") {
    header("Content-Type: application/json");
    $search_value = trim($_POST['value']);
    $response = [];
    $query_artist = $conn->query("SELECT * FROM creators WHERE nickname LIKE '%{$search_value}%' ");

    if ($query_artist->num_rows > 0) {
        while ($row = $query_artist->fetch_assoc()) {
            $user_name = $row["nickname"];
            if (str_contains($user_name, $search_value)) {
                $artist_songs = $row["songs"];
                $fetch_artist = $conn->query("SELECT user_profile FROM users WHERE nickname = '$user_name'")->fetch_assoc();
                $user_profile = $fetch_artist['user_profile'];
                $user_profile = $user_profile !== '' ? "userprofiles/$user_profile" : 'images/profile.png';
                $user_object = [
                    'status' => 'success',
                    'message' =>
                    "<a href='artist_page.php/$user_name'>
                    <div class='artist-img'><img src='$user_profile'></div>
                    <div class='artist-name text-capitalize'>
                        <p>$user_name</p>
                    </div>
                    <div class='artist-songs'>
                        <p> $artist_songs</p>
                    </div>
            </a>
                "
                ];
                array_push($response, $user_object);
            }
            echo json_encode($response);
        }
    } else {
        $response = ['status' => '0'];
        echo json_encode($response);
    }
}
