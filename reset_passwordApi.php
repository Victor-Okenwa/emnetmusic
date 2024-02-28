<?php
require "./backend/connection.php";
session_start();
$email = inputValidation($conn, $_POST['email']);
$token = inputValidation($conn, $_POST['token']);
$newPassword = trim($_POST['newPassword']);
$confPassword = trim($_POST['confPassword']);
$email = htmlspecialchars($email);


$query_tokens = $conn->query("SELECT * FROM tokens WHERE token = $token AND email = '$email'");
if ($query_tokens->num_rows > 0) {
    $fetch_type = $query_tokens->fetch_assoc();
    $user_id = $fetch_type['user_id'];

    $query_client_type_usr = $conn->query("SELECT * from users WHERE user_id = '{$user_id}' AND email = '$email'");
    $query_client_type_admn = $conn->query("SELECT * from admin WHERE admin_id = $user_id AND email = '$email'");

    if ($query_client_type_usr->num_rows > 0) {
        $password = password_hash($newPassword, PASSWORD_DEFAULT);
        $update_user = $conn->query("UPDATE users SET password = '{$password}' WHERE email = '$email' and user_id = '$user_id'");

        if ($update_user) {
            $delete_tokens = $conn->query("DELETE FROM tokens WHERE token = $token AND email = '$email' ");
            $sql_fetch = mysqli_query($conn, "SELECT * FROM users where email = '$email' AND user_id = '{$user_id}' ");
            if (mysqli_num_rows($sql_fetch) > 0) {
                $row = mysqli_fetch_assoc($sql_fetch);
                $_SESSION['ID'] = $row['id'];
                $_SESSION['uniqueID'] = $row['user_id'];
                $_SESSION['nickname'] = $row['nickname'];
                $_SESSION['status'] = $row['status'];

                $IV = random_bytes(16);
                setcookie("IdVector", $IV, time() + 8035200, '/');
                setcookie("userId", encryptData($row['id'], HASH_KEY, $IV), time() + 8035200, '/');
                setcookie("uniqueId", encryptData($row['user_id'], HASH_KEY, $IV), time() + 8035200, '/');
                setcookie("Nickname", $row['nickname'], time() + 8035200, '/');
                setcookie("Email", $email, time() + 8035200, '/');
                setcookie("Password", $newPassword, time() + 8035200, '/');
                setcookie("Status", $row['status'], time() + 8035200, '/');
            }
            $response = array('status' => 'success', 'message' => "Password update was a success");
        } else {
            $response = array('status' => 'error', 'message' => "Failed to update password, try again");
        }
    } elseif ($query_client_type_admn->num_rows > 0) {
        $password = password_hash($newPassword, PASSWORD_DEFAULT);
        $update_user = $conn->query("UPDATE admin SET password = '{$password}' WHERE email = '$email' and admin_id = '$user_id'");

        if ($update_user) {
            $delete_tokens = $conn->query("DELETE FROM tokens WHERE token = $token AND email = '$email' ");
            $sql_fetch = mysqli_query($conn, "SELECT * FROM admin where email = '$email' AND admin_id = $user_id");
            if (mysqli_num_rows($sql_fetch) > 0) {
                $row = mysqli_fetch_assoc($sql_fetch);
                $_SESSION['adminID'] = $row['id'];
                $_SESSION['adminUniqueID'] = $row['admin_id'];
                // $_SESSION['nickname'] = $row['naickname'];
                $_SESSION['status'] = $row['status'];

                $IV = random_bytes(16);
                setcookie("IdVector", $IV, time() + 8035200, '/');
                setcookie("adminId", encryptData($row['id'], HASH_KEY, $IV), time() + 8035200,  '/');
                setcookie("adminUniqueId", encryptData($row['admin_id'], HASH_KEY, $IV), time() + 8035200,  '/');
                setcookie("Email", $email, time() + 8035200,  '/');
                setcookie("Password", $password, time() + 8035200,  '/');
            }
            $response = array('status' => 'success', 'message' => "Password update was a success");
        } else {
            $response = array('status' => 'error', 'message' => "Failed to update password, try again");
        }
    }
} else {
    $response = array('status' => 'error', 'message' => "Email or token not found <br/><small>Check if email was sent twice, use the most recent.</small>");
}

echo json_encode($response);
