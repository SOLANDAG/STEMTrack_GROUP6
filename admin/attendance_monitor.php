<?php
session_start();

$current_page = basename($_SERVER['PHP_SELF']);

include "../config/database.php";

if(!isset($_SESSION['user_id'])){
header("Location: ../auth/login.php");
exit();
}

$role = $_SESSION['role'];

if($role != "admin" && $role != "faculty"){
echo "Access denied";
exit();
}

$today = date("Y-m-d");

$search = $_GET['search'] ?? '';

$sql = "SELECT 
users.user_id,
users.first_name,
users.last_name,
users.grade,
attendance.time_in,
attendance.time_out,
attendance.total_hours
FROM users
LEFT JOIN attendance 
ON users.user_id = attendance.user_id
AND attendance.attendance_date = '$today'
WHERE users.role = 'student'
";

if($search != ''){
$sql .= " AND (users.first_name LIKE '%$search%' 
OR users.last_name LIKE '%$search%')";
}

$sql .= " ORDER BY users.last_name ASC";

$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>

<head>

<title>Attendance Monitor</title>

<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

<body>

<div class="page">

<?php include "../includes/sidebar.php"; ?>

<main class="main-shell">

<div class="card">

<h2 class="section-title">Student Attendance Monitor</h2>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">

<span style="font-size:16px;font-weight:500;">
Date: <?php echo date("F d, Y"); ?>
</span>

<a href="export_attendance.php" class="main-btn export-btn">
Export Attendance
</a>

</div>

<form method="GET" class="search-form">

<input
class="input-field search-input"
type="text"
name="search"
placeholder="Search student..."
value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
>

<button class="main-btn">Search</button>

</form>

<table class="history-table">

<tr>
<th>Student</th>
<th>Grade</th>
<th>Status</th>
<th>Time In</th>
<th>Time Out</th>
<th>Total Hours</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<?php

$time_in = $row['time_in'];
$time_out = $row['time_out'];

$status = "❌ NO RECORD";

if($time_in && !$time_out){
$status = "🟢 TIMED IN";
}

if($time_in && $time_out){
$status = "⚪ TIMED OUT";
}

?>

<tr>

<td><?php echo $row['first_name']." ".$row['last_name']; ?></td>

<td><?php echo $row['grade']; ?></td>

<td><?php echo $status; ?></td>

<td><?php echo $time_in ?? "-"; ?></td>

<td><?php echo $time_out ?? "-"; ?></td>

<td>

<?php

if($time_in && $time_out){

$start = strtotime($time_in);
$end = strtotime($time_out);

$seconds = $end - $start;

$hours = floor($seconds / 3600);
$minutes = floor(($seconds % 3600) / 60);

echo $hours . "h " . $minutes . "m";

}else{

echo "-";

}

?>

</td>

</tr>

<?php } ?>

</table>

</div>

</main>

</div>

<script>
setTimeout(function(){
location.reload();
},10000);
</script>

</body>
</html>