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

            <a href="/STEMTrack/STEMTrack_Group6/admin/attendance_monitor.php"
            class="<?= ($current_page == 'attendance_monitor.php') ? 'active' : '' ?>">
            Monitor
            </a>

            <?php } ?>

            <a href="/STEMTrack/STEMTrack_Group6/dashboard/profile.php"
            class="<?= ($current_page == 'profile.php') ? 'active' : '' ?>">
            Profile
            </a>

            <a href="/STEMTrack/STEMTrack_Group6/auth/logout.php">
            Logout
            </a>

        </nav>
    </aside>