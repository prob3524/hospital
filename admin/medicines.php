<?php
session_start();
include "../connection.php";

// Check if the user is authenticated and authorized
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

$pageTitle = "Medicines";
require_once "../components/header.php";

// Handle form submission to add a new medicine
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save_medicine"])) {
    $medicine_name = $_POST["medicine_name"];
    $stock_quantity = $_POST["stock_quantity"];

    // Insert into medicines table
    $medicine_query = "INSERT INTO medicines (name,stock_quantity) VALUES ('$medicine_name',$stock_quantity)";
    $result = mysqli_query($connection, $medicine_query);

    // Redirect or show a success message
    $_SESSION['success'] = true;
    $_SESSION['message'] = "Medicine added successfully";
    header("location: $appUrl/admin/medicines.php");
    exit();
}

// Handle form submission to add a new medicine
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_medicine"])) {
    $medicine_name = $_POST["medicine_name"];
    $stock_quantity = $_POST["stock_quantity"];

    // Insert into medicines table
    $medicine_query = "update medicines set name='$medicine_name',stock_quantity=$stock_quantity where id=" . $_POST["medicine_id"];
    $result = mysqli_query($connection, $medicine_query);

    // Redirect or show a success message
    $_SESSION['success'] = true;
    $_SESSION['message'] = "Medicine updated successfully";
    header("location: $appUrl/admin/medicines.php");
    exit();
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
                        <!-- Sidebar menu items go here -->
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

        <!-- Medicines Table start -->
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
                <h3>Medicines</h3>
                <a class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#medicineModal" href="#">Add Medicine</a>
            </div>
        </div>
        <div class="row dashboard-widget-p-5">
            <div class="col-12">
                <table id="dataTable" class="display table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Medicine Name</th>
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
    <!-- Medicines Table end -->

    <!-- Create Medicine modal -->
    <div class="container-fluid py-0">
        <div class="modal fade" id="medicineModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create Medicine</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" autocomplete="off" method="POST">
                        <div class="modal-body">
                            <div>
                                <label class="my-1">Medicine Name:</label><span class="text-danger">*</span>
                                <input type="text" name="medicine_name" class="form-control" placeholder="Medicine Name" id="medicine_name">
                            </div>
                            <div>
                                <label class="my-1">Stock</label><span class="text-danger">*</span>
                                <input type="text" name="stock_quantity" class="form-control" placeholder="Stock" id="stock_quantity">
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
    <!-- Create Medicine modal End -->

    <!-- Modal for View Medicine start -->
    <div class="modal fade" id="viewMedicineModal" tabindex="-1" role="dialog" aria-labelledby="viewMedicineLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewMedicineLabel">View Medicine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Medicine Name:</td>
                                    <td id="medicineNameView"></td>
                                </tr>
                                <tr>
                                    <td>Stock:</td>
                                    <td id="medicineStockView"></td>
                                </tr>
                                <tr>
                                    <td>Created On:</td>
                                    <td id="created_at"></td>
                                </tr>
                                <!-- <tr>
                                    <td>Updated On:</td>
                                    <td id="updated_at"></td>
                                </tr> -->
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
    <!-- Modal for View Medicine end -->

    <!-- Edit Medicine modal start -->
    <div class="modal fade" id="editMedicineModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Medicine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form autocomplete="off" method="POST">
                    <div class="modal-body">
                        <div>
                            <label class="my-1">Medicine Name:</label><span class="text-danger">*</span>
                            <input type="text" name="medicine_name" class="form-control" placeholder="Medicine Name" id="medicineName">
                        </div>
                        <div>
                                <label class="my-1">Stock: </label><span class="text-danger">*</span>
                                <input type="text" name="stock_quantity" class="form-control" placeholder="Stock" id="medicineStockEdit">
                            </div>
                        <div class="my-2">
                            <input type="hidden" name="medicine_id" id="medicine_id" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="update_medicine" class="btn btn-outline-secondary">Edit</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Medicine modal end -->

    <script>
        $(document).on('click', '.edit_data, .view_data', function() {
            var medicine_id = $(this).attr("id");
            let value = $(this).attr("value");
            $.ajax({
                url: "../fetch.php",
                method: "POST",
                data: {
                    medicine_id: medicine_id
                },
                dataType: "json",
                success: function(data) {
                    if (value.toLocaleLowerCase() == "edit") {
                        $('#medicineName').val(data[0].name);
                        $('#medicineStockEdit').val(data[0].stock_quantity);
                        $('#medicine_id').val(data[0].id);
                        $('#editMedicineModal').modal('show');
                    } else if (value.toLocaleLowerCase() == "view") {
                        $('#medicineNameView').text(data[0].name);
                        $('#medicineStockView').text(data[0].stock_quantity);
                        $('#created_at').text(data[0].created_at);
                        // $('#updated_at').text(data[0].updated_at);
                        $('#viewMedicineModal').modal('show');
                    }
                }
            });
        });

        $(document).ready(function() {
            $('#dataTable').DataTable({
                "ajax": {
                    "url": "../fetch.php?allMedicines=true",
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
                        "data": "name"
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
                            return '<button name="view" title="View Medicine" value="view" id="' + full.id + '" class="btn btn-warning mx-1 view_data"><span class="fa fa-eye"></span></button>' +
                                '<button name="edit" title="Edit Medicine" value="Edit" id="' + full.id + '" class="btn btn-info mx-1 edit_data"><span class="fa fa-pencil"></span></button>' 
                                // +'<button value=' + full.id + ' class="medicineDelete btn btn-danger mx-1" title="Delete Medicine" name="deletedata" data-toggle="tooltip"><span class="fa fa-trash"></span></button>';
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

        $(document).on('click', '.medicineDelete', function() {
            let val = $(this).val();
            Swal.fire({
                text: 'Are you sure you want to delete this "Medicine"?',
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
                            medicine_id: val,
                            delete: true,
                            isMedicine: true
                        },
                        success: function(response) {
                            if (response == 1) {
                                toastr.success("Medicine deleted successfully");
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
                var medicine_name = document.getElementById("medicine_name").value.trim();

                if (medicine_name === "") {
                    toastr.error("Please enter a medicine name.");
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
</div>
