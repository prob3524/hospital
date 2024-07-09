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

// Check if the user role is patients or doctors
else if (isset($_SESSION["role"]) && ($_SESSION["role"] != "patients" && $_SESSION["role"] != "doctors")) {
    $_SESSION['success'] = false;
    $_SESSION['message'] = "You are not authorized to access this page.";
    header("location: $appUrl/login.php");
    exit;
}

$pageTitle = "Appointments";
require_once "../components/header.php";
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
                <h3>Your Appointments</h3>
            </div>
        </div>
        <div class="row dashboard-widget-p-5">
            <div class="col-12">
                <table id="dataTable" class="display table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Patient name</th>
                            <th>Appointment Date</th>
                            <th>Status</th>
                            <th>Created At</th>
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
                "url": "../fetch.php?doctorAppointments=true", // Change the URL to fetch doctor appointments
                "dataSrc": ""
            },
            "bPaginate": true,
            "bFilter": true,
            "bInfo": true,
            "aaSorting": [
                [2, 'asc']
            ],
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All']
            ],
            "columns": [{
                    "data": "id"
                },
                {
                    "render": function(data, type, full, meta) {
                        return `<div class="d-flex align-items-center">
                        <div class="d-flex flex-column">
                            <p class="mb-1">${full.patient_namee}</p>
                        </div>
                    </div>`;
                    }
                },
                {
                    "data": "opd_date",
                    "render": function(data, type, full, meta) {
                        // Format the date as needed
                        return moment(data).format('YYYY-MM-DD HH:mm:ss');
                    }
                },
                {
                    "data": "status",
                },
                {
                    "data": "created_at",
                    "render": function(data, type, full, meta) {
                        // Format the date as needed
                        return moment(data).format('YYYY-MM-DD HH:mm:ss');
                    }
                },
                {
                    "render": function(data, type, full, meta) {
                        if (full.status && full.status.toLowerCase() === 'canceled') {
                            name = full.canceled_by;
                            return `<button class="btn btn-secondary mx-1" title="Canceled" disabled>Canceled by ${name}</button>`;
                        } else if (full.status && full.status.toLowerCase() === 'confirm') {
                            // Appointment is confirmed, do not render the cancel button
                            return '<a href="/doctor/view-appointment.php?id=' + full.id + '" name="view" title="View Doctor" value="view" id="' + full.id + '" class="btn btn-warning mx-1 view_data"><span class="fa fa-eye"></span></a>';
                        } else {
                            // Appointment is not canceled or confirmed, render the cancel button
                            return '<button value=' + full.id + ' class="userDelete btn btn-danger mx-1" title="Cancel Appointment" name="cancelAppointment" data-toggle="tooltip"><span class="fa fa-times"></span></button>' +
                                '<a href="/doctor/view-appointment.php?id=' + full.id + '" name="view" title="View Doctor" value="view" id="' + full.id + '" class="btn btn-warning mx-1 view_data"><span class="fa fa-eye"></span></a>';
                        }
                    },
                    "orderable": false

                }

            ],
            "columnDefs": [{
                "targets": 0,
                "visible": false,
                "searchable": true
            }]
        });
    });


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


<script src="./doctor.js"></script>
<?php
require_once("../components/footer.php");
?>