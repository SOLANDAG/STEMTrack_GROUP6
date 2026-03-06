<?php
session_start();
include "../config/database.php";

$message = "";

if (isset($_POST['login'])) {

    $login_input = trim($_POST['login_input']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$login_input' OR school_id='$login_input'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['first_name'] = $user['first_name'];

            if ($user['role'] == "student") {
                header("Location: ../user/dashboard.php");
            }
            elseif ($user['role'] == "faculty") {
                header("Location: ../faculty/dashboard.php");
            }
            elseif ($user['role'] == "admin") {
                header("Location: ../admin/dashboard.php");
            }

            exit();
        }
        else {
            $message = "Incorrect password";
        }
    }
    else {
        $message = "Account not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Login</title>

<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body class="login-bg">

<div class="auth-container">

<div class="auth-box">

<h2>Welcome Back</h2>

<?php if($message != "") { ?>

<p class="error"><?php echo $message; ?></p>

<?php } ?>

<form method="POST">

<input type="text" name="login_input" placeholder="Email or ID Number" required>

<input type="password" name="password" placeholder="Password" required>

<button type="submit" name="login">Login</button>

<p class="switch">Don't have an account? <a href="register.php">Register</a></p>

</form>

</div>

</div>

</body>
</html>