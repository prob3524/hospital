<?php
session_start();
require_once "../connection.php";
// Check cookies exists or not
if (!isset($_SESSION['user'])) {
  $_SESSION['success'] = false;
  $_SESSION['message'] = "Authentication failed";
  header("location: $appUrl/login.php");
} else if (isset($_SESSION["role"]) && $_SESSION["role"] != "patients") {
  setcookie('user', '', time() - 3600, '/');
  $_SESSION['success'] = false;
  $_SESSION['message'] = "You are not authorized to access the Patient site.";
  header("location: $appUrl/login.php");
  exit;
}
$pageTitle = "Dashboard";
require_once "../components/header.php";

// Count Data to show at dashboard
try {
  $loggedInPatientId = $_SESSION['id'];

  $patientAppointments = $connection->prepare("SELECT COUNT(*) as bookedAppointments FROM appointments WHERE patient_id = ?");
  $patientAppointments->bind_param("i", $loggedInPatientId);
  $patientAppointments->execute();
  $appointmentsResult = $patientAppointments->get_result();
  $totalPatientAppointments = $appointmentsResult->fetch_assoc()['bookedAppointments'];
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}

?>
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
  <!-- Sidebar Start -->
  <aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
      <div class="brand-logo d-flex align-items-center justify-content-between">
        <a href="<?php echo $appUrl; ?>" class="navbar-brand" style="font-size:30px">
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
  <!--  Sidebar End -->
  <!--  Main wrapper -->
  <div class="body-wrapper">
    <!--  Header Start -->
    <?php require_once "../components/profileHeader.php" ?>
    <!--  Header End -->
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
        <h3>Dashboard</h3>
      </div>
    </div>
    <div class="row dashboard-widget-p-5">
      <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 mb-3 dashboard-widget mt-md-0 mt-sm-0 mt-3">
        <a href="/patient/appointments.php"> <!-- Assuming a specific page for patient appointments -->
          <div class="bg-warning cursor-pointer gradient-style1 text-white box-shadow widget-style3 rounded">
            <div class="d-flex flex-wrap align-items-center">
              <div class="widget-data">
                <div class="" style="font-weight: 400;font-size: 20px;line-height: 1.5em;">Your Appointments</div>
                <div class="" style="font-weight: 300;font-size: 30px;line-height: 1.46em;"><?php echo $totalPatientAppointments; ?></div>
              </div>
              <div>
                <div><i class="fa-solid fa-calendar-check" style="font-size:1.875rem"></i></div>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
</div>

<script src="./patient.js"></script>
<?php
require_once "../components/footer.php";
?>