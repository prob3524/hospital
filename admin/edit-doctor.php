<?php
session_start();
include "../connection.php";

// Check cookies exist or not
if (!isset($_SESSION['user'])) {
    $_SESSION['success'] = false;
    $_SESSION['message'] = "Authentication failed";
    header("location: $appUrl/login.php");
} else if (isset($_SESSION["role"]) && $_SESSION["role"] != "admins") {
    setcookie('user', '', time() - 3600, '/');
    $_SESSION['success'] = false;
    $_SESSION['message'] = "You are not authorized to access the admin site.";
    header("location: $appUrl/login.php");
    exit;
}

$pageTitle = "Edit Doctor";
require_once "../components/header.php";

// Check if doctor ID is provided
if (isset($_GET['id'])) {
    $doctor_id = mysqli_real_escape_string($connection, $_GET['id']);

    // Fetch doctor data for pre-filling the form
    $fetch_doctor_query = "SELECT * FROM doctors WHERE id = '$doctor_id'";
    $result = mysqli_query($connection, $fetch_doctor_query);

    if ($result && mysqli_num_rows($result) > 0) {
        $doctor_data = mysqli_fetch_assoc($result);

        // Handle form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Sanitize and escape input data (prevent SQL injection)
            $first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
            $last_name = mysqli_real_escape_string($connection, $_POST['last_name']);
            $email = mysqli_real_escape_string($connection, $_POST['email']);
            $phone_no = mysqli_real_escape_string($connection, $_POST['phone_no']);
            $diagnosis_id = mysqli_real_escape_string($connection, $_POST['diagnosis_id']);
            $designation = mysqli_real_escape_string($connection, $_POST['designation']);
            $qualification = mysqli_real_escape_string($connection, $_POST['qualification']);
            $gender = mysqli_real_escape_string($connection, $_POST['gender']);
            $status = isset($_POST['status']) ? 1 : 0;
            $specialist = mysqli_real_escape_string($connection, $_POST['specialist']);

            // Update Doctors table
            $update_query = "UPDATE doctors SET
                first_name = '$first_name',
                last_name = '$last_name',
                email = '$email',
                phone_no = '$phone_no',
                diagnosis_id = '$diagnosis_id',
                designation = '$designation',
                qualification = '$qualification',
                gender = '$gender',
                status = '$status',
                specialist = '$specialist'
                WHERE id = '$doctor_id'";

            if (mysqli_query($connection, $update_query)) {
                $_SESSION['success'] = true;
                $_SESSION['message'] = "Doctor updated successfully";
                header("Location: $appUrl/admin/doctors.php");
                exit();
            }
        }
    } else {
        // Doctor not found
        $_SESSION['success'] = false;
        $_SESSION['message'] = "Doctor not found";
        header("Location: $appUrl/admin/doctors.php");
        exit();
    }
} else {
    // Doctor ID not provided
    $_SESSION['success'] = false;
    $_SESSION['message'] = "Doctor ID not provided";
    header("Location: $appUrl/admin/doctors.php");
    exit();
}
?>


<!--  Body Wrapper -->
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <aside class="left-sidebar">
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

        <!-- Doctor Table start-->
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
                <h3>Edit Doctor</h3>
                <a class="btn btn-outline-secondary" href="./doctors.php">Back</a>
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    <form method="POST" action="" accept-charset="UTF-8" id="createDoctorForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-5">
                                    <label for="first_name" class="form-label">First Name:</label>
                                    <span class="required"></span>
                                    <input class="form-control" required="" placeholder="First Name" name="first_name" type="text" value="<?php echo isset($doctor_data['first_name']) ? htmlspecialchars($doctor_data['first_name']) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-5">
                                    <label for="last_name" class="form-label">Last Name:</label>
                                    <span class="required"></span>
                                    <input class="form-control" required="" placeholder="Last Name" name="last_name" type="text" value="<?php echo isset($doctor_data['last_name']) ? htmlspecialchars($doctor_data['last_name']) : ''; ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-5">
                                    <label for="email" class="form-label">Email:</label>
                                    <span class="required"></span>
                                    <input class="form-control" required="" placeholder="Email" name="email" type="email" value="<?php echo isset($doctor_data['email']) ? htmlspecialchars($doctor_data['email']) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-5">
                                    <label for="phone_no" class="form-label">Phone No.:</label>
                                    <span class="required"></span>
                                    <input class="form-control" required="" placeholder="Phone No." name="phone_no" type="tel" value="<?php echo isset($doctor_data['phone_no']) ? htmlspecialchars($doctor_data['phone_no']) : ''; ?>">
                                </div>
                            </div>
                            <div class="form-group col-sm-6 mb-5">
                                <label for="diagnosis_id" class="form-label">Doctor Diagnosis:</label>
                                <span class="required"></span>
                                <select class="form-select select2-hidden-accessible" id="doctorsDepartmentId" data-control="select2" required="" name="diagnosis_id" tabindex="-1" aria-hidden="true" data-select2-id="select2-data-doctorsDepartmentId">
                                    <option selected="selected" value="" data-select2-id="select2-data-399-w4d5">Select Diagnosis</option>
                                    <?php
                                    // Assuming $connection is your database connection
                                    $diagnosis_query = "SELECT id, diagnosis_name FROM diagnosis";
                                    $department_result = mysqli_query($connection, $diagnosis_query);

                                    while ($row = mysqli_fetch_assoc($department_result)) {
                                        $id = $row['id'];
                                        $diagnosisName = $row['diagnosis_name'];
                                        $selected = isset($doctor_data['diagnosis_id']) && $doctor_data['diagnosis_id'] == $id ? 'selected' : '';
                                        echo "<option value=\"$id\" $selected>$diagnosisName</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-5">
                                    <label for="designation" class="form-label">Designation:</label>
                                    <span class="required"></span>
                                    <input class="form-control" required="" placeholder="Designation" name="designation" type="text" id="designation" value="<?php echo isset($doctor_data['designation']) ? htmlspecialchars($doctor_data['designation']) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-5">
                                    <label for="qualification" class="form-label">Qualification:</label>
                                    <span class="required"></span>
                                    <input class="form-control" required="" placeholder="Qualification" name="qualification" type="text" id="qualification" value="<?php echo isset($doctor_data['qualification']) ? htmlspecialchars($doctor_data['qualification']) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-5">
                                    <label for="gender" class="form-label">Gender:</label>
                                    <span class="required"></span> &nbsp;<br>
                                    <div class="d-flex align-items-center">
                                        <div class="form-check">
                                            <label class="form-label" for="doctorMale">Male</label>
                                            <input class="form-check-input" id="doctorMale" <?php echo isset($doctor_data['gender']) && $doctor_data['gender'] == 0 ? 'checked="checked"' : ''; ?> name="gender" type="radio" value="0">
                                        </div>
                                        <div class="form-check mx-2">
                                            <label class="form-label" for="doctorFemale">Female</label>&nbsp;
                                            <input class="form-check-input" id="doctorFemale" <?php echo isset($doctor_data['gender']) && $doctor_data['gender'] == 1 ? 'checked="checked"' : ''; ?> name="gender" type="radio" value="1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-5">
                                    <label for="status" class="form-label">Status:</label>
                                    <br>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input is-active" name="status" type="checkbox" value="1" tabindex="8" <?php echo isset($doctor_data['status']) && $doctor_data['status'] == 1 ? 'checked="checked"' : ''; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-5">
                                    <label for="specialist" class="form-label">Specialist:</label>
                                    <span class="required"></span>
                                    <input class="form-control" required="" placeholder="Specialist" name="specialist" type="text" id="specialist" value="<?php echo isset($doctor_data['specialist']) ? htmlspecialchars($doctor_data['specialist']) : ''; ?>">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <input class="btn btn-primary me-2" type="submit" value="Save">
                                <a href="./doctors.php" class="btn btn-secondary">Cancel</a>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="./admin.js"></script>
<?php
require_once("../components/footer.php");
?>