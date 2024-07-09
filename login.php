<?php
session_start();
include_once "connection.php";

// Redirect if logged in
function redirectIfLoggedIn($appUrl)
{
  if (isset($_SESSION['user'])) {
    if ($_SESSION['role'] == "users") {
      header("Location: $appUrl/admin/dashboard.php");
    } elseif ($_SESSION['role'] == "doctors") {
      header("Location: $appUrl/doctor/dashboard.php");
    } elseif ($_SESSION['role'] == "patients") {
      header("Location: $appUrl/patient/dashboard.php");
    }
    exit;
  }
}

// Handle Regular Login
function handleRegularLogin($connection, $appUrl)
{
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    // Query the common "users" table
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($connection, $query);
    if (mysqli_num_rows($result) == 1) {
      $row = mysqli_fetch_array($result);
      $role = "";
      $redirect_path = "";
      // Determine user role
      if (isset($row['roles'])) {
        if ($row['roles'] == "d") {
          $role = "doctors";
          $redirect_path = "$appUrl/doctor/dashboard.php";
        } elseif ($row['roles'] == "p") {
          $role = "patients";
          $redirect_path = "$appUrl/patient/dashboard.php";
        } elseif ($row['roles'] == "a") {
          $role = "admins";
          $redirect_path = "$appUrl/admin/dashboard.php";
        } elseif ($row['roles'] == "r") {
          $role = "receptionists";
          $redirect_path = "$appUrl/receptionist/dashboard.php";
        }
      }
      // Use the determined role to query the respective table
      $roleQuery = "SELECT * FROM $role WHERE email='$email'";
      $roleResult = mysqli_query($connection, $roleQuery);
      if (mysqli_num_rows($roleResult) == 1) {
        $roleRow = mysqli_fetch_array($roleResult);
        $status = $roleRow['status'];
        if ($status == true) {
          $comparePassword = password_verify($password, $roleRow['password']);

          if ($comparePassword) {
            // Set session variables
            $_SESSION['name'] = $roleRow['first_name'] . " " . $roleRow['last_name'];
            $_SESSION['first_name'] = $roleRow['first_name'];
            $_SESSION['last_name'] =   $roleRow['last_name'];
            $_SESSION['user'] = $roleRow;
            $_SESSION['email'] = $roleRow['email'];
            $_SESSION['id'] = $roleRow['id'];
            $_SESSION['image'] = $roleRow['image'];
            $_SESSION['role'] = $role;
            $_SESSION['success'] = true;
            $_SESSION['message'] = "Logged in successfully";

            // Encode the user data as JSON
            $userData = [
              'name' =>  $roleRow['first_name'] . " " . $roleRow['last_name'],
              'email' => $roleRow['email'],
              'id' => $roleRow['id'],
              'role' => $role,
              'appUrl' => $appUrl,
              'image' => $roleRow['image']
            ];
            // Use JavaScript to set the user data in localStorage
            echo "<script>
                          localStorage.setItem('user', '" . json_encode($userData) . "');
                          setTimeout(function() {
                              window.location.href = '" . $redirect_path . "';
                          }, 100);
                      </script>";
            exit;
          } else {
            $_SESSION['message'] = "Credentials not matched with our database.";
            $_SESSION['success'] = false;
          }
        } else {
          $_SESSION['message'] = "Your account is currently disabled. Please contact the admin";
          $_SESSION['success'] = false;
        }
        // Check password

      } else {
        $_SESSION['message'] = "User not found in the respective role table.";
        $_SESSION['success'] = false;
      }
    } else {
      $_SESSION['message'] = "User not found.";
      $_SESSION['success'] = false;
    }
  }
}

try {
  redirectIfLoggedIn($appUrl);
  handleRegularLogin($connection, $appUrl);
} catch (Exception $e) {
  echo $e;
  exit;
}
?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login</title>
  <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
  <link rel="stylesheet" type="text/css" href="./assets/css/login.css">
  <link rel="stylesheet" href="./assets/css/login.css">
  <link href="./assets/toastr/toaster.min.css" rel="stylesheet">
  <script src="./assets/js/jquery.min.js"></script>
  <script src="./assets/toastr/toastr.min.js"></script>
</head>

<body class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed toolbar-tablet-and-mobile-fixed aside-enabled aside-fixed">
  <div class="d-flex flex-column flex-root">
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
                }; toastr.$toastType('$message');</script>";
      unset($_SESSION['message']);
      unset($_SESSION['success']);
    }
    ?>
    <div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed authImage">
      <!--begin::Authentication - Sign-in -->

      <div class="d-flex flex-column flex-column-fluid align-items-center justify-content-top p-4" style="margin-top: 100px;">
        <div class="col-12 text-center">
          <a href="./index.php" class="image mb-7 mb-sm-10" data-turbo="false">
            <img alt="Logo" src="./favicon.svg" class="img-fluid logo-fix-size">
          </a>
        </div>
        <div class="width-540">
        </div>
        <div class="bg-white rounded-15 shadow-md width-540 px-5 px-sm-7 py-10 mx-auto">
          <h1 class="text-center mb-7">Sign In</h1>
          <form method="post" action="#">
            <div class="mb-sm-7 mb-4">
              <label for="email" class="form-label">
                Email:<span class="required"></span>
              </label>
              <input name="email" type="email" class="form-control" id="email" aria-describedby="emailHelp" required placeholder="Email" value="admin@hospital.com">
            </div>
            <div class="mb-sm-7 mb-4">
              <div class="d-flex justify-content-between">
                <label for="password" class="form-label">Password:<span class="required"></span></label>
                <a href="#" class="link-info fs-6 text-decoration-none" style="color: hsl(182, 100%, 35%);">
                  Forgot Password?
                </a>
              </div>
              <input name="password" type="password" class="form-control" id="password" required placeholder="Password" value="123456">
            </div>
            <div class="d-grid">
              <button type="submit" style="background-color: hsl(182, 100%, 35%);color: white;" class="btn">Login</button>
            </div>
            <div class="d-flex align-items-center mt-4">
              <span class="text-gray-700 me-2">Not registered yet?</span>
              <a href="./register.php" class="link-info fs-6 text-decoration-none" style="color: hsl(182, 100%, 35%);">
                Register Here.
              </a>
            </div>
          </form>
        </div>
        <div class="container">
          <div class="row">
            <div class="col-12">
              <div class="row d-flex justify-content-center">
                <div class="col-lg-9 col-12 all-btns my-5">
                  <div class="row">
                    <div class="col-md-4 col-lg-3 mb-4 col-6">
                      <a class="btn superadmin-login">Admin</a>
                    </div>
                    <div class="col-md-4 col-lg-3 mb-4 col-6">
                      <a class="btn doctor-login ">Doctor</a>
                    </div>
                    <div class="col-md-4 col-lg-3 mb-4 col-6">
                      <a class="btn receptionist-login ">Receptionist</a>
                    </div>
                    <div class="col-md-4 col-lg-3 mb-4 col-6">
                      <a class="btn patient-login ">Patient</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    toastr.options = {
      positionClass: "toast-top-right",
      timeOut: 2000,
      progressBar: true,
    };
  </script>
  <script>
    // Function to set the email based on the button clicked
    function setEmail(role) {
      const emailInput = document.getElementById('email');

      // Define email addresses for each role
      const roleEmails = {
        admin: 'admin@hospital.com',
        doctor: 'doctor@hospital.com',
        receptionist: 'receptionist@hospital.com',
        patient: 'patient@hospital.com'
      };

      // Set the email based on the selected role
      emailInput.value = roleEmails[role];
    }

    // Add event listeners to the buttons
    document.addEventListener('DOMContentLoaded', function() {
      const adminBtn = document.querySelector('.superadmin-login');
      const doctorBtn = document.querySelector('.doctor-login');
      const receptionistBtn = document.querySelector('.receptionist-login');
      const patientBtn = document.querySelector('.patient-login');

      // Add click event listeners to each button
      adminBtn.addEventListener('click', function() {
        setEmail('admin');
      });

      doctorBtn.addEventListener('click', function() {
        setEmail('doctor');
      });

      receptionistBtn.addEventListener('click', function() {
        setEmail('receptionist');
      });

      patientBtn.addEventListener('click', function() {
        setEmail('patient');
      });
    });
  </script>

</body>

</html>