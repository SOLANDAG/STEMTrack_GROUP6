<?php
session_start();
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

$search = $_GET['search'] ?? '';

$sql = "SELECT users.first_name, users.last_name, users.grade,
attendance.attendance_date,
attendance.time_in,
attendance.time_out,
attendance.total_hours
FROM attendance
JOIN users ON attendance.user_id = users.user_id";

if($search != ''){
$sql .= " WHERE users.first_name LIKE '%$search%' 
OR users.last_name LIKE '%$search%'";
}

$sql .= " ORDER BY attendance.attendance_date DESC";

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
<th>Date</th>
<th>Time In</th>
<th>Time Out</th>
<th>Total Hours</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['first_name']." ".$row['last_name']; ?></td>

<td><?php echo $row['grade']; ?></td>

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