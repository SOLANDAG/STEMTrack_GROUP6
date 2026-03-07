<?php
session_start();

$current_page = basename($_SERVER['PHP_SELF']);

if(!isset($_SESSION['user_id'])){
header("Location: ../auth/login.php");
exit();
}

include "../config/database.php";

$user_id = $_SESSION['user_id'];

/* MONTH NAVIGATION */

$month = $_GET['month'] ?? date("m");
$year = $_GET['year'] ?? date("Y");

$month = intval($month);
$year = intval($year);

$first_day = mktime(0,0,0,$month,1,$year);
$days_in_month = date("t",$first_day);
$start_day = date("w",$first_day);

/* FETCH ATTENDANCE */

$sql = "SELECT attendance_date FROM attendance
WHERE user_id='$user_id'
AND MONTH(attendance_date)='$month'
AND YEAR(attendance_date)='$year'";

$result = mysqli_query($conn,$sql);

$present_days = [];

while($row=mysqli_fetch_assoc($result)){
$day = date("j",strtotime($row['attendance_date']));
$present_days[] = $day;
}

/* MONTH NAVIGATION LINKS */

$prev_month = $month - 1;
$next_month = $month + 1;

$prev_year = $year;
$next_year = $year;

if($prev_month == 0){
$prev_month = 12;
$prev_year--;
}

if($next_month == 13){
$next_month = 1;
$next_year++;
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Attendance Calendar</title>

<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/dashboard.css">

<style>

.calendar{
width:100%;
text-align:center;
}

.calendar th{
padding:10px;
background:#5b6cff;
color:white;
}

.calendar td{
padding:20px;
border:1px solid #eee;
}

.present{
background:#d7ffd9;
font-weight:bold;
}

.month-nav{
display:flex;
justify-content:space-between;
margin-bottom:20px;
}

</style>

</head>

<body>

<div class="page">

<?php include "../includes/sidebar.php"; ?>

<main class="main-shell">

<!-- TOP BAR -->
<div class="top-bar">

<div style="width:52px;"></div>

<div class="top-left">
<img src="../assets/images/StemLogo_no-bg.png" alt="STEM Logo">
</div>

<div class="top-right">
<img src="../assets/images/Mapua logo.png" alt="Mapua Logo">
</div>

</div>

<div class="card">

<h2 class="section-title">Attendance Calendar</h2>

<div class="month-nav">

<a class="main-btn calendar-btn"
href="?month=<?php echo $prev_month ?>&year=<?php echo $prev_year ?>">
← Previous
</a>

<h3><?php echo date("F Y",$first_day); ?></h3>

<a class="main-btn calendar-btn"
href="?month=<?php echo $next_month ?>&year=<?php echo $next_year ?>">
Next →
</a>

</div>

<table class="calendar">

<tr>
<th>Sun</th>
<th>Mon</th>
<th>Tue</th>
<th>Wed</th>
<th>Thu</th>
<th>Fri</th>
<th>Sat</th>

</tr>

<tr>

<?php

for($i=0;$i<$start_day;$i++){
echo "<td></td>";
}

$day_count = $start_day;

for($day=1;$day<=$days_in_month;$day++){

$class="";

if(in_array($day,$present_days)){
$class="present";
}

$date_value = "$year-$month-$day";

echo "<td class='$class day-cell' data-date='$date_value'>$day</td>";

$day_count++;

if($day_count % 7 == 0){
echo "</tr><tr>";
}

}

?>

</tr>

</table>

<div id="attendance-details" style="margin-top:30px;"></div>

</div>

</main>

</div>

<script>

document.querySelectorAll(".day-cell").forEach(cell => {

cell.addEventListener("click", function(){

let date = this.dataset.date;

fetch("get_attendance_details.php?date=" + date)

.then(res => res.json())

.then(data => {

let container = document.getElementById("attendance-details");

if(data.empty){

container.innerHTML = `
<div class="card">
<h3>No Attendance</h3>
<p>No record for this date.</p>
</div>
`;

}else{

container.innerHTML = `
<div class="card">
<h3>Attendance Details</h3>

<p><b>Date:</b> ${data.attendance_date}</p>

<p><b>Time In:</b> ${data.time_in}</p>

<p><b>Time Out:</b> ${data.time_out}</p>

<p><b>Total Hours:</b> ${data.total_hours}</p>

</div>
`;

}

});

});

});

</script>

</body>
</html>