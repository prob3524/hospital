<?php
session_start();
include "../connection.php";

// Check authentication
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

$pageTitle = "Bed Management";
require_once "../components/header.php";

// Fetch all distinct bed types from beds table
$bed_types_query = "SELECT DISTINCT bed_type FROM beds";
$bed_types_result = mysqli_query($connection, $bed_types_query);

// Initialize an array to store beds grouped by bed_type
$beds_by_type = [];

// Loop through each bed type and fetch beds
while ($bed_type_row = mysqli_fetch_assoc($bed_types_result)) {
    $current_bed_type = $bed_type_row['bed_type'];

    // Fetch beds for current bed type
    $beds_query = "
        SELECT beds.id, beds.bed_name, beds.status, beds.bed_type, 
               IFNULL(CONCAT(patients.first_name, ' ', patients.last_name), '') AS patient_name,
               bed_assignments.assign_date, beds.bed_charge
        FROM beds
        LEFT JOIN bed_assignments ON beds.id = bed_assignments.bed_id
        LEFT JOIN patients ON bed_assignments.ipd_patient_department_id = patients.id
        WHERE beds.bed_type = '$current_bed_type'
    ";
    $beds_result = mysqli_query($connection, $beds_query);

    // Initialize an array to store beds of current bed type
    $beds = [];

    // Loop through the query result and organize beds by bed_type
    while ($bed = mysqli_fetch_assoc($beds_result)) {
        $beds[] = $bed;
    }

    // Add beds of current bed type to the main array
    $beds_by_type[$current_bed_type] = $beds;
}

?>

<!-- Body Wrapper -->
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
    <!-- Main wrapper -->
    <div class="body-wrapper">
        <!-- Profile Header -->
        <?php require_once "../components/profileHeader.php"; ?>

        <!-- Main Content Area -->
        <div class="p-5">
            <!-- Title and Back Button -->
            <div class="d-flex justify-content-between align-items-center">
                <h3>Bed Management</h3>
                <a class="btn btn-outline-secondary" href="./dashboard.php">Back</a>
            </div>

            <!-- Beds Display Section -->
            <?php
            foreach ($beds_by_type as $bed_type => $beds) {
                echo '<h2 class="mt-5">' . htmlspecialchars($bed_type) . '</h2>';
                echo '<div class=" row mt-3" style="border:1px solid #dee2e6">';

                if (empty($beds)) {
                    echo '<p>No beds available in this category.</p>';
                } else {
                    foreach ($beds as $bed) {
                        $status_class = $bed['status'] === '0' ? 'occupied' : 'available';
                        $patient_name = isset($bed['patient_name']) ? htmlspecialchars($bed['patient_name']) : '';
                        $assign_date = isset($bed['assign_date']) ? htmlspecialchars($bed['assign_date']) : '';
                        $bed_charge = isset($bed['bed_charge']) ? htmlspecialchars($bed['bed_charge']) : '';

                        echo '<div class="bed-item col-md-2 ' . $status_class . '" data-bed-id="' . $bed['id'] . '" data-patient="' . $patient_name . '" data-assign-date="' . $assign_date . '" data-bed-charge="' . $bed_charge . '">';
                        echo '<div class="card">';
                        echo '<div class="card-body text-center">';
                        echo '<i class="fa fa-bed fa-3x"></i>';
                        echo '<p class="mt-2">' . htmlspecialchars($bed['bed_name']) . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                }

                echo '</div>'; // Close bed-grid
            }
            ?>
        </div>
    </div>
    <div class="container-fluid py-0">
        <div class="modal fade" id="bedDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Bed Details</h5>
                        <button type="button" class="close border-none" style="border:none" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p id="modalPatientName"></p>
                        <p id="modalAssignDate"></p>
                        <p id="modalBedCharge"></p>
                        <input id="bedIdInput" type="hidden" value="">
                    </div>
                    <div class="modal-footer">
                        <button id="unassignButton" class="btn btn-danger">Unassign</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Modal and Bed Item Clicks -->
    <script>
        // Function to show bed details in modal
        function showBedDetails(patientName, assignDate, bedCharge, bedId) {
            document.getElementById('modalPatientName').textContent = "Assigned Patient: " + patientName;
            document.getElementById('modalAssignDate').textContent = "Assign Date: " + assignDate;
            document.getElementById('modalBedCharge').textContent = "Bed Charge: ₹" + bedCharge;
            document.getElementById('bedIdInput').value = bedId;

            // Show the modal using Bootstrap's modal method
            var modal = new bootstrap.Modal(document.getElementById('bedDetailsModal'));
            modal.show();
        }

        // Event listener for bed items
        document.querySelectorAll('.bed-item').forEach(item => {
            item.addEventListener('click', () => {
                const patientName = item.dataset.patient;
                const assignDate = item.dataset.assignDate;
                const bedCharge = item.dataset.bedCharge;
                const bedId = item.dataset.bedId;

                if (patientName) {
                    showBedDetails(patientName, assignDate, bedCharge, bedId);
                } else {
                    window.location.href = `./assign-bed.php?bed_id=${bedId}`;
                }
            });
        });

        // Event listener for "Unassign" button in modal
        document.getElementById("unassignButton").addEventListener("click", function() {
            const bedId = document.getElementById("bedIdInput").value;

            // Perform AJAX request to unassign the bed
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../queries.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send(`bed_id=${bedId}&isUnAssigned=true`);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        toastr.success('Bed unassigned successfully!');
                        window.location.reload(); // Reload the page
                    } else {
                        toastr.error('Failed to unassign bed. Please try again.');
                    }
                } else {
                    toastr.error('Failed to unassign bed. Please try again.');
                }
            };
            xhr.onerror = function() {
                toastr.error('Network error occurred. Please try again later.');
            };
        });
    </script>
</div>

<!-- Add Bootstrap JS and dependencies -->

<!-- Styles for Beds and Modal -->
<style>
    .bed-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .bed-item {
        cursor: pointer;
    }

    .bed-item.available .card {
        /* background-color: #d4edda; */
        color: #155724;
    }

    .bed-item.occupied .card {
        /* background-color: #f8d7da; */
        color: #721c24;
    }

    .bed-item i {
        font-size: 3em;
    }

    .bed-item p {
        margin: 0;
        font-size: 1em;
    }

    .card{
background:none !important;
box-shadow:none !important;
margin-bottom:0px !important;
    }
</style>

<script src="./admin.js"></script>
<script src="../assets/js/jquery.min.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<?php require_once("../components/footer.php"); ?>
