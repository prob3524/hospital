<?php
session_start();
include "../connection.php";
// Check cookies exists or not
if (!isset($_SESSION['user'])) {
  $_SESSION['success'] = false;
  $_SESSION['message'] = "Authentication failed";
   header("location: $appUrl/login.php");
}
else if (isset($_SESSION["role"]) && $_SESSION["role"] != "receptionists") {
  setcookie('user', '', time() - 3600, '/');
  $_SESSION['success'] = false;
  $_SESSION['message'] = "You are not authorized to access the receptionist site.";
   header("location: $appUrl/login.php");
  exit;
}
$pageTitle = "Patient";
require_once "../components/header.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch tour package details from the database based on $id
    $sql = "SELECT * FROM patients WHERE id = $id";
    $result = mysqli_query($connection, $sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row['id'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $email = $row['email'];
        $phone_no = $row['phone_no'];
        $gender = $row['gender'] == 1 ? "Female" : "Male";
        $created_at = $row['created_at'];
        $updated_at = $row['updated_at'];
    } else {
        echo "Patient not found.";
    }
} else {
    echo "Invalid ID.";
}

?>

<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
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

        <div>
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
                <div class="d-flex justify-content-between">
                    <h3>View Patient</h3>
                    <a class="btn btn-outline-secondary" href="./patients.php">Back</a>
                </div>
                <div class="container-fluid" style="padding-top:0">
                    <div class="card mt-3">
                        <div class="pt-0 card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td class="py-4 fw-bold">Name:</td>
                                            <td class="py-4"><?php echo $first_name . " " . $last_name; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="py-4 fw-bold">Email:</td>
                                            <td class="py-4"><?php echo $email; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="py-4 fw-bold">Phone no.:</td>
                                            <td class="py-4"><?php echo $phone_no; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="py-4 fw-bold">Gender:</td>
                                            <td class="py-4"><?php echo $gender; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="py-4 fw-bold">Created At:</td>
                                            <td class="py-4"><?php echo $created_at; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="py-4 fw-bold">Updated At:</td>
                                            <td class="py-4"><?php echo $updated_at; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="./receptionist.js"></script>
<?php
require_once("../components/footer.php");
?>