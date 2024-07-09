<?php
session_start();
include "../connection.php";

// Check if the user is authenticated
if (!isset($_SESSION['user'])) {
    $_SESSION['success'] = false;
    $_SESSION['message'] = "Authentication failed";
    header("location: $appUrl/login.php");
    exit;
}

// Check if the user role is patients
else if (isset($_SESSION["role"]) && $_SESSION["role"] != "patients") {
    $_SESSION['success'] = false;
    $_SESSION['message'] = "You are not authorized to access this page.";
    header("location: $appUrl/login.php");
    exit;
}

$pageTitle = "Prescripitions";
require_once "../components/header.php";
?>

<div class="modal fade" id="prescriptionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Prescription Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="prescriptionDetails"></p>
            </div>
        </div>
    </div>
</div>

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

        <!-- Header Start -->
        <?php require_once "../components/profileHeader.php" ?>
        <!-- Header End -->

        <!-- Appointment Table start -->
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
                <h3>Your Prescripitions</h3>
            </div>
        </div>
        <div class="row dashboard-widget-p-5">
            <div class="col-12">
                <table id="dataTable" class="display table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Doctor name</th>
                            <th>Appointment Date</th>
                            <th>Prescription Details</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Table rows go here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Appointment Table end -->
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "ajax": {
                "url": "../fetch.php?patientAppointments=true",
                "dataSrc": ""
            },
            "bPaginate": true,
            "bFilter": true,
            "bInfo": true,
            "aaSorting": [
                [1, 'asc']
            ],
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All']
            ],
            "columns": [{
                    // Increment the ID by 1 for display
                    "render": function(data, type, full, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    "render": function(data, type, full, meta) {
                        return `<div class="d-flex align-items-center">
                        <div class="d-flex flex-column">
                            <p class="mb-1">${full.doctor_name}</p>
                        </div>
                    </div>`;
                    }
                },
                {
                    "data": "opd_date",
                    "render": function(data, type, full, meta) {
                        // Display only the date part
                        return moment(data).format('YYYY-MM-DD');
                    }
                },
                {
                    "data": "prescription_details",
                    "visible": false // Hide the prescription details column
                },
                {
                    "render": function(data, type, full, meta) {
                        // Check if the appointment is canceled
                        if (full.status && full.status.toLowerCase() === 'canceled') {
                            return '<button class="btn btn-secondary mx-1" title="Canceled" disabled>Canceled</button>';
                        } else if (full.status && full.status.toLowerCase() === 'confirm') {
                            return '<button value=' + full.id + ' class="viewPrescription btn btn-info mx-1" title="View Prescription" data-toggle="tooltip"><span class="fa fa-eye"></span></button>';
                        } else {
                            return '<button class="btn btn-warning mx-1" title="Not Confirmed" onclick="showNotConfirmedAlert()">Not Confirmed</button>';
                        }
                    },
                    "orderable": false
                }
            ]
        });

        // Event listener for viewing prescription details
        $(document).on('click', '.viewPrescription', function() {
            let rowData = $('#dataTable').DataTable().row($(this).parents('tr')).data();
            let prescriptionDetails = rowData.prescription_details;

            // Use prescriptionDetails to populate and display the modal
            $('#prescriptionModal').modal('show');
            $('#prescriptionDetails').text(prescriptionDetails);
        });
    });

    // Additional script for handling not confirmed appointments
    function showNotConfirmedAlert() {
        Swal.fire({
            text: 'This appointment is not confirmed.',
            icon: 'warning',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK',
        });
    }


    // Additional script for canceling appointments
    $(document).on('click', '.userDelete', function() {
        let val = $(this).val();
        Swal.fire({
            text: 'Are you sure want to cancel this appointment?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../queries.php",
                    method: "POST",
                    data: {
                        appointment_id: val,
                        cancelAppointment: true,
                    },
                    success: function(response) {
                        if (response == 1) {
                            toastr.success("Appointment canceled successfully");
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        } else {
                            toastr.error(response);
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        toastr.error(errorThrown);
                    }
                });
            }
        });
    });
</script>


<script src="./patient.js"></script>
<?php
require_once("../components/footer.php");
?>