<?php
session_start();
include "connection.php";

// Fetch all available beds with their status and patient details if assigned
$beds_query = "
    SELECT beds.id, beds.bed_name, beds.bed_type, beds.status, 
           IFNULL(CONCAT(patients.first_name, ' ', patients.last_name), '') AS patient_name,
           bed_assignments.assign_date, beds.bed_charge
    FROM beds
    LEFT JOIN bed_assignments ON beds.id = bed_assignments.bed_id
    LEFT JOIN patients ON bed_assignments.ipd_patient_department_id = patients.id
    WHERE beds.status = 1
";
$beds_result = mysqli_query($connection, $beds_query);

// Organize beds by bed type
$beds_by_type = [];
while ($bed = mysqli_fetch_assoc($beds_result)) {
    $beds_by_type[$bed['bed_type']][] = $bed;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Beds</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/index.css">
    
  <!-- <link rel="stylesheet" href="./assets/bootstrap/bootstrap.min.css" /> -->
  <link rel="stylesheet" href="./assets/font-awesome/css/all.min.css" />
  <!-- <link rel="stylesheet" href="./assets/css/adminStyle.css"> -->
  <link href="./assets/quill/quill.snow.css" rel="stylesheet">
  <link rel="stylesheet" href="./assets/datatable/jquery.dataTables.min.css">
  <link href="./assets/toastr/toaster.min.css" rel="stylesheet">
  <script src="./assets/js/jquery.min.js"></script>
  <script src="./assets/datatable/jquery.dataTables.min.js"></script>
  <script src="./assets/quill/quill.js"></script>
  <script src="./assets/toastr/toastr.min.js"></script>
  <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml">

    <style>
        body {
            overflow-y: scroll;
        }

        /* .bed-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        } */

        .bed-item {
            width: 120px;
            height: 120px;
            border: 1px solid #ccc;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
        }

        .bed-item.available {
            background-color: #d4edda;
            margin-left:10px;
            color: #155724;
        }

        .bed-item.occupied {
            background-color: #f8d7da;
            color: #721c24;
        }

        .bed-item i {
            font-size: 2.5em;
        }

        .bed-item p {
            margin: 0;
            font-size: 1.2em;
        }
        .modal-content{
border-radius:1.3rem;
        }
        .modal-title{
font-size:2.25rem;
        }
        .modal-footer button{
font-size:15px;
        }
        .modal-header, .modal-footer{
            border:none !important
        }
    </style>
</head>

<body id="top">
    <!-- Header Section -->
    <header class="header active" data-header>
        <div class="container">
            <a href="./index.php" class="logo">
                <img src="./assets/images/logo.svg" width="136" height="46" alt="Doclab home">
            </a>
            <nav class="navbar" data-navbar>
                <!-- Navbar Content -->
                <ul class="navbar-list">
                    <li class="navbar-item">
                        <a href="./index.php" class="navbar-link title-md">Home</a>
                    </li>
                    <li class="navbar-item">
                        <a href="./about.php" class="navbar-link title-md">About Us</a>
                    </li>
                    <li class="navbar-item">
                        <a href="./beds.php" class="navbar-link title-md">Beds</a>
                    </li>
                    <li class="navbar-item">
                        <a href="./services.php" class="navbar-link title-md">Services</a>
                    </li>
                    <li class="navbar-item">
                        <a href="#" class="navbar-link title-md">Blog</a>
                    </li>
                </ul>
            </nav>
            <?php
            // Check if the user is not logged in
            if (!isset($_SESSION['role'])) {
                echo '<a href="./login.php" class="btn has-before title-md">Login</a>';
                echo '<a href="./appointment.php" style="margin-left: 5px;" class="has-before btn title-md">Appointment</a>';
            } else {
                if ($_SESSION['role'] == 'doctors') {
                    $path = "$appUrl/doctor/dashboard.php";
                } else if ($_SESSION['role'] == 'patients') {
                    $path = "$appUrl/patient/dashboard.php";
                } else if ($_SESSION['role'] == 'admins') {
                    $path = "$appUrl/admin/dashboard.php";
                } else {
                    $path = "$appUrl/receptionist/dashboard.php";
                }
                echo "<a href='$path' class='btn has-before title-md'>Dashboard</a>";
                echo '<a href="./appointment.php" style="margin-left: 5px;" class="has-before btn title-md">Appointment</a>';
            }
            ?>
        </div>
    </header>

    <!-- Bed Section -->
    <main>
        <section class="section beds" aria-labelledby="beds-label">
            <div class="container">
                <h1 id="beds-label" class="headline-lg text-center">Available Beds</h1>

                <?php
                foreach ($beds_by_type as $bed_type => $beds) {
                    echo '<h2 class="mt-5">' . htmlspecialchars($bed_type) . '</h2>';
                    echo '<div class="row mt-3" style="border:1px solid #dee2e6; padding: 15px;">';

                    if (empty($beds)) {
                        echo '<p>No beds available in this category.</p>';
                    } else {
                        foreach ($beds as $bed) {
                            $status_class = $bed['status'] === '0' ? 'occupied' : 'available';
                            $patient_name = isset($bed['patient_name']) ? htmlspecialchars($bed['patient_name']) : '';
                            $assign_date = isset($bed['assign_date']) ? htmlspecialchars($bed['assign_date']) : '';
                            $bed_charge = isset($bed['bed_charge']) ? htmlspecialchars($bed['bed_charge']) : '';

                            echo '<div class="bed-item col-md-2 ' . $status_class . '" data-toggle="modal" data-target="#bedDetailsModal" data-bed-id="' . $bed['id'] . '" data-patient="' . $patient_name . '" data-assign-date="' . $assign_date . '" data-bed-charge="' . $bed_charge . '" data-bed-name="' . htmlspecialchars($bed['bed_name']) . '" data-bed-type="' . htmlspecialchars($bed_type) . '">';
                            echo '<i class="fa fa-bed fa-3x"></i>';
                            echo '<p class="mt-2">' . htmlspecialchars($bed['bed_name']) . '</p>';
                            echo '</div>';
                        }
                    }

                    echo '</div>'; // Close row
                }
                ?>
            </div>
        </section>
    </main>

    <!-- Modal Structure -->
    <div class="modal fade" id="bedDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Bed Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="modalBedName"></p>                    <p id="modalWardName"></p>
                    <p id="modalBedCharge"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <a href="#top" class="back-top-btn" aria-label="back to top" data-back-top-btn>
        <ion-icon name="chevron-up"></ion-icon>
    </a>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Function to show bed details in modal
        $('#bedDetailsModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var bedName = button.data('bed-name');
            var wardName = button.data('bed-type');
            var bedCharge = button.data('bed-charge');

            var modal = $(this);
            modal.find('#modalBedName').text('Bed Name: ' + bedName);
            modal.find('#modalWardName').text('Ward Name: ' + wardName);
            modal.find('#modalBedCharge').text('Bed Charge: ₹' + bedCharge);
        });
    </script>
</body>

</html>
