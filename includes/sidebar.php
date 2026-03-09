<!-- SIDEBAR -->
<aside class="sidebar">

    <div class="sidebar-logo">
        <a href="/STEMTrack/STEMTrack_Group6/dashboard/dashboard.php">
            <img src="../assets/images/KNHS-Logo-1.png" alt="KNHS Logo">
        </a>
    </div>

    <nav>

        <a href="/STEMTrack/STEMTrack_Group6/dashboard/dashboard.php"
        class="<?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">
        Dashboard
        </a>

        <a href="/STEMTrack/STEMTrack_Group6/dashboard/attendance.php"
        class="<?= ($current_page == 'attendance.php') ? 'active' : '' ?>">
        Attendance
        </a>

        <a href="/STEMTrack/STEMTrack_Group6/dashboard/history.php"
        class="<?= ($current_page == 'history.php') ? 'active' : '' ?>">
        History
        </a>

        <?php if($_SESSION['role']=="admin" || $_SESSION['role']=="faculty"){ ?>

            <?php if($_SESSION['role']=="admin"){ ?>

            <a href="/STEMTrack/STEMTrack_Group6/admin/schedule_manager.php"
            class="<?= ($current_page == 'schedule_manager.php') ? 'active' : '' ?>">
            Schedule
            </a>

            <?php } ?>

            <a href="/STEMTrack/STEMTrack_Group6/admin/attendance_monitor.php"
            class="<?= ($current_page == 'attendance_monitor.php') ? 'active' : '' ?>">
            Monitor
            </a>

        <?php } ?>

        <a href="/STEMTrack/STEMTrack_Group6/dashboard/profile.php"
        class="<?= ($current_page == 'profile.php') ? 'active' : '' ?>">
        Profile
        </a>

        <!-- LOGOUT BUTTON -->
        <a href="#" id="logout-btn">Logout</a>

    </nav>

</aside>


<!-- LOGOUT MODAL -->
<div id="logout-modal" class="logout-modal">

    <div class="logout-box">

        <h3>Logout</h3>

        <p>Are you sure you want to log out?</p>

        <div class="logout-buttons">

            <a href="/STEMTrack/STEMTrack_Group6/auth/logout.php" class="logout-confirm">
            Logout
            </a>

            <button id="logout-cancel">Return</button>

        </div>

    </div>

</div>


<script>

const logoutBtn = document.getElementById("logout-btn");
const modal = document.getElementById("logout-modal");
const cancel = document.getElementById("logout-cancel");

logoutBtn.onclick = (e)=>{
    e.preventDefault();
    modal.classList.add("show");
};

cancel.onclick = ()=>{
    modal.classList.remove("show");
};

window.onclick = (e)=>{
    if(e.target === modal){
        modal.classList.remove("show");
    }
};

document.addEventListener("keydown", function(e){
    if(e.key === "Escape"){
        modal.classList.remove("show");
    }
});

</script>