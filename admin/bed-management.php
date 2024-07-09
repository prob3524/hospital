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
$pageTitle = "Bed management";
require_once "../components/header.php";



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_bed'])) {
  $bed_name = $_POST["bed_name"];
  $bed_charge = $_POST["bed_charge"];
  $bed_type = $_POST["bed_type"];
  echo $bed_charge;
  // Insert into doctors table
  $diagnosis_query = "INSERT INTO beds (bed_name,bed_charge,bed_type) VALUES ('$bed_name','$bed_charge','$bed_type')";
  $result = mysqli_query($connection, $diagnosis_query);

  // Redirect or show a success message
  $_SESSION['success'] = true;
  $_SESSION['message'] = "Bed added successfully";
  header("location: $appUrl/admin/bed-management.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["bed_update"])) {
  $bed_id = $_POST["bed_id"];
  $bed_name = $_POST["bed_name"];
  $bed_charge = $_POST["bed_charge"];
  $update_query = "UPDATE `beds` SET `bed_name` = '$bed_name', `bed_charge` = '$bed_charge' WHERE `id` = $bed_id";
  print($update_query);
  $result = mysqli_query($connection, $update_query);
  if ($result) {
    $_SESSION['success'] = true;
    $_SESSION['message'] = "Bed updated successfully";
    header("location: $appUrl/admin/bed-management.php");
    exit();
  }
}

// Fetch wards data
$wards_query = "SELECT ward_name, ward_charge FROM wards";
$wards_result = mysqli_query($connection, $wards_query);
$wards = [];
if ($wards_result && mysqli_num_rows($wards_result) > 0) {
  while ($row = mysqli_fetch_assoc($wards_result)) {
    $wards[] = $row;
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

    <!-- Bed Table start-->
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
        <h3>Beds</h3>
        <a class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#diagnosisModal" href="#">Add Bed</a>
      </div>
    </div>
    <div class="row dashboard-widget-p-5">
      <div class="col-12">
        <table id="dataTable" class="display table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Bed Name</th>
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

  <!-- Bed Table end-->

  <!-- Create Bed modal -->
  <div class="container-fluid py-0">
    <div class="modal fade" id="diagnosisModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Create Bed</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="" autocomplete="off" method="POST">
            <div class="modal-body">
              <div>
                <label class="my-1">Bed Name:</label><span class="text-danger">*</span>
                <input type="text" name="bed_name" class="form-control" placeholder="Bed Name" id="bed_name" required>
              </div>
              <div>
              <label class="my-1">Bed Type:</label><span class="text-danger">*</span>
              <select name="bed_type" class="form-control" id="bed_type" onchange="setBedCharge()" required>
                <option value="">Select bed type</option>
                <?php foreach ($wards as $ward): ?>
                  <option value="<?php echo $ward['ward_name']; ?>" data-charge="<?php echo $ward['ward_charge']; ?>">
                    <?php echo $ward['ward_name']; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

              <div>
                <label class="my-1">Bed Charge:</label><span class="text-danger">*</span>
                <input type="number" name="bed_charge" class="form-control" placeholder="Bed Charge" id="bed_charge" readonly required>
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
  <!-- Create Bed modal End -->

  <!-- Modal for View Bed end-->

  <!-- Edit Bed modal start -->
  <div class="modal fade" id="editDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Bed</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form autocomplete="off" method="POST">
          <div class="modal-body">
            <div>
              <label class="my-1">Bed Name:</label><span class="text-danger">*</span>
              <input type="text" name="bed_name" class="form-control" placeholder="Bed Name" id="bedName">
            </div>
            <div>
              <label class="my-1">Bed Charge:</label><span class="text-danger">*</span>
              <input type="number" name="bed_charge" class="form-control" placeholder="Bed Charge" id="bedCharge">
            </div>
            <div class="my-2">
              <input type="hidden" name="bed_id" id="bed_id" class="form-control">
            </div>
            <div class="modal-footer">
              <button type="submit" name="bed_update" class="btn btn-outline-secondary">Edit</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Edit Bed modal end -->

  <script>
    $(document).on('click', '.edit_data', function() {
      var bed_id = $(this).attr("id");
      let value = $(this).attr("value");
      $.ajax({
        url: "../fetch.php",
        method: "POST",
        data: {
          bed_id: bed_id
        },
        dataType: "json",
        success: function(data) {
          if (value.toLocaleLowerCase() == "edit") {
            $('#bedName').val(data[0].bed_name);
            $('#bedCharge').val(data[0].bed_charge);
            $('#bed_id').val(bed_id);
            $('#editDepartmentModal').modal('show');
          }
        }
      });
    });

  function setBedCharge() {
    var bedTypeElement = document.getElementById("bed_type");
    var selectedOption = bedTypeElement.options[bedTypeElement.selectedIndex];
    var bedCharge = selectedOption.getAttribute("data-charge");
    document.getElementById("bed_charge").value = bedCharge;
  }


    $(document).ready(function() {
      $('#dataTable').DataTable({
        "ajax": {
          "url": "../fetch.php?allBeds=true",
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
            "data": "bed_name"
          },
          {
            "render": function(data, type, full, meta) {
              const isChecked = full.status == 1;
              const toggleSwitchHTML = `
                        ${isChecked ? '<span class="badge bg-success">Available</span> ' : '<span class="badge bg-danger">Not Available</span>'}
                    `;
              return toggleSwitchHTML;
            }
          },
          {
            "render": function(data, type, full, meta) {
              const formattedDate = moment(full.created_at).format("DD/MM/YYYY");
              const formattedTime = moment(full.created_at).format('LT');
              return `<span class="badge bg-light-info"><div class="mb-1">${formattedTime}</div><div>${formattedDate}</div></span>`;
            }
          },
          {
            "render": function(data, type, full, meta) {
              return '<button name="edit" title="Edit Bed" value="Edit" id="' + full.id + '" class="btn btn-info mx-1 edit_data"><span class="fa fa-pencil"></span></button>' +
                '<button value=' + full.id + ' class="departmentDelete btn btn-danger mx-1" title="Delete Bed" name="deletedata" data-toggle="tooltip"><span class="fa fa-trash"></span></button>';
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
        text: 'Are you sure want to delete this "Bed"?',
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
              bed_id: val,
              delete: true,
              isBed: true
            },
            success: function(response) {
              if (response == 1) {
                toastr.success("Bed deleted successfully");
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

    document.addEventListener("DOMContentLoaded", function() {
      var form = document.querySelector("form");

      form.addEventListener("submit", function(event) {
        var diagnosis_name = document.getElementById("diagnosis_name").value.trim();

        if (diagnosis_name === "") {
          toastr.error("Please enter a diagnosis_name.");
          event.preventDefault();
          return;
        }
      });
    });
  </script>

  <script src="./admin.js"></script>
  <?php
  require_once("../components/footer.php");
  ?>