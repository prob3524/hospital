<?php
session_start();
include "../connection.php";

// Check if the user is authenticated
if (!isset($_SESSION['user'])) {
    $_SESSION['success'] = false;
    $_SESSION['message'] = "Authentication failed";
    header("location: $appUrl/login.php");
    exit;
} else if (isset($_SESSION["role"]) && $_SESSION["role"] != "doctors") {
    setcookie('user', '', time() - 3600, '/');
    $_SESSION['success'] = false;
    $_SESSION['message'] = "You are not authorized to access the doctor site.";
    header("location: $appUrl/login.php");
    exit;
}

$pageTitle = "View Appointment";
require_once "../components/header.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT appointments.*, CONCAT(patients.first_name, ' ', patients.last_name) AS patient_name, diagnosis.diagnosis_name
            FROM appointments
            LEFT JOIN patients ON appointments.patient_id = patients.id
            LEFT JOIN diagnosis ON appointments.diagnosis = diagnosis.id
            WHERE appointments.id = $id";

    $result = mysqli_query($connection, $sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $appointment_id = $row['id'];
        $patientName = $row['patient_name'];
        $diagnosis = $row['diagnosis_name'];
        $opdDate = $row['opd_date'];
        $description = $row['description'];
        $created_at = $row['created_at'];
        $status = $row['status'];
    } else {
        echo "Appointment not found.";
    }

    $prescriptionQuery = "SELECT * FROM prescriptions WHERE appointment_id = $appointment_id";
    $prescriptionResult = mysqli_query($connection, $prescriptionQuery);

    if ($prescriptionResult && $prescriptionResult->num_rows > 0) {
        $prescriptionRow = $prescriptionResult->fetch_assoc();
        $prescription_id = $prescriptionRow['id'];
        $prescriptionDetails = trim($prescriptionRow['prescription_details']);
    } else {
        $prescription_id = null; // Set to null if no prescription exists
        $prescriptionDetails = "No prescription available.";
    }
    
    // Modify the query to use appointment_id for medicines
    $medicinesQuery = "SELECT pm.*, m.name AS medicine_name 
                       FROM prescribed_medicines pm
                       JOIN medicines m ON pm.medicine_id = m.id
                       WHERE pm.appointment_id = $appointment_id";
    $medicinesResult = mysqli_query($connection, $medicinesQuery);
} else {
    echo "Invalid ID.";
}
?>

<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
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
    <div class="body-wrapper">
        <?php require_once "../components/profileHeader.php" ?>

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
                <h3>View Appointment</h3>
                <a class="btn btn-outline-secondary" href="./appointments.php">Back</a>
            </div>
            <div class="container-fluid" style="padding-top:0">
                <div class="card mt-3">
                    <div class="pt-0 card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td class="py-4 fw-bold">Patient Name:</td>
                                        <td class="py-4"><?php echo $patientName; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="py-4 fw-bold">Diagnosis:</td>
                                        <td class="py-4"><?php echo $diagnosis; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="py-4 fw-bold">OPD Date:</td>
                                        <td class="py-4"><?php echo $opdDate; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="py-4 fw-bold">Description:</td>
                                        <td class="py-4"><?php echo $description; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="py-4 fw-bold">Created At:</td>
                                        <td class="py-4"><?php echo $created_at; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="py-4 fw-bold">Status:</td>
                                        <td class="py-4"><?php echo $status; ?></td>
                                    </tr>
                                    <!-- Prescription details -->
                                    <tr>
                                        <td class="py-4 fw-bold">Prescription:</td>
                                        <td class="py-4"><?php echo $prescriptionDetails; ?></td>
                                    </tr>
                                    <!-- Medicine details -->
                                    <tr>
                                        <td class="py-4 fw-bold">Medicines:</td>
                                        <td class="py-4">
                                            <?php
                                            if ($medicinesResult && $medicinesResult->num_rows > 0) {
                                                echo "<ul>";
                                                while ($medicineRow = $medicinesResult->fetch_assoc()) {
                                                    echo "<li>" . $medicineRow['medicine_name'] . " - " . $medicineRow['dosage'] . " - " . $medicineRow['frequency'] . " - " . $medicineRow['duration'] . "</li>";
                                                }
                                                echo "</ul>";
                                            } else {
                                                echo "No medicines prescribed.";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <?php if ($prescriptionDetails === "No prescription available.") : ?>
                            <a class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#prescriptionModal" href="#">Add Prescription</a>
                        <?php else : ?>
                            <a class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#prescriptionModal" href="#">Edit Prescription</a>
                        <?php endif; ?>
                        <a class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#medicineModal" href="#">Add Medicine</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Prescription Modal -->
<div class="container-fluid py-0">
    <div class="modal fade" id="prescriptionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <?php echo ($prescriptionDetails === "No prescription available.") ? "Add Prescription" : "Edit Prescription"; ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../queries.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="appointment_id" value="<?php echo $appointment_id ?>">
                        <div class="form-group">
                            <label for="prescriptionDetails">Prescription Details: </label>
                            <textarea class="form-control" id="prescriptionDetails" name="prescriptionDetails" rows="4"><?php echo ($prescriptionDetails === "No prescription available.") ? "" : trim($prescriptionDetails); ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="save_prescription" class="btn btn-outline-secondary"><?php echo ($prescriptionDetails === "No prescription available.") ? "Save" : "Update"; ?></button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
</form>
</div>
</div>
</div>

</div>
<!-- Medicine Modal -->
<div class="container-fluid py-0">
    <div class="modal fade" id="medicineModal" tabindex="-1" role="dialog" aria-labelledby="medicineModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="medicineModalLabel">Add Medicine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../queries.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="appointment_id" value="<?php echo $appointment_id; ?>">
                        <div class="form-group">
                            <label for="medicine_id">Medicine:</label>
                            <select class="form-control" id="medicine_id" name="medicine_id">
                                <?php
                                $medicineQuery = "SELECT * FROM medicines";
                                $medicineResult = mysqli_query($connection, $medicineQuery);
                                while ($medicine = $medicineResult->fetch_assoc()) {
                                    echo "<option value='" . $medicine['id'] . "'>" . $medicine['name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dosage">Dosage:</label>
                            <input type="text" class="form-control" id="dosage" name="dosage" required>
                        </div>
                        <div class="form-group">
                            <label for="frequency">Frequency:</label>
                            <input type="text" class="form-control" id="frequency" name="frequency" required>
                        </div>
                        <div class="form-group">
                            <label for="duration">Duration:</label>
                            <input type="text" class="form-control" id="duration" name="duration" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="save_medicine" class="btn btn-outline-secondary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="./doctor.js"></script>
<?php
require_once("../components/footer.php");
?>