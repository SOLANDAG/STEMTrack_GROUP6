<?php
session_start();

if(!isset($_SESSION['user_id'])){
header("Location: ../auth/login.php");
}
?>

<h1>Dashboard</h1>

<a href="attendance.php">Record Attendance</a>

<br><br>

<a href="history.php">View Attendance History</a>

<br><br>

<a href="../auth/logout.php">Logout</a>