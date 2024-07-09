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

<script>
    $(document).ready(function() {
        $('#patients').change(function() {
            var selectedPatientId = $(this).val();
            $('input[name="patient_id"]').val(selectedPatientId);
        });

        // Your existing code...
    });
    $(document).ready(function() {
        $('form').submit(function(e) {
            e.preventDefault(); // Prevent the form from submitting in the traditional way

            // Collect all form data
            var formData = $(this).serialize();

            // Perform AJAX request
            $.ajax({
                url: '/queries.php', // Replace 'submit.php' with the actual URL where you want to handle form submission
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    // Handle success response
                    if (response == 1) {
                        Swal.fire({
                            text: 'Appointment Booked Succesfully',
                            icon: 'success'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Reload the page or perform other actions
                                window.location.replace("/receptionist/appointments.php")
                            }
                        });
                    } else {
                        Swal.fire({
                            text: response,
                            icon: 'error'
                        })
                    }
                },
                error: function(e) {
                    // Handle error
                    Swal.fire({
                        text: e.responseText,
                        icon: 'error'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Reload the page or perform other actions
                            location.reload(true); // Reload the page
                        }
                    });
                }
            });
        });
    });
    $(document).ready(function() {

        $.ajax({
            url: '/fetch.php?allPatients=true',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                // Populate the diagnosis dropdown
                var departmentDropdown = $('#patients');
                departmentDropdown.empty();
                departmentDropdown.append($('<option>', {
                    value: '',
                    text: 'Select Patient'
                }));
                $.each(data, function(index, patient) {
                    departmentDropdown.append($(' <option> ', {
                        value: patient.id,
                        text: patient.first_name
                    }));
                });
            },
            error: function() {
                toastr.error('Failed to fetch patients.');
            }
        });

        $.ajax({
            url: '/fetch.php?allDepartments=true',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                // Populate the diagnosis dropdown
                var departmentDropdown = $('#diagnosis');
                departmentDropdown.empty();
                departmentDropdown.append($('<option>', {
                    value: '',
                    text: 'Select Diagnosis'
                }));
                $.each(data, function(index, diagnosis) {
                    departmentDropdown.append($(' <option> ', {
                        value: diagnosis.id,
                        text: diagnosis.diagnosis_name
                    }));
                });
            },
            error: function() {
                toastr.error('Failed to fetch diagnosis.');
            }
        });

        // Handle diagnosis change
        $('#diagnosis').change(function() {
            var selectedDepartment = $(this).val();

            // Fetch doctors based on the selected diagnosis
            $.ajax({
                url: '/fetch.php', // Create this PHP file to fetch doctors based on diagnosis
                method: 'POST',
                data: {
                    diagnosis: selectedDepartment
                },
                success: function(data) {
                    // Update the doctors dropdown with fetched data
                    var doctorDropdown = $('#doctor');
                    doctorDropdown.empty();
                    doctorDropdown.append(data);
                },
                error: function() {
                    toastr.error('Failed to fetch doctors.');
                }
            });
        });
    });
</script>
<!--  Body Wrapper -->
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
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
                <h3>New Appointment</h3>
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    <section class="appointment-section p-t-120 position-relative">
                        <div class="container">
                            <?php
                            if (isset($_SESSION['message']) && isset($_SESSION['success'])) {
                                $message = $_SESSION['message'];
                                $success = $_SESSION['success'];
                                $toastType = $success ? 'success' : 'error';
                                echo "<script>
                     toastr.options = {
                       positionClass: 'toast-top-right',
                       // timeOut: 2000,
                       progressBar: true,
                     };
                     toastr.$toastType('$message');
                     </script>";
                                unset($_SESSION['message']);
                                unset($_SESSION['success']);
                            }
                            ?>
                            <form onsubmit="return validateForm()">
                                <div class="d-lg-flex align-items-center justify-content-between mb-4">
                                    <div class="row">
                                        <!-- <div class="col-lg-4 col-md-6">
                                            <div class="appointment-form__input-block">
                                                <label for="patient_name" class="form-label">Patient Name:<span class="required">*</span></label>
                                                <input class="form-control" id="patientName" />
                                            </div>
                                        </div> -->
                                        <div class="col-lg-4 col-md-6 diagnosis-div">
                                            <div class="appointment-form__input-block">
                                                <label for="diagnosis" class="form-label">Patient Name:<span class="required">*</span></label>
                                                <select class="form-control" id="patients" name="patientName">
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" name="patient_id" />
                                        <div class="col-lg-4 col-md-6 diagnosis-div">
                                            <div class="appointment-form__input-block">
                                                <label for="diagnosis" class="form-label">Diagnosis:<span class="required">*</span></label>
                                                <select class="form-control" id="diagnosis" name="diagnosis">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 doctor-div">
                                            <div class="appointment-form__input-block">
                                                <label for="doctor" class="form-label">Doctor:<span class="required">*</span></label>
                                                <select class="form-control" id="doctor" name="doctor"></select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="appointment-form__input-block">
                                                <label for="date" class="form-label">Date:
                                                    <span class="required">*</span>
                                                </label>
                                                <input class="form-control opdDate" autocomplete="off" id="opdDate" required="" name="opd_date" type="date">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="appointment-form__input-block">
                                                <label for="description" class="form-label">Description:</label>
                                                <textarea class="form-control form-textarea" placeholder="Enter Description" autocomplete="off" rows="4" name="description" cols="50"></textarea>
                                            </div>
                                        </div>
                                        <!-- <div class="d-block my-4">
                                            <div class="col-lg-12">
                                                <button type="submit" name="appointmentSave" class="btn btn-primary custom-btn-lg" id="webAppointmentBtnSave">Save</button>
                                                <button type="submit" name="appointmentSave" class="btn btn-primary custom-btn-lg" id="webAppointmentBtnSave">Cancle</button>
                                                <div class="d-flex justify-content-end">
                                                    <input class="btn btn-primary me-2" type="submit" value="Save">
                                                    <a href="./patients.php" class="btn btn-secondary">Cancel</a>
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="d-flex justify-content-end mt-4">
                                            <input class="btn btn-primary me-2" name="appointmentSave" type="submit" value="Save">
                                            <a href="./appointments.php" class="btn btn-secondary">Cancel</a>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="./receptionist.js"></script>
<script>
    function validateForm() {
        var selectedDate = new Date(document.getElementById('opdDate').value);
        var currentDate = new Date();

        if (selectedDate < currentDate) {
            // alert('Please select a date and time that is not less than the current date and time.');
            return false;
        }

        return true;
    }
    var today = new Date().toISOString().split('T')[0];
    document.getElementsByName("opd_date")[0].setAttribute('min', today);
</script>
<?php
require_once("../components/footer.php");
?>