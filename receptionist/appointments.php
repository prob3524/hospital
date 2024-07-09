<?php
session_start();
include "../connection.php";
// Check cookies exists or not
if (!isset($_SESSION['user'])) {
  $_SESSION['success'] = false;
  $_SESSION['message'] = "Authentication failed";
  header("location: $appUrl/login.php");
} else if (isset($_SESSION["role"]) && $_SESSION["role"] != "receptionists") {
  setcookie('user', '', time() - 3600, '/');
  $_SESSION['success'] = false;
  $_SESSION['message'] = "You are not authorized to access the receptionist site.";
  header("location: $appUrl/login.php");
  exit;
}
$pageTitle = "Appointments";
require_once "../components/header.php";
?>

<!--  Body Wrapper -->
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
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

  <!--  Main wrapper -->
  <div class="body-wrapper">
    <!--  Header Start -->
    <?php require_once "../components/profileHeader.php" ?>
    <!--  Header End -->

    <!-- Appointment Table start-->
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
        <h3>Appointments</h3>
        <a class="btn btn-outline-secondary" href="./add-appointment.php">Add Appointment</a>
      </div>
    </div>
    <div class="row dashboard-widget-p-5">
      <div class="col-12">
        <table id="dataTable" class="display table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Patient Name</th>
              <th>Doctor name</th>
              <th>Appointment Date</th>
              <th>Created At</th>
              <th>Canceled By</th>
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
  <!-- Appointment Table end-->

  <script>
    $(document).ready(function() {
      $('#dataTable').DataTable({
        "ajax": {
          "url": "../fetch.php?allAppointments=true",
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
                      <a href="/appointments.php?id=${full.id}" class="text-decoration-none mb-1">${full.patient_name}</a>
                      <span>${full.email}</span>
                  </div>
              </div>`
            }
          },
          {
            "render": function(data, type, full, meta) {
              return `<div class="d-flex align-items-center">
                  <div class="d-flex flex-column">
                      <p class=" mb-1">${full.doctor_name}</p>
                  </div>
              </div>`
            }
          },
          {
            "data": "opd_date",
            "render": function(data, type, full, meta) {
              return moment(data).format('YYYY-MM-DD HH:mm:ss');
            }
          },
          {
            "data": "created_at",
            "render": function(data, type, full, meta) {
              return moment(data).format('YYYY-MM-DD HH:mm:ss');
            }
          },
          {
            "data": "status",
            "defaultContent": "Not Canceled",
            "render": function(data, type, full, meta) {
              return data ? data : "Not Canceled";
            }
          },
          {
            "render": function(data, type, full, meta) {
              return '<button value=' + full.id + ' class="userDelete btn btn-danger mx-1" title="Delete Appointment" name="deletedata" data-toggle="tooltip"><span class="fa fa-trash"></span></button>';
            },
            "orderable": false
          }
        ],
        "columnDefs": [{
          "targets": 0,
          "visible": false,
          "searchable": true
        }]
      })
    });

    $(document).on('click', '.userDelete', function() {
      let val = $(this).val();
      Swal.fire({
        text: 'Are you sure want to delete this "Appointment"?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Delete',
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "../queries.php",
            method: "POST",
            data: {
              appointment_id: val,
              delete: true,
              isAppointment: true
            },
            success: function(response) {
              if (response == 1) {
                toastr.success("Appointment deleted successfully");
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

  <script src="./receptionist.js"></script>
  <?php
  require_once("../components/footer.php");
  ?>
