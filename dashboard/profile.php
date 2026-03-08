<?php
session_start();

$current_page = basename($_SERVER['PHP_SELF']);

if(!isset($_SESSION['user_id'])){
header("Location: ../auth/login.php");
exit();
}

include "../config/database.php";

$user_id = $_SESSION['user_id'];

$sql="SELECT * FROM users WHERE user_id='$user_id'";
$result=mysqli_query($conn,$sql);
$user=mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>

<head>

<title>My Profile</title>

<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

<body>

<div class="page">

<?php include "../includes/sidebar.php"; ?>

<main class="main-shell">

<!-- TOP BAR -->
<div class="top-bar">

<div style="width:52px;"></div>

<div class="top-left">
<img src="../assets/images/StemLogo_no-bg.png">
</div>

<div class="top-right">
<img src="../assets/images/Mapua logo.png">
</div>

</div>

<div class="dashboard-grid">

<div class="left-column">

<div class="card">

<h2 class="section-title">My Profile</h2>

<p><b>Name:</b> <?php echo $user['first_name']." ".$user['last_name']; ?></p>

<p><b>Email:</b> <?php echo $user['email']; ?></p>

<p><b>Grade:</b> <?php echo $user['grade']; ?></p>

<p><b>School ID:</b> <?php echo $user['school_id']; ?></p>

<p><b>Role:</b> <?php echo ucfirst($user['role']); ?></p>

</div>

</div>

<div class="right-column">

<div class="card">

<h3>Change Password</h3>

<form method="POST" action="update_password.php">

<input class="input-field" type="password" name="current_password" placeholder="Current Password" required>

<input class="input-field" type="password" id="password" name="new_password" placeholder="New Password" required>

<div class="strength-bar">
<div id="strength-fill"></div>
</div>

<p id="strength-text" style="font-size:13px;margin-top:5px;color:#555;">
Use uppercase letters, numbers, and special characters.
</p>

<input class="input-field" type="password" name="confirm_password" placeholder="Confirm Password" required>

<br><br>

<button class="main-btn">Update Password</button>

</form>

</div>

</div>

</div>

</main>

</div>

</body>
</html>