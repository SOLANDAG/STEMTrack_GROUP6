<?php

include "../config/database.php";

header('Content-Type: text/csv');
$date = date("Y-m-d");

header("Content-Disposition: attachment; filename=attendance_$date.csv");

$output = fopen("php://output", "w");

fputcsv($output, ["Attendance Report"]);
fputcsv($output, ["Date Generated:", date("F d, Y")]);
fputcsv($output, []); // empty row

/* column headers */

fputcsv($output, [
"Student",
"Grade",
"Date",
"Time In",
"Time Out",
"Total Hours"
]);

$sql = "SELECT 
users.first_name,
users.last_name,
users.grade,
attendance.attendance_date,
attendance.time_in,
attendance.time_out,
attendance.total_hours
FROM attendance
JOIN users
ON attendance.user_id = users.user_id
ORDER BY attendance.attendance_date DESC";

$result = mysqli_query($conn,$sql);

while($row = mysqli_fetch_assoc($result)){

fputcsv($output, [

$row['first_name']." ".$row['last_name'],
$row['grade'],
$row['attendance_date'],
$row['time_in'],
$row['time_out'],
$row['total_hours']

]);

}

fclose($output);
exit();