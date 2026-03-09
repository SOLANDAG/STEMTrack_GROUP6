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

$today = date("Y-m-d");
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
table-layout:fixed;
border-collapse:separate;
border-spacing:6px;
}

.calendar th{
padding:12px 8px;
background:#5b6cff;
color:white;
border-radius:12px;
font-size:14px;
}

.calendar td{
height:95px;
width:14.28%;
padding:8px;
vertical-align:top;
overflow:hidden;
position:relative;
border:none;
border-radius:16px;
transition:0.2s ease;
}

.day-cell{
background:#dbeafe;
color:#1f2937;
font-weight:500;
cursor:pointer;
box-shadow:0 4px 10px rgba(0,0,0,0.06);
transition:all 0.18s ease;
}

.day-cell.present{
background:#dcfce7;
}

.day-cell.holiday{
background:#fbcfe8;
}

.day-cell.no_class{
background:#f3f4f6;
color:#6b7280;
}

/* TODAY MUST WIN */
.day-cell.today,
.day-cell.present.today,
.day-cell.holiday.today,
.day-cell.no_class.today{
background:#fef3c7 !important;
border:2px solid #facc15 !important;
box-shadow:0 0 12px rgba(250,204,21,0.55) !important;
outline:none !important;
}

.day-cell:hover{
transform:translateY(-2px);
box-shadow:0 10px 18px rgba(0,0,0,0.10);
}

.day-cell.selected{
outline:3px solid #6366f1;
box-shadow:
0 0 0 3px rgba(99,102,241,0.25),
0 10px 18px rgba(0,0,0,0.12);
transform:translateY(-2px);
}

.holiday-name{
font-size:10px;
line-height:1.1;
margin-top:3px;
display:block;
white-space:normal;
word-wrap:break-word;
overflow-wrap:break-word;
max-height:28px;
overflow:hidden;
text-overflow:ellipsis;
}

.day-number{
font-weight:700;
font-size:15px;
margin-bottom:4px;
text-align:left;
}

.month-nav{
display:flex;
align-items:center;
justify-content:space-between;
gap:12px;
margin-bottom:20px;
flex-wrap:wrap;
}

.calendar-legend{
display:flex;
flex-wrap:wrap;
gap:14px;
margin-bottom:18px;
font-size:14px;
color:#374151;
}

.calendar-legend span{
display:flex;
align-items:center;
gap:6px;
}

.legend-box{
width:14px;
height:14px;
display:inline-block;
border-radius:4px;
}

.legend-class{
background:#dbeafe;
}

.legend-present{
background:#dcfce7;
}

.legend-holiday{
background:#fbcfe8;
}

.legend-no-class{
background:#f3f4f6;
border:1px solid #d1d5db;
}

.legend-today{
background:#facc15;
}

</style>

</head>

<body>

<div class="page">

<?php include "../includes/sidebar.php"; ?>

<main class="main-shell">

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

<div class="calendar-legend">
    <span><i class="legend-box legend-class"></i> Class Day</span>
    <span><i class="legend-box legend-present"></i> Present</span>
    <span><i class="legend-box legend-holiday"></i> Holiday</span>
    <span><i class="legend-box legend-no-class"></i> No Class</span>
    <span><i class="legend-box legend-today"></i> Today</span>
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

    $class = "";

    $date_value = $year . "-" . str_pad($month,2,"0",STR_PAD_LEFT) . "-" . str_pad($day,2,"0",STR_PAD_LEFT);

    $schedule_query = "SELECT day_type, description
    FROM school_calendar
    WHERE calendar_date='$date_value'";

    $schedule_result = mysqli_query($conn,$schedule_query);
    $schedule = mysqli_fetch_assoc($schedule_result);

    $day_type = $schedule['day_type'] ?? 'class';
    $description = $schedule['description'] ?? '';

    if($day_type == "holiday"){
        $class = "holiday";
    }

    if($day_type == "no_class"){
        $class = "no_class";
    }

    if(in_array($day,$present_days)){
        $class = "present";
    }

    if($date_value == $today){
        $class .= " today";
    }

    echo "<td class='$class day-cell'
    data-date='$date_value'
    data-daytype='$day_type'
    data-description=\"".htmlspecialchars($description, ENT_QUOTES)."\">";

    echo "<div class='day-number'>$day</div>";

    if($description){
        echo "<div class='holiday-name'>$description</div>";
    }

    echo "</td>";

    $day_count++;

    if($day_count % 7 == 0 && $day != $days_in_month){
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

document.querySelectorAll(".day-cell").forEach(c=>{
c.classList.remove("selected");
});

this.classList.add("selected");

let date = this.dataset.date;
let dayType = this.dataset.daytype || "class";
let description = this.dataset.description || "";

fetch("get_attendance_details.php?date=" + date)
.then(res => res.json())
.then(data => {

let container = document.getElementById("attendance-details");

let scheduleLabel = "Class Day";

if(dayType === "holiday"){
scheduleLabel = "Holiday";
}else if(dayType === "no_class"){
scheduleLabel = "No Class";
}

let scheduleText = `
<p><b>Day Type:</b> ${scheduleLabel}</p>
`;

if(description){
scheduleText += `<p><b>Note:</b> ${description}</p>`;
}

if(data.empty){

container.innerHTML = `
<div class="card">
<h3>Day Details</h3>
<p><b>Date:</b> ${date}</p>
${scheduleText}
<p><b>Attendance:</b> No record for this date.</p>
</div>
`;

}else{

container.innerHTML = `
<div class="card">
<h3>Attendance Details</h3>
<p><b>Date:</b> ${data.attendance_date}</p>
${scheduleText}
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