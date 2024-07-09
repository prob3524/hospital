<?php
session_start();
include "../connection.php";

// Check cookies exist or not
if (!isset($_SESSION['user'])) {
    $_SESSION['success'] = false;
    $_SESSION['message'] = "Authentication failed";
    header("location: $appUrl/login.php");
    exit;
} else if (isset($_SESSION["role"]) && $_SESSION["role"] != "admins") {
    setcookie('user', '', time() - 3600, '/');
    $_SESSION['success'] = false;
    $_SESSION['message'] = "You are not authorized to access the admin site.";
    header("location: $appUrl/login.php");
    exit;
}

$pageTitle = "Assign Bed";
require_once "../components/header.php";

// Fetch all patients
// $patients_query = "SELECT id, CONCAT(first_name, ' ', last_name) as name FROM patients";
// $patients_result = mysqli_query($connection, $patients_query);

// Fetch all patients who do not have a bed assigned
$patients_query = "
    SELECT p.id, CONCAT(p.first_name, ' ', p.last_name) AS name 
    FROM patients p
    LEFT JOIN bed_assignments ba ON p.id = ba.ipd_patient_department_id
    WHERE ba.ipd_patient_department_id IS NULL
";
$patients_result = mysqli_query($connection, $patients_query);


// Fetch all beds
$beds_query = "SELECT id, bed_name FROM beds where status = 1";
$beds_result = mysqli_query($connection, $beds_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and escape input data
    $ipd_patient_department_id = mysqli_real_escape_string($connection, $_POST['ipd_patient_department_id']);
    $bed_id = mysqli_real_escape_string($connection, $_POST['bed_id']);
    $assign_date = mysqli_real_escape_string($connection, $_POST['opd_date']);
    $status = isset($_POST['status']) ? 1 : 0;

    // Insert into bed_assignments table
    $insert_query = "INSERT INTO bed_assignments (ipd_patient_department_id, bed_id, assign_date, status) VALUES ('$ipd_patient_department_id', '$bed_id', '$assign_date', '$status')";

    if (mysqli_query($connection, $insert_query)) {
        // Update the bed's status to false (occupied)
        $update_bed_query = "UPDATE beds SET status = 0 WHERE id = '$bed_id'";
        mysqli_query($connection, $update_bed_query);

        $_SESSION['success'] = true;
        $_SESSION['message'] = "Bed assigned successfully";
        header("Location: ./bed-management.php");
        exit();
    } else {
        $_SESSION['success'] = false;
        $_SESSION['message'] = "Failed to assign bed";
    }
}
?>

<!--  Body Wrapper -->
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <aside class="left-sidebar">
        <div>
            <div class="brand-logo d-flex align-items-center justify-content-between">
                <a href="<?php echo $appUrl; ?>" class="navbar-brand" style="font-size:30px">
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

    <!--  Main wrapper -->
    <div class="body-wrapper">

        <!--  Header Start -->
        <?php require_once "../components/profileHeader.php" ?>
        <!--  Header End -->

        <!-- Patient Table start-->
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
          };
          toastr.$toastType('$message');
        </script>";
                unset($_SESSION['message']);
                unset($_SESSION['success']);
            }
            ?>
            <div class="d-flex justify-content-between align-items-center">
                <h3>Assign Bed</h3>
                <a class="btn btn-outline-secondary" href="./bed-management.php">Back</a>
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    <form method="POST" accept-charset="UTF-8" id="createBedAssign" data-select2-id="select2-data-[object HTMLInputElement]">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-5">
                                    <label for="ipd_patient_department_id" class="form-label required">IPD Patient:</label>
                                    <select class="form-select select2-hidden-accessible" required="" id="ipdPatientId" data-control="select2" name="ipd_patient_department_id" tabindex="-1" aria-hidden="true" data-select2-id="select2-data-ipdPatientId">
                                        <option selected="selected" value="" data-select2-id="select2-data-443-3zxm">Choose IPD Patient</option>
                                        <?php while ($patient = mysqli_fetch_assoc($patients_result)) : ?>
                                            <option value="<?php echo $patient['id']; ?>"><?php echo $patient['name']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                    <input class="ipdPatientId" name="ipd_patient_id" type="hidden" value="">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-5">
                                    <label for="bed_id" class="form-label">Bed:</label>
                                    <span class="required"></span>
                                    <select class="form-select select2-hidden-accessible" required="" id="BedAssignBedId" data-control="select2" name="bed_id" tabindex="-1" aria-hidden="true" data-select2-id="select2-data-BedAssignBedId">
                                        <option value="">Choose Bed</option>
                                        <?php while ($bed = mysqli_fetch_assoc($beds_result)) : ?>
                                            <option value="<?php echo $bed['id']; ?>"><?php echo $bed['bed_name']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-5">
                                    <label for="assign_date" class="form-label required">Assign Date:</label>
                                    <input class="form-control opdDate" autocomplete="off" id="opdDate" required="" name="opd_date" type="date">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-5">
                                    <label for="status" class="form-label">Status:</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input w-35px h-20px switch-input is-active" name="status" type="checkbox" value="1" checked="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <input class="btn btn-primary me-2" id="BedAssignSaveBtn" type="submit" value="Save">
                            <a href="./beds.php" class="btn btn-secondary me-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="./admin.js"></script>
<script>
    // Get today's date in the format YYYY-MM-DD
    var today = new Date().toISOString().split('T')[0];
    
    // Set the minimum date attribute of the date field
    document.getElementById("opdDate").setAttribute("min", today);
</script>

<?php
require_once("../components/footer.php");
?>