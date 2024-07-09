<?php
session_start();
include "../connection.php";
// Check cookies exists or not
if (!isset($_SESSION['user'])) {
    $_SESSION['success'] = false;
    $_SESSION['message'] = "Authentication failed";
    header("location: $appUrl/login.php");
} else if (isset($_SESSION["role"]) && $_SESSION["role"] != "patients") {
    setcookie('user', '', time() - 3600, '/');
    $_SESSION['success'] = false;
    $_SESSION['message'] = "You are not authorized to access the patient site.";
    header("location: $appUrl/login.php");
    exit;
}
$pageTitle = "Edit Profile";
require_once "../components/header.php";

try {
    if (isset($_SESSION['user'])) {
        $email = $_SESSION['email'];
        $user = "SELECT * FROM users where email ='$email'";
        $result = mysqli_query($connection, $user);
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
                }
            }
            $roleQuery = "SELECT first_name,last_name,phone_no FROM $role WHERE email='$email'";
            $roleResult = mysqli_query($connection, $roleQuery);

            if (mysqli_num_rows($roleResult) == 1) {
                $roleRow = mysqli_fetch_array($roleResult);
                $first_name = $roleRow['first_name'];
                $last_name = $roleRow['last_name'];
                $phone_no = $roleRow['phone_no'] ? $roleRow['phone_no'] : '';
                $image = !empty($roleRow['image']) ? $roleRow['image'] : '';
            }
        }
    } else {
        header("location: $appUrl/login.php");
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if (isset($_POST['btnProfile'])) {
    $imageName = $image;
    $targetDirectory = "../uploads/users/";
    $newFileName = uniqid() . "_" . basename($_FILES["userProfileImage"]["name"]); // Generate a unique name
    $targetFile = $targetDirectory . $newFileName;

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $allowedFormats = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowedFormats) && $imageFileType) {
        $_SESSION['success'] = false;
        $_SESSION['message'] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        header('Location: /admin/dashboard.php');
        exit;
    }

    if ($uploadOk == 0 && $imageFileType) {
        $_SESSION['success'] = false;
        $_SESSION['message'] = "File was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["userProfileImage"]["tmp_name"], $targetFile)) {
            $imageName = $newFileName;
            unlink("../uploads/users/" . $image);
        } else {
            $_SESSION['success'] = false;
            $_SESSION['message'] = "Error uploading file.";
        }
    }

    $updatedFirstName = $_POST['first_name'];
    $updatedLastName = $_POST['last_name'];
    $updatedPhone_no = $_POST['phone_no'];

    $updatedProfile = "UPDATE $role  SET image = '$imageName',first_name='$updatedFirstName',last_name='$updatedLastName', phone_no='$updatedPhone_no' WHERE email='$email'";
    $updateUser = mysqli_query($connection, $updatedProfile);
    if ($updateUser) {
        $_SESSION['success'] = true;
        $_SESSION['message'] = "Your profile has been updated successfully";
        $_SESSION['name'] = $updatedFirstName . " " . $updatedLastName;
        $_SESSION['image'] = $imageName;

        echo "<script>let userprofile = localStorage.getItem('user');
        let isSuccess = '" . $_SESSION['success'] . "';
        let name = '" . $_SESSION['name'] . "';
        let appUrl = '$appUrl';
        let image = '" . $_SESSION['image'] . "';
        if (isSuccess) {
            userprofile = JSON.parse(userprofile);
            localStorage.setItem('user', JSON.stringify({
                ...userprofile,
                name: name,
                appUrl:appUrl,
                image:image
            }));
            setTimeout(function() {
                window.location.href = '" . ("/hosptial/patient/dashboard.php") . "';
            }, 100);
        }</script>";
        exit;
    } else {
        $_SESSION['success'] = false;
        $_SESSION['message'] = "Error updating profile.";
    }
}

?>
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
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
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
        <!--  Header Start -->
        <?php require_once "../components/profileHeader.php" ?>
        <!--  Header End -->
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
          }; toastr.$toastType('$message');</script>";
                unset($_SESSION['message']);
                unset($_SESSION['success']);
            }
            ?>
            <div>
                <h3>Edit Profile</h3>
            </div>
        </div>
        <div class="row dashboard-widget-p-5">
            <div class="card container">
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-4">
                                    <div class="col-12"><label class="form-label mb-4">Change Image:</label>
                                        <div class="d-block">
                                            <div class="image-picker">
                                                <div class="image previewImage imagePreviewUrl"><img id="previewImage" src="<?php echo $image ? "../uploads/users/" . $image : "../assets/img/user.jpeg"; ?>" alt="img" width="75" height="100" class="image image-circle image-mini h-100"><span class="picker-edit rounded-circle text-gray-500 fs-small cursor-pointer">
                                                        <input class="upload-file" name="userProfileImage" id="imageInput" title="[object Object]" type="file" accept=".png, .jpg, .jpeg">
                                                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="pencil" class="svg-inline--fa fa-pencil " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                            <path fill="currentColor" d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"></path>
                                                        </svg></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="mb-2"><b>First Name: </b></label><span class="text-danger">*</span>
                                <input type="text" name="first_name" class="form-control" value="<?php echo $first_name; ?>" placeholder="First Name">
                            </div>
                            <div class="col-md-6">
                                <label class="mb-2"><b>Last Name: </b></label><span class="text-danger">*</span>
                                <input type="text" name="last_name" class="form-control" value="<?php echo $last_name; ?>" placeholder="Last Name">
                            </div>
                            <div class="col-md-6">
                                <label class="mb-2"><b>Phone No.: </b></label><span class="text-danger">*</span>
                                <input type="text" class="form-control" name="phone_no" value="<?php echo $phone_no; ?>" placeholder="Phone no.">
                            </div>
                            <div class="d-flex mt-5">
                                <div><button class="btn btn-outline-secondary me-3" name="btnProfile">Save</button></div><a class="btn btn-secondary me-3" href="/patient/dashboard.php">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#imageInput").on('change', function(event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function() {
                    $("#previewImage").attr('src', reader.result);
                };
                reader.readAsDataURL(file);
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        var form = document.querySelector("form");

        form.addEventListener("submit", function(event) {
            var usernameInput = document.querySelector("input[name='username']");
            var phoneNoInput = document.querySelector("input[name='phone_no']");

            if (usernameInput.value.trim() === "") {
                toastr.error("Please enter a username.");
                event.preventDefault();
                return;
            }

            if (phoneNoInput.value.trim() === "") {
                toastr.error("Please enter a phone number.");
                event.preventDefault();
                return;
            } else if (phoneNoInput.value.length != 10) {
                toastr.error("Please enter a phone number with exact 10 digits.");
                event.preventDefault();
                return;
            }

        });
    });
</script>

<script src="./patient.js"></script>
<?php
require_once "../components/footer.php";
?>