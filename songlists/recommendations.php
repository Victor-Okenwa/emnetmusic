<?php
require_once "../backend/connection.php";
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$resultsPerPage = isset($_GET['perPage']) ? $_GET['perPage'] : 10;
$offset = ($page - 1) * $resultsPerPage;

// echo $page . ' ' . $offset . ' ' . $resultsPerPage;

$query_songs = $conn->query("SELECT * FROM songs order BY rand() desc LIMIT $offset, $resultsPerPage");
$songArray = [];
$songIds = [];
while ($song_data = $query_songs->fetch_assoc()) {
    $thisSongID = $song_data['song_id'];
    $thisSongName = $song_data['song_name'];
    $thisSongArtist = $song_data['artist'];
    $thisFileName = $song_data['audio_file'];
    array_push($songArray, ['songID' => $thisSongID, 'songName' => $thisSongName]);
?>
    <div class="song-item ambientDiv" id="<?= $song_data['song_id'] ?>" onclick="selectSong(this)">
        <div class="img-play">
            <img src="./audio_thumbnails/<?= $song_data['thumbnail'] ?>" alt="<?= $song_data['song_name'] ?>">
            <i class="fa playlistPlay fa-play-circle" id="<?= $song_data['song_id'] ?>"></i>
            <i class="fa fa-pause-circle"></i>
            <audio class="audio-item" src="./audios/<?= $song_data['audio_file'] ?>"></audio>
        </div>

        <div class="mk-flex justify-content-between">
            <h5>
                <div class="song-name text-capitalize mk-abel"><?= shortenTextName($song_data['song_name']) ?></div>
                <div class="artist text-capitalize"><?= shortenTextArtist($song_data['artist']) ?></div>
            </h5>
            <small class="text-light" style="font-size: 10px"><small class="fa fa-eye text-success"></small> <?= subNumber($song_data['streams'])  ?></small>
        </div>

        <div class="xb42dee">
            <?= "$domain/view.php?song=" . trim($song_data['song_id']) ?>
        </div>

        <div class="xb42dee2">
            <?= "$domain/artist.php?artist=" . trim($song_data['admin_name'] == "" ? $song_data['creator_name'] : $song_data['admin_name']) ?>
        </div>

        <?= ($song_data['admin_id'] != 0) ? "<span class='from-emnet'></span>" : "" ?>
        <?= ($song_data['admin_id'] != 0) ? "<span class='poster_id'> " . $song_data['admin_id'] . " </span>" : "<span class='poster_id'>" . $song_data['creator_id'] . " </span>" ?>

        <div class="genre"><?= $song_data['genre'] ?></div>
    </div>
<?php
}
?>