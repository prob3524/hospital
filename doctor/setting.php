<?php
session_start();
require_once "../connection.php";

// Check cookies exist or not
if (!isset($_SESSION['user'])) {
    $_SESSION['success'] = false;
    $_SESSION['message'] = "Authentication failed";
    header("location: $appUrl/login.php");
    exit;
} else if (isset($_SESSION["role"]) && $_SESSION["role"] != "doctors") {
    setcookie('user', '', time() - 3600, '/');
    $_SESSION['success'] = false;
    $_SESSION['message'] = "You are not authorized to access the Doctor site.";
    header("location: $appUrl/login.php");
    exit;
}

$pageTitle = "Appointment Setting";
require_once "../components/header.php";
?>

<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
        <!-- Sidebar scroll-->
        <div>
            <div class="brand-logo d-flex align-items-center justify-content-between">
                             <a href="<?php echo $appUrl;?>" class="navbar-brand" style="font-size:30px">
                    <!-- <div class="d-flex align-items-center"><img src="../uploads/settings/<?php echo $_SESSION['logo'] ?>" class="img-fluid" alt="logo" width="50" height="50"><span class="mx-2 my-1" style="font-size:20px"><?php echo $_SESSION['site_name'] ?></span></div> -->
                    Hospital
                </a>
                <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                    <i class="fa fa-times"></i>
                </div>
            </div>
            <!-- Sidebar navigation-->
            <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                <div class="sidebar">
                    <ul id="sideNav">
                    </ul>
                </div>
            </nav>
        </div>
    </aside>
    <!-- Sidebar End -->

    <!-- Main wrapper -->
    <div class="body-wrapper">
        <!-- Header Start -->
        <?php require_once "../components/profileHeader.php" ?>
        <!-- Header End -->

        <div class="p-5">
            <?php
            if (isset($_SESSION['message']) && isset($_SESSION['success'])) {
                $message = $_SESSION['message'];
                $success = $_SESSION['success'];
                $toastType = $success ? 'success' : 'error';
                echo "<script>
                toastr.options = {
                    positionClass: 'toast-top-right',
                    timeOut: 2000,
                    progressBar: true,
                }; toastr.$toastType('$message');</script>";
                unset($_SESSION['message']);
                unset($_SESSION['success']);
            }
            ?>
            <div>
                <h3>Appointment Setting</h3>
            </div>
        </div>

        <div class="container mt-5">
            <form action="../queries.php" method="post">
                <!-- Additional features -->
                <div class="form-group">
                    <label for="scheduleGap">Schedule Gap (in minutes):</label>
                    <input type="number" class="form-control" id="scheduleGap" name="scheduleGap">
                </div>

                <div class="form-group mb-5">
                    <label for="meetingTiming">Meeting Timing (in minutes):</label>
                    <input type="number" class="form-control" id="meetingTiming" name="meetingTiming">
                </div>

                <?php
                $daysOfWeek = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
                echo '<table class="table table-borderless w-50 d-flex align-items-center">';
                foreach ($daysOfWeek as $day) {
                    echo '<tr>';
                    echo '<td class="form-check">';
                    echo '<input type="checkbox" id="' . strtolower($day) . 'Checkbox" name="' . strtolower($day) . 'Checkbox" ' . (isset($_POST[strtolower($day) . 'Checkbox']) && $_POST[strtolower($day) . 'Checkbox'] == "on" ? 'checked' : '') . ' style="transform: scale(1.5);">'; // Added style for larger checkbox
                    echo '<label class="mx-2" for="' . strtolower($day) . 'Checkbox">' . $day . ':</label>';
                    echo '</td>';
                    echo '<td>';
                    echo '<input type="text" name="' . strtolower($day) . 'StartTime" id="' . strtolower($day) . 'StartTime" class="form-control w-100 mx-2" placeholder="Select start time" disabled>';
                    echo '</td>';
                    echo '<td>';
                    echo '<input type="text" name="' . strtolower($day) . 'EndTime" id="' . strtolower($day) . 'EndTime" class="form-control w-100" placeholder="Select end time" disabled>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</table>';
                ?>
                <input type="submit" value="Add Schedule" name="schedule" class="btn btn-primary mt-3" />
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="./doctor.js"></script>
<script>
    <?php
    foreach ($daysOfWeek as $day) {
        echo 'flatpickr("#' . strtolower($day) . 'StartTime, #' . strtolower($day) . 'EndTime", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
        });';
        echo 'document.getElementById("' . strtolower($day) . 'Checkbox").addEventListener("change", function() {
            document.getElementById("' . strtolower($day) . 'StartTime").disabled = !this.checked;
            document.getElementById("' . strtolower($day) . 'EndTime").disabled = !this.checked;
        });';
    }
    ?>
</script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<?php
require_once "../components/footer.php";
?>