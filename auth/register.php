<?php
session_start();
include "../config/database.php";

$message="";

if(isset($_POST['register'])){

$first=$_POST['first_name'];
$mi=$_POST['middle_initial'];
$last=$_POST['last_name'];
$email=$_POST['email'];
$password=$_POST['password'];
$confirm=$_POST['confirm_password'];
$role=$_POST['role'];
$grade=$_POST['grade'];

if(empty($role)){
$message="Please select a role";
}

elseif($role=="student" && empty($grade)){
$message="Students must select a grade level";
}

elseif($password!=$confirm){
$message="Passwords do not match";
}

else{

// GENERATE SCHOOL ID
$prefix = ($role=="student") ? "10" : "20";
$random = rand(100000,999999);
$school_id = $prefix.$random;

$hashed=password_hash($password,PASSWORD_DEFAULT);

$sql="INSERT INTO users (school_id,first_name,middle_initial,last_name,email,password,role,grade)
VALUES ('$school_id','$first','$mi','$last','$email','$hashed','$role','$grade')";

if(mysqli_query($conn,$sql)){

// AUTO LOGIN AFTER REGISTER
$_SESSION['user_id']=mysqli_insert_id($conn);
$_SESSION['first_name']=$first;
$_SESSION['role']=$role;
$_SESSION['school_id']=$school_id;

// REDIRECT TO DASHBOARD
header("Location: ../dashboard/dashboard.php");
exit();

}else{
$message="Error creating account";
}

}
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Register</title>

<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">

<script src="../assets/js/auth.js"></script>

</head>

<body class="register-bg">

<div class="auth-container register-layout">

<div class="auth-box register-box">

<h2>Create Account</h2>

<?php if($message!=""){ ?>
<p class="success"><?php echo $message; ?></p>
<?php } ?>

<form method="POST">

<div class="name-row">

<input type="text" name="first_name" placeholder="First Name" class="first-name" required>

<input type="text" name="middle_initial" placeholder="MI" class="middle-name">

<input type="text" name="last_name" placeholder="Last Name" class="last-name" required>

</div>

<div class="divider"></div>

<div class="role-title">SELECT ROLE</div>

<input type="hidden" name="role" id="role">
<input type="hidden" name="grade" id="grade">

<div class="role-buttons">

<button type="button" class="role-btn" onclick="selectRole('student',this)">STUDENT</button>

<button type="button" class="role-btn" onclick="selectRole('faculty',this)">FACULTY</button>

</div>

<div class="grade-section" id="gradeSection">

<div class="role-title">SELECT YEAR / GRADE</div>

<div class="grade-buttons">

<button type="button" class="grade-btn grade1" onclick="selectGrade(this)">1ST YEAR</button>
<button type="button" class="grade-btn grade2" onclick="selectGrade(this)">2ND YEAR</button>
<button type="button" class="grade-btn grade3" onclick="selectGrade(this)">3RD YEAR</button>
<button type="button" class="grade-btn grade4" onclick="selectGrade(this)">4TH YEAR</button>
<button type="button" class="grade-btn grade11" onclick="selectGrade(this)">GRADE 11</button>
<button type="button" class="grade-btn grade12" onclick="selectGrade(this)">GRADE 12</button>

</div>

</div>

<div class="divider"></div>

<input type="email" name="email" placeholder="Email" required>

<input type="password" id="password" name="password" placeholder="Password" required>

<div id="password-strength" style="display:none;">

<div class="strength-bar">
<div id="strength-fill"></div>
</div>

<p id="strength-text" style="font-size:12px;margin-top:6px;color:white;">
Use 6+ characters, one capital letter, and one symbol.
</p>

</div>

<input type="password" name="confirm_password" placeholder="Confirm Password" required>

<button type="submit" name="register" class="main-btn">Register</button>

<p class="switch">Already have an account? <a href="login.php">Login</a></p>

</form>

</div>

</div>

<script>

function selectRole(role,btn){

document.getElementById("role").value=role;

document.querySelectorAll(".role-btn").forEach(b=>{
b.classList.remove("active");
});

btn.classList.add("active");

if(role==="student"){
document.getElementById("gradeSection").style.display="block";
}else{
document.getElementById("gradeSection").style.display="none";
}

}

function selectGrade(btn){

document.querySelectorAll(".grade-btn").forEach(b=>{
b.classList.remove("active");
});

btn.classList.add("active");

// SAVE GRADE VALUE
document.getElementById("grade").value = btn.innerText;

}

/* PASSWORD STRENGTH CHECK */

document.addEventListener("DOMContentLoaded", function(){

const passwordInput = document.getElementById("password");
const strengthFill = document.getElementById("strength-fill");
const strengthText = document.getElementById("strength-text");
const strengthBox = document.getElementById("password-strength");

if(!passwordInput) return;

passwordInput.addEventListener("input", function(){

let password = passwordInput.value;

if(password.length === 0){
strengthBox.style.display = "none";
strengthFill.style.width = "0%";
return;
}

strengthBox.style.display = "block";

/* RULES */

let hasLength = password.length >= 6;
let hasUpper = /[A-Z]/.test(password);
let lowerCount = (password.match(/[a-z]/g) || []).length;
let numberCount = (password.match(/[0-9]/g) || []).length;
let hasSpecial = /[^A-Za-z0-9]/.test(password);

let hasLower = lowerCount >= 3;
let hasNumbers = numberCount >= 2;

/* SCORE */

let score = 0;

if(hasLength) score++;
if(hasUpper) score++;
if(hasLower) score++;
if(hasNumbers) score++;
if(hasSpecial) score++;

/* STRENGTH BAR */

if(score <= 2){

strengthFill.style.width = "30%";
strengthFill.style.background = "red";
strengthText.innerText = "Weak password";

}
else if(score <= 4){

strengthFill.style.width = "70%";
strengthFill.style.background = "orange";
strengthText.innerText = "Medium password";

}
else{

strengthFill.style.width = "100%";
strengthFill.style.background = "green";
strengthText.innerText = "Strong password";

}

});

});

</script>

</body>

</html>