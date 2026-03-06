<?php
include "../config/database.php";

$message = "";

if(isset($_POST['register'])){

$first = $_POST['first_name'];
$mi = $_POST['middle_initial'];
$last = $_POST['last_name'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm = $_POST['confirm_password'];
$role = $_POST['role'];

if($password != $confirm){

$message = "Passwords do not match";

}
else{

if($role == "student"){
$prefix = "10";
}
else{
$prefix = "20";
}

$random = rand(100000,999999);
$school_id = $prefix . $random;

$hashed = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (school_id, first_name, middle_initial, last_name, email, password, role)
VALUES ('$school_id','$first','$mi','$last','$email','$hashed','$role')";

if(mysqli_query($conn,$sql)){
$message = "Account created! Your ID: $school_id";
}
else{
$message = "Error creating account";
}

}
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Register</title>

<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body class="register-bg">

<div class="auth-container">

<div class="auth-box">

<h2>Create Account</h2>

<?php if($message != "") { ?>

<p class="success"><?php echo $message; ?></p>

<?php } ?>

<form method="POST">

<input type="text" name="first_name" placeholder="First Name" required>

<input type="text" name="middle_initial" placeholder="Middle Initial">

<input type="text" name="last_name" placeholder="Last Name" required>

<select name="role" required>

<option value="">Select Role</option>
<option value="student">Student</option>
<option value="faculty">Faculty</option>

</select>

<input type="email" name="email" placeholder="Email">

<input type="password" name="password" placeholder="Password">

<input type="password" name="confirm_password" placeholder="Confirm Password">

<button type="submit" name="register">Register</button>

<p class="switch">Already have an account? <a href="login.php">Login</a></p>

</form>

</div>

</div>

</body>
</html>