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

echo json_encode($row);

}else{

echo json_encode(["empty"=>true]);

}