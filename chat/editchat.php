<?php
session_start();
require_once "../backend/connection.php";


$response = array('status' => 'error', 'message' => "Message empty");

echo json_encode($response);
