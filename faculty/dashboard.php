<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty') {
    header("Location: ../auth/login.php");
    exit();
}
?>

<h1>Faculty Dashboard</h1>
<p>Welcome, <?php echo $_SESSION['first_name']; ?>!</p>
<a href="../auth/logout.php">Logout</a>