<?php
require "./backend/connection.php";

if (isset($_FILES['compressedAudio'])) {
    $destinationFolder = 'images/';
    $uploadedFile = $_FILES['compressedAudio']['tmp_name'];
    $originalFileName = $_FILES['compressedAudio']['name'];
    $destinationPath = $destinationFolder . $originalFileName;

    if (move_uploaded_file($uploadedFile, $destinationPath)) {
        echo 'File uploaded and processed successfully.';
    } else {
        echo 'Error moving the file to the destination folder.';
    }
} else {
    echo 'No file received.';
}




// $page = isset($_GET['page']) ? $_GET['page'] : 1;
// $resultsPerPage = isset($_GET['perPage']) ? $_GET['perPage'] : 20;

// $offset = ($page - 1) * $resultsPerPage;

// $query_chats = $conn->query("SELECT * FROM chats order by chat_id LIMIT $offset, $resultsPerPage");

// if ($query_chats->num_rows > 0) {
//     while ($row = $query_chats->fetch_assoc()) {
//         $chat_id = $row['chat_id'];
//         $iv = $row['iv'];
//         $message = openssl_decrypt($row['message'], CIPHER_METHOD, HASH_KEY, 0, $iv);

//         $image = $row['image'];
//         $type = $row['type'];

// 
?>

<!-- <div class='test-card card bg-dark'> -->
<!-- <div class='card-img'> -->
<!-- <img src='./emnet/chat/uploads/<?= $image ?>' alt=''> -->
<!-- </div> -->
<!-- <div class='card-body'> -->
<!-- <p class='text-light'><?= $chat_id ?><?= $message ?></p> -->
<!-- <a href='<?= $chat_id ?>' class='text-light btn btn-primary'>Go To page</a> -->
<!-- </div> -->
<!-- </div> -->

<?php
//     }
// } else {
//     echo "No Result found";
// }

// mysqli_close($conn);

// $data = [];
// $results = [];
// while ($row = $query_chats->fetch_assoc()) {
//     $data = [
//         ['message' => openssl_decrypt($row['message'], CIPHER_METHOD, HASH_KEY, 0, $row['iv'])]
//     ];

//     // $results .= $data;
//     echo json_encode($data);
// }


// header('Content-Type: application/json');
