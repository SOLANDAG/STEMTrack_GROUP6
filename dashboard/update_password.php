<?php

session_start();
include "../config/database.php";

$user_id=$_SESSION['user_id'];

$current=$_POST['current_password'];
$new=$_POST['new_password'];
$confirm=$_POST['confirm_password'];

$sql="SELECT password FROM users WHERE user_id='$user_id'";
$result=mysqli_query($conn,$sql);
$user=mysqli_fetch_assoc($result);

if($current!=$user['password']){
echo "Current password incorrect";
exit();
}

if($new!=$confirm){
echo "Passwords do not match";
exit();
}

$update="UPDATE users SET password='$new' WHERE user_id='$user_id'";
mysqli_query($conn,$update);

header("Location: profile.php");