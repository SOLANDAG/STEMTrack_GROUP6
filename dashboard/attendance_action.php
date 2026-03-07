<?php
session_start();
include "../config/database.php";

if(!isset($_SESSION['user_id'])){
header("Location: ../auth/login.php");
exit();
}

$user_id = $_SESSION['user_id'];
$date = date("Y-m-d");
$time = date("H:i:s");

$action = $_POST['action'];

if($action == "time_in"){

$sql="SELECT * FROM attendance 
WHERE user_id='$user_id' 
AND attendance_date='$date'";

$result=mysqli_query($conn,$sql);

if(mysqli_num_rows($result)==0){

$insert="INSERT INTO attendance 
(user_id,attendance_date,time_in)
VALUES ('$user_id','$date','$time')";

mysqli_query($conn,$insert);

}

}

if($action == "time_out"){

$update="UPDATE attendance 
SET time_out='$time',
total_hours = ROUND(TIMESTAMPDIFF(MINUTE,time_in,'$time')/60,2)
WHERE user_id='$user_id'
AND attendance_date='$date'";

mysqli_query($conn,$update);

}

header("Location: dashboard.php");
exit();
?>