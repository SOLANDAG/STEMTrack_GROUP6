<?php
session_start();

$current_page = basename($_SERVER['PHP_SELF']);

include "../config/database.php";

if(!isset($_SESSION['user_id'])){
header("Location: ../auth/login.php");
exit();
}

$role = $_SESSION['role'];

if($role != "admin"){
echo "Access denied";
exit();
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Schedule Manager</title>

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
<img src="../assets/images/StemLogo_no-bg.png" alt="STEM Logo">
</div>

<div class="top-right">
<img src="../assets/images/MAPUA LOGO.png" alt="Mapua Logo">
</div>

</div>

<div class="card">

<h2 class="section-title">Upload School Schedule</h2>

<p>Upload a CSV file containing the school calendar.</p>

<br>

<form action="upload_schedule.php" method="POST" enctype="multipart/form-data">

<div class="file-upload">

<input type="file" id="schedule_file" name="schedule_file" accept=".csv" required>

<div class="file-ui">

<label for="schedule_file" class="file-btn">
Choose CSV File
</label>

<div id="file-name" class="file-name">
No file selected
</div>

</div>

</div>

<br><br>

<button class="main-btn">Upload Schedule</button>

</form>

<div id="csv-preview" class="card" style="display:none;margin-top:20px;">

<h3>CSV Preview</h3>

<table class="history-table" id="preview-table">

<thead>
<tr>
<th>Date</th>
<th>Type</th>
</tr>
</thead>

<tbody></tbody>

</table>

</div>

<br>

<p><b>CSV Format Example:</b></p>

<pre>
date,type
2026-03-01,class
2026-03-02,holiday(Christmas Day)
2026-03-03,no_class
</pre>

</div>

</main>

</div>

<script>

const input = document.getElementById("schedule_file");
const fileName = document.getElementById("file-name");

input.addEventListener("change", function(){

if(this.files.length > 0){
fileName.textContent = this.files[0].name;
}

});

</script>

<script>

document.addEventListener("DOMContentLoaded", function(){

const fileInput = document.getElementById("schedule_file");
const fileName = document.getElementById("file-name");
const preview = document.getElementById("csv-preview");
const tableBody = document.querySelector("#preview-table tbody");

if(!fileInput) return;

fileInput.addEventListener("change", function(){

const file = this.files[0];

if(!file) return;

fileName.textContent = file.name;

const reader = new FileReader();

reader.onload = function(e){

const rows = e.target.result.split("\n");

tableBody.innerHTML = "";

rows.slice(1,6).forEach(row => {

const cols = row.split(",");

if(cols.length >= 2){

tableBody.innerHTML += `
<tr>
<td>${cols[0]}</td>
<td>${cols[1]}</td>
</tr>
`;

}

});

preview.style.display = "block";

};

reader.readAsText(file);

});

});

</script>

</body>
</html>