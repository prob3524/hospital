<?php
session_start();
include "connection.php";
$first_name = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : '';
$last_name = isset($_SESSION['last_name']) ? $_SESSION['last_name'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Appoinment</title>
   <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
   <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
   <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
   <link href="./assets/toastr/toaster.min.css" rel="stylesheet">
   <link rel="stylesheet" href="./assets/css/style.css">
   <script src="./assets/toastr/toastr.min.js"></script>
   <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
   <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <style>
      #webAppointmentBtnSave{
         font-size:17px !important;
      }

      .swal2-html-container{
         font-size:16px !important;
      }
      .swal2-confirm{
         font-size: 13.5px !important;
      }
   </style>
   <script>
      $(document).ready(function() {
         first_name = '<?php echo $first_name; ?>';
         last_name = '<?php echo $last_name; ?>';
         $("#firstName").val(first_name);
         $("#lastName").val(last_name);
         $.ajax({
            url: 'fetch.php?allDepartments=true',
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
               url: 'fetch.php', // Create this PHP file to fetch doctors based on diagnosis
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
   <script>
      $(document).ready(function() {
         // Your existing code

         // Handle form submission
         $('form').submit(function(e) {
            e.preventDefault(); // Prevent the form from submitting in the traditional way

            // Collect all form data
            var formData = $(this).serialize();

            // Perform AJAX request
            $.ajax({
               url: 'queries.php', // Replace 'submit.php' with the actual URL where you want to handle form submission
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
                           location.reload(true); // Reload the page
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
   </script>
</head>

<body id="top">
   <!--
         - #PRELOADER
         -->
   <div class="preloader" data-preloader>
      <div class="circle"></div>
   </div>
   <!--
         - #HEADER
         -->
   <header class="header active" data-header>
      <div class="container">
         <a href="./index.php" class="logo">
            <img src="./assets/images/logo.svg" width="136" height="46" alt="Doclab home">
         </a>
         <nav class="navbar" data-navbar>
            <div class="navbar-top">
               <a href="./index.php" class="logo">
                  <img src="./assets/images/logo.svg" width="136" height="46" alt="Doclab home">
               </a>
               <button class="nav-close-btn" aria-label="clsoe menu" data-nav-toggler>
                  <ion-icon name="close-outline" aria-hidden="true"></ion-icon>
               </button>
            </div>
            <ul class="navbar-list">
               <li class="navbar-item">
                  <a href="./index.php" class="navbar-link title-md">Home</a>
               </li>
               <li class="navbar-item">
                  <a href="./about.php" class="navbar-link title-md">About Us</a>
               </li>
               <!-- <li class="navbar-item">
                     <a href="#" class="navbar-link title-md">Doctors</a>
                     </li> -->
               <li class="navbar-item">
                  <a href="./services.php" class="navbar-link title-md">Services</a>
               </li>
               <li class="navbar-item">
                  <a href="#" class="navbar-link title-md">Blog</a>
               </li>
               <!-- <li class="navbar-item">
                     <a href="#" class="navbar-link title-md">Contact</a>
                     </li> -->
            </ul>
            <ul class="social-list">
               <li>
                  <a href="#" class="social-link">
                     <ion-icon name="logo-twitter"></ion-icon>
                  </a>
               </li>
               <li>
                  <a href="#" class="social-link">
                     <ion-icon name="logo-facebook"></ion-icon>
                  </a>
               </li>
               <li>
                  <a href="#" class="social-link">
                     <ion-icon name="logo-pinterest"></ion-icon>
                  </a>
               </li>
               <li>
                  <a href="#" class="social-link">
                     <ion-icon name="logo-instagram"></ion-icon>
                  </a>
               </li>
               <li>
                  <a href="#" class="social-link">
                     <ion-icon name="logo-youtube"></ion-icon>
                  </a>
               </li>
            </ul>
         </nav>
         <button class="nav-open-btn" aria-label="open menu" data-nav-toggler>
            <ion-icon name="menu-outline"></ion-icon>
         </button>
         <?php
               // Check if the user is not logged in
               if (!isset($_SESSION['role'])) {
                 echo '<a href="./login.php" class="btn has-before title-md">Login</a>';
                 echo '<a href="./appointment.php" style="margin-left: 5px;" class="has-before btn title-md">Appointment</a>';
               } else {
                 if ($_SESSION['role'] == 'doctors') {
                   $path = "$appUrl/doctor/dashboard.php";
                 } else  if ($_SESSION['role'] == 'patients') {
                  $path = "$appUrl/patient/dashboard.php";
                } else  if ($_SESSION['role'] == 'admins') {
                  $path = "$appUrl/admin/dashboard.php";
                }else {
                   $path = "$appUrl/receptionist/dashboard.php";
                 }
                 echo "<a href='$path' class='btn has-before title-md'>Dashboard</a>";
                 echo '<a href="./appointment.php" style="margin-left: 5px;" class="has-before btn title-md">Appointment</a>';
               }
               ?>
         <div class="overlay" data-nav-toggler data-overlay></div>
      </div>
   </header>
   <main style="margin-top: 10%;">
      <div class="appointment-page">
         <!-- start hero section -->
         <section class="hero-section position-relative p-t-60 border-bottom-right-rounded border-bottom-left-rounded bg-gray overflow-hidden">
            <div class="container">
               <div class="row align-items-center">
                  <div class="col-lg-6 text-lg-start text-center">
                     <div class="hero-content">
                        <h1 class="mb-3 pb-1">
                           Make Appointment
                        </h1>
                        <nav aria-label="breadcrumb">
                           <ol class="breadcrumb justify-content-lg-start justify-content-center mb-lg-0 mb-5">
                              <li class="breadcrumb-item">
                                 <a href="./index.php">Home</a>
                              </li>
                              <li class="breadcrumb-item active" aria-current="page">
                                 Make Appointment
                              </li>
                           </ol>
                        </nav>
                     </div>
                  </div>
                  <div class="col-lg-6 text-lg-end text-center">
                     <img src="https://naturebox.com/static/media/giant-calendar.2d312369.png" alt="Infy Care" class="img-fluid">
                  </div>
               </div>
            </div>
         </section>
         <!-- end hero section -->
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
                     <h2 class="mb-3">Make an Appointment</h2>
                  </div>
                  <div class="row">
                     <div class="col-lg-4 col-md-6">
                        <div class="appointment-form__input-block">
                           <label for="patient_name" class="form-label">First Name:<span class="required">*</span></label>
                           <input class="form-control disabled" value="<?php if (isset($_SESSION["user"])) echo $_SESSION['first_name'] ?>" id="firstName" <?php if (isset($_SESSION["user"])) {
                                                                                    echo "disabled";
                                                                                 } ?> placeholder="First Name" autocomplete="off" required="" name="first_name" type="text">
                           <input id="patient" name="patient_id" value="<?php if (isset($_SESSION["user"])) echo $_SESSION['id'] ?>" type="hidden">
                        </div>
                     </div>
                     <div class="col-lg-4 col-md-6">
                        <div class="appointment-form__input-block">
                           <label for="patient_name" class="form-label">Last Name:<span class="required">*</span></label>
                           <input class="form-control disabled" value="<?php if (isset($_SESSION["user"])) echo $_SESSION['last_name'] ?>" id="lastName" <?php if (isset($_SESSION["user"])) {
                                                                                    echo "disabled";
                                                                                 } ?> placeholder="Last Name" autocomplete="off" required="" name="last_name" type="text">
                        </div>
                     </div>
                     <?php // Check if the user is not logged in
                     if (!isset($_SESSION["user"])) {
                        echo '<div class="col-lg-4 col-md-6 patient-email-div">
                           <div class="appointment-form__input-block">
                           <label for="email" class="form-label">Email:
                           <span class="required">*</span>
                           </label>
                           <input class="form-control old-patient-email" placeholder="Email" autocomplete="off" required="" name="email" type="email">
                           </div>
                           </div>';

                        echo '<div class="col-lg-4 col-md-6 password-div">
                           <div class="appointment-form__input-block">
                           <label for="password" class="form-label">Password:<span class="required">*</span></label>
                           <input class="form-control" placeholder="Password" required="" min="6" max="10" id="password" name="password" type="password" value="">
                           </div>
                           </div>';

                        echo '<div class="col-lg-4 col-md-6 confirm-password-div">
                           <div class="appointment-form__input-block">
                           <label for="confirmPassword" class="form-label">Confirm Password:<span class="required">*</span></label>
                           <input class="form-control" placeholder="Confirm Password" required="" min="6" max="10" id="confirmPassword" name="password_confirmation" type="password" value="">
                           </div>
                           </div>';
                     } ?>
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
                     <div class="d-block my-4">
                        <div class="col-lg-12 text-center my-4">
                           <button type="submit" name="appointmentSave" class="btn btn-primary custom-btn-lg" id="webAppointmentBtnSave">Save</button>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </section>
      </div>
   </main>
   <!--
         - #FOOTER
         -->
   <footer class="footer" style="background-image: url('./assets/images/footer-bg.png')">
      <div class="container">
         <div class="section footer-top">
            <div class="footer-brand" data-reveal="bottom">
               <a href="#" class="logo">
                  <img src="./assets/images/logo.svg" width="136" height="46" loading="lazy" alt="Doclab home">
               </a>
               <ul class="contact-list has-after">
                  <li class="contact-item">
                     <div class="item-icon">
                        <ion-icon name="mail-open-outline"></ion-icon>
                     </div>
                     <div>
                        <p>
                           Main Email: <a href="mailto:contact@website.com" class="contact-link">contact@&shy;website.com</a>
                        </p>
                        <p>
                           Inquiries: <a href="mailto:Info@mail.com" class="contact-link">Info@mail.com</a>
                        </p>
                     </div>
                  </li>
                  <li class="contact-item">
                     <div class="item-icon">
                        <ion-icon name="call-outline"></ion-icon>
                     </div>
                     <div>
                        <p>
                           Office Telephone: <a href="tel:0029129102320" class="contact-link">0029129102320</a>
                        </p>
                        <p>
                           Mobile: <a href="tel:000232439493" class="contact-link">000 2324 39493</a>
                        </p>
                     </div>
                  </li>
               </ul>
            </div>
            <div class="footer-list" data-reveal="bottom">
               <p class="headline-sm footer-list-title">About Us</p>
               <p class="text">
                  Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed
               </p>
               <address class="address">
                  <ion-icon name="map-outline"></ion-icon>
                  <span class="text">
                     2416 Mapleview Drive <br>
                     Tampa, FL 33634
                  </span>
               </address>
            </div>
            <ul class="footer-list" data-reveal="bottom">
               <li>
                  <p class="headline-sm footer-list-title">Services</p>
               </li>
               <li>
                  <a href="#" class="text footer-link">Conditions</a>
               </li>
               <li>
                  <a href="#" class="text footer-link">Listing</a>
               </li>
               <li>
                  <a href="#" class="text footer-link">How It Works</a>
               </li>
               <li>
                  <a href="#" class="text footer-link">What We Offer</a>
               </li>
               <li>
                  <a href="#" class="text footer-link">Latest News</a>
               </li>
               <li>
                  <a href="#" class="text footer-link">Contact Us</a>
               </li>
            </ul>
            <ul class="footer-list" data-reveal="bottom">
               <li>
                  <p class="headline-sm footer-list-title">Useful Links</p>
               </li>
               <li>
                  <a href="#" class="text footer-link">Conditions</a>
               </li>
               <li>
                  <a href="#" class="text footer-link">Terms of Use</a>
               </li>
               <li>
                  <a href="#" class="text footer-link">Our Services</a>
               </li>
               <li>
                  <a href="#" class="text footer-link">Join as a Doctor</a>
               </li>
               <li>
                  <a href="#" class="text footer-link">New Guests List</a>
               </li>
               <li>
                  <a href="#" class="text footer-link">The Team List</a>
               </li>
            </ul>
            <div class="footer-list" data-reveal="bottom">
               <p class="headline-sm footer-list-title">Subscribe</p>
               <form action="" class="footer-form">
                  <input type="email" name="email" placeholder="Email" class="input-field title-lg">
                  <button type="submit" class="btn has-before title-md">Subscribe</button>
               </form>
               <p class="text">
                  Get the latest updates via email. Any time you may unsubscribe
               </p>
            </div>
         </div>
         <div class="footer-bottom">
            <p class="text copyright">
               &copy; Doclab 2024 | All Rights Reserved by TechTitans
            </p>
            <ul class="social-list">
               <li>
                  <a href="#" class="social-link"><ion-icon name="logo-facebook"></ion-icon></a>
               </li>
               <li>
                  <a href="#" class="social-link"><ion-icon name="logo-twitter"></ion-icon></a>
               </li>
               <li>
                  <a href="#" class="social-link"><ion-icon name="logo-google"></ion-icon></a>
               </li>
               <li>
                  <a href="#" class="social-link"><ion-icon name="logo-linkedin"></ion-icon></a>
               </li>
               <li>
                  <a href="#" class="social-link"><ion-icon name="logo-pinterest"></ion-icon></a>
               </li>
            </ul>
         </div>
      </div>
   </footer>

   <script src="./assets/js/script.js"></script>
   <script>
      function validateForm() {
         var selectedDate = new Date(document.getElementById('opdDate').value);
         var currentDate = new Date();

         if (selectedDate < currentDate) {
            console.log('Please select a date and time that is not less than the current date and time.');
            return false;
         }

         return true;
      }
      var today = new Date().toISOString().split('T')[0];
      document.getElementsByName("opd_date")[0].setAttribute('min', today);
   </script>

</body>

</html>