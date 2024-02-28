<?php
require "../assets/access.php";
$passkey = inputValidation($conn, $_POST['passkey']);
$remember = isset($_POST['remember']) ? true : false;
if (!empty($passkey)) {

    if (existsWith($passkey, $admin_id, 'passkey', 'id', 'admin')) {
        if ($remember) {
            $iv = openssl_random_pseudo_bytes(16);
            $hash_key = "my_secret_key";
            $CIPHER_METHOD = "AES-256-CBC";
            $options = 0;
            $passkey = openssl_encrypt($passkey, $CIPHER_METHOD, $hash_key, $options, $iv);
            
            setcookie("IV", $iv, time() + 8035200,  '/');
            setcookie("passkey", $passkey, time() + 8035200,  '/');
        } else {
            setcookie("passkey", "");
        }
        $response = ['status' => 'success', 'message' => 'Logged in'];
    } else {
        $response = ['status' => 'error', 'message' => 'Passkey not found'];
    }
} else {
    $response = ['status' => 'error', 'message' => 'passkey cannot be empty'];
}

outputInJSON($response);
