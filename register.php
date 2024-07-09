<?php
session_start();
// Include the database connection file
include 'connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $f_name = $_POST['first_name'];
    $l_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $gender = $_POST['gender'];

    // Perform basic validation (you should enhance this based on your requirements)
    if (empty($f_name) || empty($l_name) || empty($email) || empty($phone) || empty($password) || !isset($gender)) {
        $_SESSION['success'] = false;
        $_SESSION['message'] = "All fields are required.";
    } else {
        // Insert data into the database
        $query = "INSERT INTO users (email,roles)
                  VALUES ('$email','p')";
        $result = mysqli_query($connection, $query);

        $patientQuery = "INSERT INTO patients (first_name, last_name, email, phone_no,gender,status, password)
        VALUES ('$f_name', '$l_name', '$email', '$phone', '$gender',1, '$password')";
$patientResult = mysqli_query($connection, $patientQuery);

        if ($result && $patientResult) {
            $_SESSION['success'] = true;
            $_SESSION['message'] = "Registred successfully please login.";
            header("location: $appUrl/login.php");
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title> Register</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="./assets/toastr/toaster.min.css" rel="stylesheet">
    <script src="./assets/js/jquery.min.js"></script>
    <script src="./assets/toastr/toastr.min.js"></script>
    <link rel="stylesheet" href="./assets/css/login.css">
    <style>
        .form-check-input:checked {
            background-color: hsl(182, 100%, 35%);
            border-color: hsl(182, 100%, 35%);
        }
    </style>
</head>

<body class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed toolbar-tablet-and-mobile-fixed aside-enabled aside-fixed">
    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed authImage">
            <!--begin::Authentication - Sign-up -->

            <div class="d-flex flex-column flex-column-fluid align-items-center justify-content-center p-4">
                <div class="col-12 text-center">
                    <a href="./index.php" class="image mb-7 mb-sm-10" data-turbo="false">
                        <img alt="Logo" src="./favicon.svg" class="img-fluid logo-fix-size">
                    </a>
                </div>
                <div class="width-540">
                </div>
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
                <div class="bg-white rounded-15 shadow-md width-540 px-5 px-sm-7 py-10 mx-auto">
                    <h1 class="text-center mb-7">Registration</h1>
                    <form method="POST" action="#">
                        <div class="row">
                            <div class="col-md-6 mb-sm-7 mb-4">
                                <label for="formInputFirstName" class="form-label">
                                    First Name:<span class="required"></span>
                                </label>
                                <input name="first_name" type="text" class="form-control" id="first_name" placeholder=" First Name" aria-describedby="firstName" value="" onkeypress='if (/\s/g.test(this.value)) this.value = this.value.replace(/\s/g,"")'>
                            </div>
                            <div class="col-md-6 mb-sm-7 mb-4">
                                <label for="last_name" class="form-label">
                                    Last Name:<span class="required"></span>
                                </label>
                                <input name="last_name" type="text" class="form-control" id="last_name" placeholder=" Last Name" aria-describedby="lastName" onkeypress='if (/\s/g.test(this.value)) this.value = this.value.replace(/\s/g,"")' value="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-sm-7 mb-4">
                                <label for="email" class="form-label">
                                    Email:<span class="required"></span>
                                </label>
                                <input name="email" type="email" class="form-control" id="email" aria-describedby="email" placeholder=" Email" value="">
                            </div>
                            <div class="col-md-6 mb-sm-7 mb-4">
    <label for="phone" class="form-label required">Phone:</label>
    <input class="form-control phoneNumber" placeholder="Phone Number" name="phone" maxlength="10" type="tel" id="phone" required>
    <input class="prefix_code" name="prefix_code" type="hidden">
</div>


                        </div>  
                        <div class="row">
                            <div class="col-md-6 mb-sm-7 mb-4">
                                <label for="password" class="form-label">
                                    Password:<span class="required"></span>
                                </label>
                                <div class="mb-3 position-relative">
                                    <input type="password" name="password" class="form-control" id="password" onkeypress='if (/\s/g.test(this.value)) this.value = this.value.replace(/\s/g,"")' placeholder=" Password" aria-describedby="password" aria-label="Password" data-toggle="password">
                                </div>
                            </div>
                            <div class="col-md-6 mb-sm-7 mb-4">
                                <label for="password_confirmation" class="form-label">
                                    Confirm Password:<span class="required"></span>
                                </label>
                                <div class="mb-3 position-relative">
                                    <input name="password_confirmation" type="password" class="form-control" placeholder=" Confirm Password" id="password_confirmation" aria-describedby="confirmPassword" aria-label="Password" onkeypress='if (/\s/g.test(this.value)) this.value = this.value.replace(/\s/g,"")' data-toggle="password">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-sm-7 mb-4">
                            <div class="form-group mb-5">
                                <label class="form-label">Gender
                                    <span class="required"></span> &nbsp;<br>
                                </label>
                                <br>
                                <div class="d-flex align-items-center">

                                    <div class="form-check me-5">
                                        <input class="form-check-input" type="radio" name="gender" value="m" checked id="male" />
                                        <label class="form-check-label" for="male">
                                            Male
                                        </label>
                                    </div>

                                    <div class="form-check me-10">
                                        <input class="form-check-input" type="radio" name="gender" value="f" id="female" />
                                        <label class="form-check-label" for="female">
                                            Female
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn" style="background-color: hsl(182, 100%, 35%);color: white;">Submit</button>
                        </div>

                        <div class="d-flex align-items-center mt-4">
                            <span class="text-gray-700 me-2">Already registered ?</span>
                            <a href="./login.php" style="color: hsl(182, 100%, 35%);" class="link-info fs-6 text-decoration-none">
                                Login Here
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.getElementById('phone').addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
    });
</script>

</body>

</html>