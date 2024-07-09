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
      <title>About Us</title>
      <!--
         - favicon
         -->
      <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml">
      <link rel="stylesheet" href="./assets/css/About.css">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
         integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
         integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
         crossorigin="anonymous"></script>
      <!--
         - google font link
         -->
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Rubik:wght@400;500;700&display=swap"
         rel="stylesheet">
      <!--
         - custom css link
         -->
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
      <div class="container-fluid" style="margin-top: 10%;">
         <div class="row">
            <div class="col-md-12">
               <div class="container">
                  <div class="row d-flex justify-content-between">
                     <div class="col-md-5">
                        <h5 style="color: #00A3C8; font-weight: 600;">INTRODUCING A NEW HEALTH CARE</h5>
                        <h1 style="font-size: 44px; font-weight: 700; letter-spacing: 5; color: #004861;">Short
                           Story About <br />
                           Fovia Clinic Since 1999.
                        </h1>
                        <p class="mt-5">
                           Like education healthcare is also need to be given importance. We need a cost-effective,
                           high-quality health care system, guaranteeing health care to all of our people as a
                           right. Take care of your health, that it may serve you to serve God.
                        </p>
                     </div>
                     <div class="col-md-5 image-column mt-3">
                        <img src="./assets/img/home-hero-img.jpg" alt="Your Image" class="img-fluid">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- about section container -->
      <div class="py-5">
         <div class="container">
            <div class="card mb-3" style="border: none;">
               <div class="row d-flex justify-content-between">
                  <div class="col-md-5">
                     <img src="https://thumbs.dreamstime.com/b/doctors-hospital-modern-medicine-group-successful-background-61422906.jpg"
                        class="img-fluid" alt="...">
                  </div>
                  <div class="col-md-5">
                     <div class="card-body">
                        <h1 class="sub_title">About DocLab</h1>
                        <p class="card-text"><span style="font-size: 30px; color: #00A3C8;">W</span>elcome to
                           DocLab, a leading
                           provider of innovative healthcare management solutions. Our mission is to revolutionize
                           healthcare administration and enhance patient care through cutting-edge technology and
                           exceptional service.At MedHelp, we are dedicated to revolutionizing the way healthcare
                           organizations manage their operations. Our Hospital Management System (HMS) is the
                           culmination of cutting-edge technology.
                        </p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- state counter -->
      <div class="state-container">
         <div class="container">
            <div class="state-section text-white">
               <div class="state-box text-center bg-info-subtle">
                  <h1 class="counter" data-target="211">0</h1>
                  <h3>Successful Operation</h3>
                  <p>By Our Experienced Surgeons</p>
               </div>
               <div class="state-box text-center bg-secondary-subtle">
                  <h1 class="counter" data-target="345">0</h1>
                  <h3>Special Care</h3>
                  <p>By The Expert Doctors</p>
               </div>
               <div class="state-box text-center bg-primary-subtle">
                  <h1 class="counter" data-target="412">0</h1>
                  <h3>Free Outdoor</h3>
                  <p>Checkups by Senior Doctors</p>
               </div>
               <div class="state-box text-center bg-dark-subtle">
                  <h1 class="counter" data-target="154">0</h1>
                  <h3>Blood & Urine</h3>
                  <p>Tests in Laboratory</p>
               </div>
            </div>
         </div>
      </div>
      <!-- new technology section -->
      <div class="py-5 mt-5">
         <div class="container">
            <div class="card mb-3" style="border: none;">
               <div class="row g-0">
                  <div id="carouselExampleControls" class="carousel slide col-md-6" data-bs-ride="carousel">
                     <div class="carousel-inner">
                        <div class="carousel-item active">
                           <img src="./assets/img/aboutus-carousal-1.webp" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                           <img src="https://www.medtecheurope.org/wp-content/themes/ak-medtecheurope-2018/assets/img/heading-2-02.jpg"
                              class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                           <img src="./assets/img/aboutus-carousal-3.jpg" class="d-block w-100" alt="...">
                        </div>
                     </div>
                     <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
                        data-bs-slide="prev">
                     <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                     <span class="visually-hidden">Previous</span>
                     </button>
                     <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
                        data-bs-slide="next">
                     <span class="carousel-control-next-icon" aria-hidden="true"></span>
                     <span class="visually-hidden">Next</span>
                     </button>
                  </div>
                  <div class="col-md-6 px-2">
                     <div class="card-body">
                        <h1 class="sub_title" style="font-size: 30px;">New Generation High Technology</h1>
                        <p class="card-text py-4" style="padding-right: 10px; letter-spacing: 1px;"><span
                           style="font-size: 30px; color: #00A3C8;">W</span>e provide services in our hospital
                           by moving forward with confidence with constantly renewed technology and future-oriented
                           investments. It receives reports with state-of-the-art devices. We start the treatment
                           by making the correct diagnosis with our specialist physicians.
                        </p>
                     </div>
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