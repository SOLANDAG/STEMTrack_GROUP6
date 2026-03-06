<?php
session_start();
include "../config/database.php";

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM attendance WHERE user_id='$user_id' ORDER BY date DESC";
$result = mysqli_query($conn,$sql);
?>

<h2>Attendance History</h2>

<table border="1" cellpadding="10">

<tr>
<th>Date</th>
<th>Time In</th>
<th>Time Out</th>
</tr>

<?php
while($row = mysqli_fetch_assoc($result)){
?>

<tr>
<td><?php echo $row['date']; ?></td>
<td><?php echo $row['time_in']; ?></td>
<td><?php echo $row['time_out']; ?></td>
</tr>

<?php } ?>

</table>

<br>

<a href="dashboard.php">Back to Dashboard</a>