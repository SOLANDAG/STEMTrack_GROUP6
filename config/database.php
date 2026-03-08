<?php

date_default_timezone_set('Asia/Manila');

$host = "localhost";
$user = "root";
$password = "";
$database = "stemtrack_db";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>