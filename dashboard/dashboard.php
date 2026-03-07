<?php
session_start();

$current_page = basename($_SERVER['PHP_SELF']);



if(!isset($_SESSION['user_id'])){
header("Location: ../auth/login.php");
exit();
}

include "../config/database.php";

$user_id = $_SESSION['user_id'];

/* ATTENDANCE STATISTICS */

$current_month = date("m");
$current_year = date("Y");

/* count present days */

$sql_present = "SELECT COUNT(*) AS present_days
FROM attendance
WHERE user_id='$user_id'
AND MONTH(attendance_date)='$current_month'
AND YEAR(attendance_date)='$current_year'";

$result_present = mysqli_query($conn,$sql_present);
$data_present = mysqli_fetch_assoc($result_present);

$present_days = $data_present['present_days'] ?? 0;

/* calculate days passed this month */

$today_day = date("j");

/* assume school days = days passed */

$total_days = $today_day;

/* absent calculation */

$absent_days = $total_days - $present_days;

if($total_days > 0){
$attendance_rate = round(($present_days / $total_days) * 100);
}else{
$attendance_rate = 0;
}

$sql = "SELECT * FROM users WHERE user_id='$user_id'";
$result = mysqli_query($conn,$sql);
$user = mysqli_fetch_assoc($result);

$first = $user['first_name'] ?? '';
$role = $user['role'] ?? '';
$grade = $user['grade'] ?? '';
$school_id = $user['school_id'] ?? '';


/* =========================
   TODAY ATTENDANCE QUERY
   ========================= */

$today = date("Y-m-d");

$sql_today = "SELECT * FROM attendance
WHERE user_id='$user_id'
AND attendance_date='$today'";

$res_today = mysqli_query($conn,$sql_today);
$today_record = mysqli_fetch_assoc($res_today);

$time_in = $today_record['time_in'] ?? "--:--";
$time_out = $today_record['time_out'] ?? "--:--";
$total_hours = $today_record['total_hours'] ?? "0";

$status = "Not Started";

if($time_in != "--:--" && $time_out == "--:--"){
$status = "Timed In";
}

if($time_in != "--:--" && $time_out != "--:--"){
$status = "Completed";
}

$can_time_in = ($time_in == "--:--");
$can_time_out = ($time_in != "--:--" && $time_out == "--:--");

/* TODAY ATTENDANCE */

$today = date("Y-m-d");

$sql_today = "SELECT * FROM attendance
WHERE user_id='$user_id'
AND attendance_date='$today'";

$result_today = mysqli_query($conn,$sql_today);
$today_data = mysqli_fetch_assoc($result_today);

$time_in = $today_data['time_in'] ?? null;
$time_out = $today_data['time_out'] ?? null;

$status = "Not Timed In";

if($time_in && !$time_out){
$status = "Currently Timed In";
}

if($time_in && $time_out){
$status = "Completed";
}

/* calculate hours */

$total_hours = $today_data['total_hours'] ?? 0;

?>

<!DOCTYPE html>
<html>
<head>
<title>STEMTrack Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

<body>

<div class="page">

<?php include "../includes/sidebar.php"; ?>

    <!-- MAIN WHOLE AREA -->
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

        <!-- MAIN CONTENT -->
        <div class="dashboard-grid">

            <div class="left-column">

                <div class="card welcome-card">
                    <h2>Welcome <?php echo htmlspecialchars($first); ?></h2>
                    <p><b>Role:</b> <?php echo !empty($role) ? ucfirst(htmlspecialchars($role)) : 'Not set'; ?></p>

                    <?php if(!empty($grade)){ ?>
                    <p><b>Grade:</b> <?php echo htmlspecialchars($grade); ?></p>
                    <?php } ?>

                    <p><b>School ID:</b> <?php echo htmlspecialchars($school_id); ?></p>
                    <?php
                        $status_class = "status-not";

                        if($status == "Timed In"){
                        $status_class = "status-in";
                        }

                        if($status == "Completed"){
                        $status_class = "status-done";
                        }
                        ?>

                        <p><b>Status:</b> <span class="status-badge <?php echo $status_class; ?>"><?php echo $status; ?></span></p>
                </div>

                <div class="card">
                    <h2 class="section-title">Attendance Today</h2>

                    <div class="attendance-grid">

                        <div class="attendance-card">

                            <h3>Time In</h3>

                            <p><?php echo $time_in; ?></p>

                            <?php if($can_time_in){ ?>

                            <form method="POST" action="attendance_action.php">
                            <input type="hidden" name="action" value="time_in">
                            <button class="main-btn">Time In</button>
                            </form>

                            <?php } else { ?>

                            <p style="font-size:14px;color:#777;">Already timed in</p>

                            <?php } ?>

                        </div>

                        <div class="attendance-card">

                            <h3>Time Out</h3>

                            <?php if($can_time_out){ ?>

                            <form method="POST" action="attendance_action.php">
                            <input type="hidden" name="action" value="time_out">
                            <button class="main-btn">Time Out</button>
                            </form>

                            <?php } else { ?>

                            <p style="font-size:14px;color:#777;">Time in first</p>

                            <?php } ?>

                        </div>

                        <div class="attendance-card">
                            <h3>Total Hours</h3>
                            <p><?php echo $total_hours; ?> hrs</p>
                        </div>

                    </div>
                </div>

            </div>

            <div class="right-column">

                <div class="card info-card">
                    <h3>Today Summary</h3>
                    <p><b>Current Time:</b></p>
                    <h2 id="live-clock">--:--:--</h2>
                    <p><b>Status:</b> <?php echo $status; ?></p>
                    <p><b>Hours Today:</b> <?php echo $total_hours; ?></p>
                </div>

                <div class="card info-card">
                    <h3>Progress</h3>
                    <p>Weekly attendance goal</p>
                    <div class="progress-bar-wrap">
                        <div class="progress-bar" style="width:<?php echo min($attendance_rate,100); ?>%"></div>
                    </div>
                </div>

                <div class="card info-card">
                    <h3>Attendance Summary</h3>
                    <p><b>Days Present:</b> <?php echo $present_days; ?></p>
                    <p><b>Days Absent:</b> <?php echo $absent_days; ?></p>
                    <p><b>Attendance Rate:</b> <?php echo $attendance_rate; ?>%</p>
                </div>
    
            </div>

        </div>

    </main>

</div>

<script>

function updateClock(){

let now = new Date();

let hours = now.getHours();
let minutes = now.getMinutes();
let seconds = now.getSeconds();

let ampm = hours >= 12 ? "PM" : "AM";

hours = hours % 12;
hours = hours ? hours : 12;

minutes = minutes < 10 ? "0"+minutes : minutes;
seconds = seconds < 10 ? "0"+seconds : seconds;

let timeString = hours + ":" + minutes + ":" + seconds + " " + ampm;

document.getElementById("live-clock").innerText = timeString;

}

setInterval(updateClock,1000);

updateClock();

</script>

</body>
</html>