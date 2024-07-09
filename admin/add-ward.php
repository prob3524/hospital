<?php
session_start();
include "../connection.php";
// Check cookies exists or not
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
$pageTitle = "Ward management";
require_once "../components/header.php";



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_bed'])) {
  $ward_name = $_POST["ward_name"];
  $ward_charge = $_POST["ward_charge"];
  echo $ward_charge;
  // Insert into doctors table
  $diagnosis_query = "INSERT INTO wards (ward_name,ward_charge) VALUES ('$ward_name','$ward_charge')";
  $result = mysqli_query($connection, $diagnosis_query);

  // Redirect or show a success message
  $_SESSION['success'] = true;
  $_SESSION['message'] = "Ward added successfully";
  header("location: $appUrl/admin/add-ward.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["ward_update"])) {
  $ward_id = $_POST["ward_id"];
  $ward_name = $_POST["ward_name"];
  $ward_charge = $_POST["ward_charge"];
  $update_query = "UPDATE `wards` SET `ward_name` = '$ward_name', `ward_charge` = '$ward_charge' WHERE `id` = $ward_id";
  print($update_query);
  $result = mysqli_query($connection, $update_query);
  if ($result) {
    $_SESSION['success'] = true;
    $_SESSION['message'] = "Ward updated successfully";
    header("location: $appUrl/admin/add-ward.php");
    exit();
  }
}

?>
<!--  Body Wrapper -->
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
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

    <!-- Ward Table start-->
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
        <h3>Wards</h3>
        <a class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#wardModal" href="#">Add Ward</a>
      </div>
    </div>
    <div class="row dashboard-widget-p-5">
      <div class="col-12">
        <table id="dataTable" class="display table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Ward Name</th>
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

  <!-- Ward Table end-->

  <!-- Create Ward modal -->
  <div class="container-fluid py-0">
    <div class="modal fade" id="wardModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Create Ward</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="" autocomplete="off" method="POST">
            <div class="modal-body">
              <div>
                <label class="my-1">Ward Name:</label><span class="text-danger">*</span>
                <input type="text" name="ward_name" class="form-control" placeholder="Ward Name" id="ward_name" required>
              </div>
              <div>
                <label class="my-1">Ward Charge:</label><span class="text-danger">*</span>
                <input type="number" name="ward_charge" class="form-control" placeholder="Ward Charge" id="ward_charge" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" name="add_bed" class="btn btn-outline-secondary">Save</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Create Ward modal End -->

  <!-- Modal for View Ward end-->

  <!-- Edit Ward modal start -->
  <div class="modal fade" id="editWardModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Ward</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form autocomplete="off" method="POST">
          <div class="modal-body">
            <div>
              <label class="my-1">Ward Name:</label><span class="text-danger">*</span>
              <input type="text" name="ward_name" class="form-control" placeholder="Ward Name" id="bedName">
            </div>
            <div>
              <label class="my-1">Ward Charge:</label><span class="text-danger">*</span>
              <input type="number" name="ward_charge" class="form-control" placeholder="Ward Charge" id="bedCharge">
            </div>
            <div class="my-2">
              <input type="hidden" name="ward_id" id="ward_id" class="form-control">
            </div>
            <div class="modal-footer">
              <button type="submit" name="ward_update" class="btn btn-outline-secondary">Edit</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Edit Ward modal end -->

  <script>
    $(document).on('click', '.edit_data', function() {
      var ward_id = $(this).attr("id");
      let value = $(this).attr("value");
      $.ajax({
        url: "../fetch.php",
        method: "POST",
        data: {
          ward_id: ward_id
        },
        dataType: "json",
        success: function(data) {
          if (value.toLocaleLowerCase() == "edit") {
            $('#bedName').val(data[0].ward_name);
            $('#bedCharge').val(data[0].ward_charge);
            $('#ward_id').val(ward_id);
            $('#editWardModal').modal('show');
          }
        }
      });
    });

    function setBedCharge() {
      var bedType = document.getElementById("ward_type").value;
      var bedCharge = document.getElementById("ward_charge");

      if (bedType === "ICU") {
        bedCharge.value = 500;
      } else if (bedType === "3 Star") {
        bedCharge.value = 200;
      } else {
        bedCharge.value = ''; // Default value if necessary
      }
    }

    $(document).ready(function() {
      $('#dataTable').DataTable({
        "ajax": {
          "url": "../fetch.php?allWards=true",
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
            "data": "id"
          },
          {
            "data": "ward_name"
          },
          {
            "render": function(data, type, full, meta) {
              return '<button name="edit" title="Edit Ward" value="Edit" id="' + full.id + '" class="btn btn-info mx-1 edit_data"><span class="fa fa-pencil"></span></button>' +
                '<button value=' + full.id + ' class="departmentDelete btn btn-danger mx-1" title="Delete Ward" name="deletedata" data-toggle="tooltip"><span class="fa fa-trash"></span></button>';
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


    $(document).on('click', '.departmentDelete', function() {
      let val = $(this).val();
      Swal.fire({
        text: 'Are you sure want to delete this "Ward"?',
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
              ward_id: val,
              delete: true,
              isWard: true
            },
            success: function(response) {
              if (response == 1) {
                toastr.success("Ward deleted successfully");
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

  <script src="./admin.js"></script>
  <?php
  require_once("../components/footer.php");
  ?>