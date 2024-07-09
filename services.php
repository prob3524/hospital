<?php
   session_start();
   include "connection.php";
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <!--
         - primary meta tags
         -->
      <title>Services</title>
      <!--
         - favicon
         -->
      <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml">
      <link rel="stylesheet" href="./assets/css/Services.css">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
         integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
         integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
         crossorigin="anonymous"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
      <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
      <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
      <link rel="stylesheet" href="./assets/css/style.css">
      <!--
         - preload images
         -->
      <link rel="preload" as="image" href="./assets/images/hero-banner.png">
      <link rel="preload" as="image" href="./assets/images/hero-bg.png">
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
                  <li class="navbar-item">
                        <a href="./beds.php" class="navbar-link title-md">Beds</a>
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
      <div class="bg-body-tertiary" style="margin-top: 9%;">
         <div class="container">
            <h5 class="main_title pb-4 text-center" style="font-size: 19px;" >HOSPITAL SERVICES</h5>
            <h2 class="sub_title text-center" style="font-size: 30px;" >Our Healthcare Service</h2>
            <div class="service-sec py-5">
               <div class="service-box d-flex bg-white p-4 rounded-5" >
                  <i class="bi bi-hospital fa-3x text-primary" style="color: hsl(182, 100%, 35%) !important;"></i>
                  <div class="px-3">
                     <h4 style="font-size: 23.46px;">Medical Treatment</h4>
                     <p>Medical treatment can encompass a wide range of topics, including various conditions,
                        procedures, medications, and therapies.
                     </p>
                  </div>
               </div>
               <div class="service-box d-flex bg-white p-4 rounded-5" >
                  <i class="fa fa-ambulance fa-3x text-primary" style="color: hsl(182, 100%, 35%) !important;"></i>
                  <div class="px-3">
                     <h4 style="font-size: 23.46px;">Emergency Help 24/7</h4>
                     <p>We provide round-the-clock care and support for COVID-19 patients, ensuring access to medical
                        attention whenever it's needed.
                     </p>
                  </div>
               </div>
               <div class="service-box d-flex bg-white p-4 rounded-5">
                  <i class="bi bi-capsule-pill fa-3x text-primary" style="color: hsl(182, 100%, 35%) !important;"></i>
                  <div class="px-3">
                     <h4 style="font-size: 23.46px;">Research Professionals</h4>
                     <p>These individuals conduct original research, design experiments, collect and analyze data,
                        and publish their findings in academic journals.
                     </p>
                  </div>
               </div>
               <div class="service-box d-flex bg-white p-4 rounded-5">
                  <i class="bi bi-heart-pulse-fill fa-3x text-primary" style="color: hsl(182, 100%, 35%) !important;"></i>
                  <div class="px-3">
                     <h4 style="font-size: 23.46px;">Check Ups</h4>
                     <p> These tests can help detect conditions like diabetes, heart disease, and kidney problems.
                        Regular blood pressure checks can help identify hypertension.
                     </p>
                  </div>
               </div>
               <div class="service-box d-flex bg-white p-4 rounded-5" >
                  <i class="bi bi-eye-fill fa-3x text-primary" style="color: hsl(182, 100%, 35%) !important;"></i>
                  <div class="px-3">
                     <h4 style="font-size: 23.46px;">Eye Care</h4>
                     <p>Eye care professionals can conduct various screenings to assess your eye health. These
                        include tests for conditions like glaucoma, macular degeneration.
                     </p>
                  </div>
               </div>
               <div class="service-box d-flex bg-white p-4 rounded-5" >
                  <i class="fa fa-stethoscope fa-3x text-primary" style="color: hsl(182, 100%, 35%) !important;"></i>
                  <div class="px-3">
                     <h4 style="font-size: 23.46px;">Diagnostics</h4>
                     <p>Diagnostic procedures play a crucial role in determining the cause of symptoms, guiding
                        treatment decisions, and monitoring the progress of medical conditions.
                     </p>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="py-5">
         <div class="container">
            <h5 class="main_title pb-4 text-center"  style="font-size: 19px;">POPULAR MEDICAL SERVICES</h5>
            <h2 class="sub_title text-center"  style="font-size:30px;">Benefit For Physical Mental and Virtual Care</h2>
            <div class="d-grid service-content text-center my-5">
               <div class=" service_box bg-info-subtle rounded-4 shadow-sm" >
                  <img src="./assets/img/service-icon3.png" alt="corona" class="mt-4">
                  <h4 style="font-size: 25px;">Cardiology</h4>
                  <div class="service_info text-center px-4 pb-4">
                     <p>At MedHelp, our top priority is the health and well-being of our community. In response to
                        the ongoing COVID-19 pandemic, we have established specialized treatment services to provide
                        exceptional care to patients affected by the virus.
                     </p>
                  </div>
               </div>
               <div class=" service_box bg-warning-subtle rounded-4 shadow-sm" >
                  <img src="./assets/img/service-icon6.png" alt="corona" class="mt-4">
                  <h4 style="font-size: 25px;">Orthopedics</h4>
                  <div class="service_info text-center px-4 pb-4">
                     <p>Our orthopedic team is dedicated to providing world-class care for a wide range of orthopedic
                        conditions and injuries. Whether you're dealing with joint pain, sports injuries, or need
                        specialized orthopedic surgery, we are here to support your journey to better mobility and
                        comfort.
                     </p>
                  </div>
               </div>
               <div class="service_box bg-warning-subtle rounded-4 shadow-sm" >
                  <img src="./assets/img/service-icon5.png" alt="corona" class="mt-4">
                  <h4 style="font-size: 25px;">Neurology</h4>
                  <div class="service_info text-center px-4 pb-4">
                     <p>At MedHelp, we believe in providing hope and improving the lives of our patients living with
                        neurological conditions. Our commitment to excellence, patient-centered care, and innovation
                        ensures that you receive the best possible care on your journey to recovery.
                     </p>
                  </div>
               </div>
               <div class="service_box bg-info-subtle rounded-4 shadow-sm" >
                  <img src="./assets/img/service-icon4.png" alt="corona" class="mt-4">
                  <h4 style="font-size: 25px;">Pulmonary</h4>
                  <div class="service_info text-center px-4 pb-4">
                     <p>Our team of dedicated pulmonologists and healthcare professionals is here to provide
                        comprehensive care for a wide range of respiratory conditions. Whether you're dealing with a
                        chronic lung disease, respiratory infection, or need specialized pulmonary care, we are
                        committed to your well-being.
                     </p>
                  </div>
               </div>
            </div>
         </div>
      </div>
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
                              Main Email : <a href="mailto:contact@website.com"
                                 class="contact-link">contact@&shy;website.com</a>
                           </p>
                           <p>
                              Inquiries : <a href="mailto:Info@mail.com" class="contact-link">Info@mail.com</a>
                           </p>
                        </div>
                     </li>
                     <li class="contact-item">
                        <div class="item-icon">
                           <ion-icon name="call-outline"></ion-icon>
                        </div>
                        <div>
                           <p>
                              Office Telephone : <a href="tel:0029129102320"
                                 class="contact-link">0029129102320</a>
                           </p>
                           <p>
                              Mobile : <a href="tel:000232439493" class="contact-link">000 2324 39493</a>
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
                     <a href="#" class="social-link">
                        <ion-icon name="logo-facebook"></ion-icon>
                     </a>
                  </li>
                  <li>
                     <a href="#" class="social-link">
                        <ion-icon name="logo-twitter"></ion-icon>
                     </a>
                  </li>
                  <li>
                     <a href="#" class="social-link">
                        <ion-icon name="logo-google"></ion-icon>
                     </a>
                  </li>
                  <li>
                     <a href="#" class="social-link">
                        <ion-icon name="logo-linkedin"></ion-icon>
                     </a>
                  </li>
                  <li>
                     <a href="#" class="social-link">
                        <ion-icon name="logo-pinterest"></ion-icon>
                     </a>
                  </li>
               </ul>
            </div>
         </div>
      </footer>
      <!--
         - #BACK TO TOP
         -->
      <a href="#top" class="back-top-btn" aria-label="back to top" data-back-top-btn>
         <ion-icon name="chevron-up"></ion-icon>
      </a>
      <!--
         - custom js link
         -->
      <script src="./assets/js/script.js"></script>
      <!--
         - ionicon link
         -->
      <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
      <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
      <script>
         const counts = document.querySelectorAll('.counter');
         const speed = 150;
         counts.forEach((counter) => {
             function upData() {
                 const target = Number(counter.getAttribute('data-target'));
                 const count = Number(counter.innerText)
                 const inc = target / speed
                 if (count < target) {
                     counter.innerText = Math.floor(inc + count)
                     setTimeout(upData, 1)
                 } else {
                     counter.innerText = target
                 }
             }
             upData()
         })
      </script>
   </body>
</html>