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
  $_SESSION['message'] = "You are not authorized to access the recptionist site.";
   header("location: $appUrl/login.php");
  exit;
}
$pageTitle = "Patients";
require_once "../components/header.php";
?>

<!--  Body Wrapper -->
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
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
        <h3>Patients</h3>
        <a class="btn btn-outline-secondary" href="./add-patient.php">Add Patient</a>
      </div>
    </div>
    <div class="row dashboard-widget-p-5">
      <div class="col-12">
        <table id="dataTable" class="display table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Phone no.</th>
              <th>Status</th>
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

  <!-- Patient Table end-->

  <!-- Create Patient modal -->
  <div class="container-fluid py-0">
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Create Patient</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form autocomplete="off" action="../queries.php" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
              <div>
                <label>Name:</label><span class="text-danger">*</span>
                <input type="text" name="username" class="form-control" placeholder="Username" id="username">
              </div>

              <div class="my-2">
                <label> Email: </label><span class="text-danger">*</span>
                <input type="email" name="email" class="form-control" placeholder="Email" id="email">
              </div>

              <label> Password: </label><span class="text-danger">*</span>
              <div class="my-2">
                <div class="input-group">
                  <input type="password" id="password" name="password" class="form-control" placeholder="Password" autocomplete="new-password" value="">
                  <span id="changeInputType" class="icon cursor-pointer input-group-text"><i id="eyeToggle" class="fa fa-eye-slash"></i></span>
                </div>
              </div>

              <div class="my-2">
                <label> Phone Number </label><span class="text-danger">*</span>
                <input type="number" name="phone_no" id="phone_no" class="form-control" maxlength="10" placeholder="Phone Number">
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" name="save" class="btn btn-outline-secondary">Save</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Create user modal End -->

  <!-- Modal for View Patient start-->
  <div class="modal fade" id="viewUserModal" tabindex="-1" role="dialog" aria-labelledby="viewUserLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewUserLabel">View Patient</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table">
              <tbody>
                <tr>
                  <td>Username:</td>
                  <td id="usernameView"></td>
                </tr>
                <tr>
                  <td>Email:</td>
                  <td id="emailView"></td>
                </tr>
                <tr>
                  <td>Phone no.:</td>
                  <td id="phoneView"></td>
                </tr>
                <tr>
                  <td>Created On:</td>
                  <td id="created_at"></td>
                </tr>
                <tr>
                  <td>Updated On:</td>
                  <td id="updated_at"></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal for View Patient end-->

  <!-- Edit user modal start -->
  <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Patient</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form autocomplete="off" action="../queries.php" method="POST">
          <div class="modal-body">
            <div>
              <label>Username: </label>
              <input type="text" name="username" id="username1" class="form-control" placeholder="Username">
            </div>

            <div class="my-2">
              <label>Email: </label>
              <input type="email" name="email" id="email1" class="form-control" placeholder="Email">
            </div>

            <div class="my-2">
              <label>Phone No.: </label>
              <input type="text" name="phone_no" id="phone_no1" class="form-control" placeholder="Phone No.">
            </div>
            <div class="my-2">
              <input type="hidden" name="user_id" id="user_id" class="form-control">
            </div>
            <div class="modal-footer">
              <button type="submit" name="user_update" class="btn btn-outline-secondary">Edit</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Edit user modal end -->

  <script>
    $(document).on('click', '.edit_data,.view_data', function() {
      var user_id = $(this).attr("id");
      let value = $(this).attr("value");
      $.ajax({
        url: "../fetch.php",
        method: "POST",
        data: {
          user_id: user_id
        },
        dataType: "json",
        success: function(data) {
          if (value.toLocaleLowerCase() == "edit") {
            $('#username1').val(data[0].username);
            $('#email1').val(data[0].email);
            $('#phone_no1').val(data[0].phone_no);
            $('#user_id').val(data[0].user_id);
            $('#editUserModal').modal('show');
          } else if (value.toLocaleLowerCase() == "view") {
            $('#usernameView').text(data[0].username);
            $('#emailView').text(data[0].email);
            $('#phoneView').text(data[0].phone_no);
            $('#created_at').text(data[0].created_at);
            $('#updated_at').text(data[0].updated_at);
            $('#viewUserModal').modal('show');
          }
        }
      });
    });

  $(document).ready(function() {
    $('#dataTable').DataTable({
      "ajax": {
        "url": "../fetch.php?allPatients=true",
        "dataSrc": ""
      },
      "bPaginate": true,
      "bFilter": true,
      "bInfo": true,
      "aaSorting": [
        [4, 'asc']
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
            <a href="/doctor.php?id=${full.id}" class="text-decoration-none mb-1">${full.first_name + " "+full.last_name}</a>
            <span>${full.email}</span>
        </div>
    </div>`
            }
          },
        {
          "data": "phone_no"
        },
        {
            "render": function(data, type, full, meta) {
              const isChecked = full.status == 1;
              const toggleSwitchHTML = `
              <div class="status_change">
                            <label class="form-check form-switch form-switch-sm cursor-pointer">
                                <input style="border:1px solid hsl(182, 100%, 35%)"
                                    autocomplete="off"
                                    class="form-check-input cursor-pointer status_change"
                                    type="checkbox" value=${full.id}
                                    ${isChecked ? 'checked' : ''}
                                >
                                <span class="switch-slider" data-checked="✓" data-unchecked="✕"></span>
                            </label>
                        </div>
                    `;
              return toggleSwitchHTML;
            }
          },
        {
          "render": function(data, type, full, meta) {
            return '<a href="./view-patient.php?id='+ full.id + '" name="view" title="View Patient" value="view" class="btn btn-warning mx-1 view_data"><span class="fa fa-eye"></span></a>' +
              '<a href="./edit-patient.php?id='+ full.id + '" name="edit" title="Edit Patient" value="Edit" class="btn btn-info mx-1 edit_data"><span class="fa fa-pencil"></span></a>' +
              '<button value=' + full.email + ' class="userDelete btn btn-danger mx-1" title="Delete Patient" name="deletedata" data-toggle="tooltip"><span class="fa fa-trash"></span></button>';
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
      text: 'Are you sure want to delete this "Patient"?',
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
            email: val,
            delete: true,
            isPatient:true
          },
          success: function(response) {
            if (response == 1) {
              toastr.success("Patient deleted successfully");
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
      var username = document.getElementById("username").value.trim();
      var email = document.getElementById("email").value.trim();
      var password = document.getElementById("password").value.trim();
      var phone_no = document.getElementById("phone_no").value.trim();
      var emailRegex = /^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/;

      if (username === "") {
        toastr.error("Please enter a username.");
        event.preventDefault();
        return;
      }

      if (email === "") {
        toastr.error("Please enter an email.");
        event.preventDefault();
        return;
      } else if (!emailRegex.test(email)) {
        toastr.error("Please enter a valid email address.");
        event.preventDefault();
        return;
      }

      if (password === "") {
        toastr.error("Please enter a password.");
        event.preventDefault();
        return;
      }

      if (phone_no === "") {
        toastr.error("Please enter a phone number.");
        event.preventDefault();
        return;
      }
    });
  });


  $(document).ready(function() {
    $("#btn_save").attr("disabled", true);
    $("#emailValidate, #passwordValidate, #usernameValidate").keyup(function() {
      var username = $("#usernameValidate").val();
      var email = $("#emailValidate").val();
      var password = $("#emailValidate").val();
      var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

      if (!username || !email || !emailRegex.test(email) || !password) {
        $("#btn_save").attr("disabled", true);
      } else {
        $("#btn_save").attr("disabled", false);
      }
    });
  });

  $(document).ready(function() {
      $(document).on('change', '.status_change', function(e) {
        e.stopPropagation();
        let patient_id = $(this).val();
        let status = this.checked ? 1 : 0;

        $.ajax({
          url: "../queries.php",
          method: "POST",
          data: {
            patient_id: patient_id,
            updatePatientStatus: true,
            status: status
          },
          success: function(response) {
            if (response == 1) {
              toastr.success("Patient status updated successfully");
            } else {
              toastr.error(response);
            }
          },
          error: function(xhr, textStatus, errorThrown) {
            toastr.error(errorThrown);
          }
        });
      });
    });
</script>

<script src="./receptionist.js"></script>
<?php
require_once("../components/footer.php");
?>