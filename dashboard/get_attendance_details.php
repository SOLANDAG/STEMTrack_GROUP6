<?php

session_start();
include "../config/database.php";

if(!isset($_SESSION['user_id'])){
exit();
}

$user_id = $_SESSION['user_id'];

$date = $_GET['date'];

$sql = "SELECT * FROM attendance
WHERE user_id='$user_id'
AND attendance_date='$date'";

$result = mysqli_query($conn,$sql);

$row = mysqli_fetch_assoc($result);

if($row){

if($row['time_in'] && $row['time_out']){

$start = strtotime($row['time_in']);
$end = strtotime($row['time_out']);

$seconds = $end - $start;

$hours = floor($seconds / 3600);
$minutes = floor(($seconds % 3600) / 60);

$row['total_hours'] = $hours . "h " . $minutes . "m";

}else{

$row['total_hours'] = "-";

}

echo json_encode($row);

}else{

echo json_encode(["empty"=>true]);

}