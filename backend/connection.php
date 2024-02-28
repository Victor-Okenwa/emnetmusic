<?php
# *********** THIS IS THE DATABASE CONNECTION SCRIPT ************ #
# ---- Here we have some universal functions
# ---- And remember script for set sessions
require "config.php";
if (!$conn) {
    echo ('Not connected');
} else {

    // VALIDATE USER INPUT AND STRIP UNWANTED SYMBOLS AND WHITE SPACES
    function inputValidation($conn, $input)
    {
        $data = trim($input);
        $data = stripcslashes($data);
        $data = mysqli_real_escape_string($conn, $data);
        return $data;
    }
}

// TO SHORTEN TEXT FOR USER NAME NAME[has a longer word substitution algorithm]
function shortenTextName($text)
{
    (strlen($text) > 50) ? $subtext = substr($text, 0, 50) . '...' : $subtext = $text;
    echo $subtext;
}

// TO SHORTEN TEXT FOR ARTIST NAME[has a shorter word substitution algorithm]
function shortenTextArtist($text)
{
    (strlen($text) > 25) ? $subtext = substr($text, 0, 25) . '...' : $subtext = $text;
    echo $subtext;
}

function subNumber($numb)
{
    if ($numb <= 999) {
        echo $numb;
    } elseif ($numb <= 999000) {
        echo floatval($numb / 1000) . "k";
    } elseif ($numb <= 999000000) {
        echo floatval($numb / 1000000) . "m";
    } elseif ($numb <= 999000000000) {
        echo floatval($numb / 1000000000) . "B";
    } elseif ($numb <= 999000000000000) {
        echo floatval($numb / 1000000000000) . "T";
    }
}

function isInDb($field, $column, $table)
{
    global $conn;
    $queryDb = $conn->query("SELECT $column from $table WHERE $column = '{$field}'")->num_rows;
    if ($queryDb > 0) return true;
    else return false;
}

function existsWith($field1, $field2, $column1, $column2, $table)
{
    global $conn;
    $queryDb = $conn->query("SELECT $column1 from $table WHERE $column1 = '{$field1}' AND $column2 = '{$field2}'")->num_rows;
    if ($queryDb > 0) return true;
    else return false;
}

function outputInJSON($array)
{
    header("Content-Type: application/json");
    echo json_encode($array);
}

//----  BYTE ENCRYPTION
// Encryption configuration


// The encryption key *! NOT TO BE ALTERED !*
define("HASH_KEY", "my_secret_key");

// The Encryption cipher method
define("CIPHER_METHOD", "AES-256-CBC");
$options = 0;

// CHECK IF USER EXISTS OR NOT [METHOD REFRACTOR]

function checkUserExists($user_id)
{
    global $conn;

    $query_user_exists = $conn->query("SELECT * FROM users WHERE user_id = '{$user_id}'");
    return $query_user_exists->num_rows;
}

function encryptData($data, $key, $iv)
{
    $encryptedData = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
    return base64_encode($encryptedData);
}

// Decrypt data
function decryptData($encryptedData, $key, $iv)
{
    $encryptedData = base64_decode($encryptedData);
    return openssl_decrypt($encryptedData, 'AES-256-CBC', $key, 0, $iv);
}

if (isset($_COOKIE['uniqueId']) && !isset($_SESSION['uniqueID'])) {
    $_SESSION['ID'] = $_COOKIE['userId'];
    $_SESSION['uniqueID'] = $_COOKIE['uniqueId'];
    $_SESSION['status'] = $_COOKIE['Status'];
    $_SESSION['nickname'] = $_COOKIE['Nickname'];
}

if (isset($_SESSION['ID'])) {
    if (isInDb($_SESSION['ID'], 'id', 'users')) {
        $id = $_SESSION['ID'];
        $fetch_user = $conn->query("SELECT * FROM users WHERE id = $id")->fetch_assoc();
        $user_id = $fetch_user['user_id'];
        $email = $fetch_user['email'];
        $nickname = $fetch_user['nickname'];
        $img = $fetch_user['user_profile'];
        $user_type = $fetch_user['user_type'];
    }
}

// $domain = "https://emnetmusic.com";
$domain = "http://127.0.0.1/emnet";
isset($domain) && $domain !== "" ? $domain : "https://emnetmusic.com";
