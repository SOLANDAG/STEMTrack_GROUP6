<?php
session_start();

$current_page = basename($_SERVER['PHP_SELF']);

if(!isset($_SESSION['user_id'])){
header("Location: ../auth/login.php");
exit();
}

include "../config/database.php";

$user_id = $_SESSION['user_id'];

$sql="SELECT * FROM attendance 
WHERE user_id='$user_id'
ORDER BY attendance_date DESC";

$result=mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>

<head>

<title>Attendance History</title>

<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

<body>

<div class="page">

<?php include "../includes/sidebar.php"; ?>

<main class="main-shell">

<div class="top-bar">

<div style="width:52px;"></div>

<div class="top-left">
<img src="../assets/images/StemLogo_no-bg.png">
</div>

<div class="top-right">
<img src="../assets/images/Mapua logo.png">
</div>

</div>


<div class="card">

<h2 class="section-title">Attendance History</h2>

<table class="history-table">

<tr>
<th>Date</th>
<th>Time In</th>
<th>Time Out</th>
<th>Total Hours</th>
</tr>

<?php
while($row=mysqli_fetch_assoc($result)){
?>

<tr>

<td><?php echo $row['attendance_date']; ?></td>
<td><?php echo $row['time_in']; ?></td>
<td><?php echo $row['time_out']; ?></td>
<td><?php echo $row['total_hours']; ?></td>

</tr>

<?php } ?>

</table>

</div>

</main>

</div>

</body>
</html>