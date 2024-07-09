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
$pageTitle = "Diagnosis";
require_once "../components/header.php";



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $diagnosis_name = $_POST["diagnosis_name"];

    // Insert into doctors table
    $diagnosis_query = "INSERT INTO diagnosis (diagnosis_name) VALUES ('$diagnosis_name')";
    $result = mysqli_query($connection, $diagnosis_query);

    // Redirect or show a success message
    $_SESSION['success'] = true;
    $_SESSION['message'] = "Diagnosis added successfully";
    header("location: $appUrl/admin/diagnosis.php");
    exit();
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

        <!-- Diagnosis Table start-->
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
                <h3>Diagnosis</h3>
                <a class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#diagnosisModal" href="#">Add Diagnosis</a>
            </div>
        </div>
        <div class="row dashboard-widget-p-5">
            <div class="col-12">
                <table id="dataTable" class="display table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Diagnosis Name</th>
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

    <!-- Diagnosis Table end-->

    <!-- Create Diagnosis modal -->
    <div class="container-fluid py-0">
        <div class="modal fade" id="diagnosisModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create Diagnosis</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" autocomplete="off" method="POST">
                        <div class="modal-body">
                            <div>
                                <label class="my-1">Diagnosis Name:</label><span class="text-danger">*</span>
                                <input type="text" name="diagnosis_name" class="form-control" placeholder="Diagnosis Name" id="diagnosis_name">
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
    <!-- Create Diagnosis modal End -->

    <!-- Modal for View Diagnosis start-->
    <div class="modal fade" id="viewDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="viewUserLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewUserLabel">View Diagnosis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Diagnosis Name:</td>
                                    <td id="departmentNameView"></td>
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

    <!-- Modal for View Diagnosis end-->

    <!-- Edit Diagnosis modal start -->
    <div class="modal fade" id="editDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Diagnosis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form autocomplete="off" method="POST">
                    <div class="modal-body">
                        <div>
                            <label class="my-1">Diagnosis Name:</label><span class="text-danger">*</span>
                            <input type="text" name="diagnosis_name" class="form-control" placeholder="Diagnosis Name" id="diagnosisName">
                        </div>
                        <div class="my-2">
                            <input type="hidden" name="department_id" id="department_id" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="user_update" class="btn btn-outline-secondary">Edit</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Diagnosis modal end -->

    <script>
        $(document).on('click', '.edit_data,.view_data', function() {
            var department_id = $(this).attr("id");
            let value = $(this).attr("value");
            $.ajax({
                url: "../fetch.php",
                method: "POST",
                data: {
                    department_id: department_id
                },
                dataType: "json",
                success: function(data) {
                    if (value.toLocaleLowerCase() == "edit") {
                        $('#diagnosisName').val(data[0].diagnosis_name);
                        $('#department_id').val(data[0].id);
                        $('#editDepartmentModal').modal('show');
                    } else if (value.toLocaleLowerCase() == "view") {
                        $('#departmentNameView').text(data[0].diagnosis_name);
                        $('#created_at').text(data[0].created_at);
                        $('#updated_at').text(data[0].updated_at);
                        $('#viewDepartmentModal').modal('show');
                    }
                }
            });
        });

        $(document).ready(function() {
            $('#dataTable').DataTable({
                "ajax": {
                    "url": "../fetch.php?allDepartments=true",
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
                        "data": "diagnosis_name"
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
                            return '<button name="view" title="View Diagnosis" value="view" id="' + full.id + '" class="btn btn-warning mx-1 view_data"><span class="fa fa-eye"></span></button>' +
                                '<button name="edit" title="Edit Diagnosis" value="Edit" id="' + full.id + '" class="btn btn-info mx-1 edit_data"><span class="fa fa-pencil"></span></button>' +
                                '<button value=' + full.id + ' class="departmentDelete btn btn-danger mx-1" title="Delete Diagnosis" name="deletedata" data-toggle="tooltip"><span class="fa fa-trash"></span></button>';
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
                text: 'Are you sure want to delete this "Diagnosis"?',
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
                            department_id: val,
                            delete: true,
                            isDepartment: true
                        },
                        success: function(response) {
                            if (response == 1) {
                                toastr.success("Diagnosis deleted successfully");
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