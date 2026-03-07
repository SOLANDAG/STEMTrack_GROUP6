<?php
session_start();
include "../config/database.php";

$message="";

if(isset($_POST['login'])){

$login_input=trim($_POST['login_input']);
$password=$_POST['password'];

$sql="SELECT * FROM users WHERE email='$login_input' OR school_id='$login_input'";
$result=mysqli_query($conn,$sql);

if($result && mysqli_num_rows($result)==1){

$user=mysqli_fetch_assoc($result);

if(password_verify($password,$user['password'])){

$_SESSION['user_id']=$user['user_id'];
$_SESSION['role']=$user['role'];
$_SESSION['first_name']=$user['first_name'];
$_SESSION['school_id']=$user['school_id'];

// REDIRECT TO DASHBOARD
header("Location: ../dashboard/dashboard.php");
exit();

}else{
$message="Incorrect password";
}

}else{
$message="Account not found";
}

}
?>

<!DOCTYPE html>
<html>

<head>

<title>Login</title>

<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body class="login-bg">

<div class="auth-container login-layout">

<div class="auth-box login-box">

<h2>Welcome Back</h2>

<?php if($message!=""){ ?>
<p class="error"><?php echo $message; ?></p>
<?php } ?>

<form method="POST">

<input type="text" name="login_input" placeholder="Email or ID Number" required>

<input type="password" name="password" placeholder="Password" required>

<button type="submit" name="login" class="main-btn">Login</button>

<p class="switch">Don't have an account? <a href="register.php">Register</a></p>

</form>

</div>

</div>

</body>

</html>