<?php
include "./connection.php";
session_start();

// ----------------------- Delete user -----------------------
if (isset($_POST["delete"])) {
  if (isset($_POST["email"])) {
    $email = $_POST["email"];

    if (isset($_POST["isDoctor"]) && $_POST['isDoctor'] == true) {
      $delete_query = "DELETE FROM doctors WHERE email = '$email'";
    } elseif (isset($_POST["isPatient"]) && $_POST['isPatient'] == true) {
      $delete_query = "DELETE FROM patients WHERE email = '$email'";
    } elseif (isset($_POST["isReceptionist"]) && $_POST['isReceptionist'] == true) {
      $delete_query = "DELETE FROM receptionists WHERE email = '$email'";
    } else {
      echo "Invalid user type.";
      exit;
    }

    $delete_users_query = "DELETE FROM users WHERE email = '$email'";
    $delete_users_result = mysqli_query($connection, $delete_users_query);

    $delete_result = mysqli_query($connection, $delete_query);

    if ($delete_users_result && $delete_result) {
      echo 1; // Successful deletion
    } else {
      $error_message = mysqli_error($connection);
      echo $error_message;
    }
  } elseif (isset($_POST["department_id"])) {
    $department_id = $_POST["department_id"];

    if (isset($_POST["isDepartment"]) && $_POST['isDepartment'] == true) {
      $delete_query = "DELETE FROM diagnosis WHERE id = $department_id";

      $delete_result = mysqli_query($connection, $delete_query);

      if ($delete_result) {
        echo 1; // Successful deletion
      } else {
        $error_message = mysqli_error($connection);
        echo $error_message;
      }
    } else {
      echo "Invalid diagnosis deletion request.";
    }
  } elseif (isset($_POST["appointment_id"])) {
    $appointment_id = $_POST["appointment_id"];

    if (isset($_POST["isAppointment"]) && $_POST['isAppointment'] == true) {
      $delete_query = "DELETE FROM appointments WHERE id = $appointment_id";

      $delete_result = mysqli_query($connection, $delete_query);

      if ($delete_result) {
        echo 1; // Successful deletion
      } else {
        $error_message = mysqli_error($connection);
        echo $error_message;
      }
    } else {
      echo "Invalid diagnosis deletion request.";
    }
  }
  elseif (isset($_POST["bed_id"])) {
    $bed_id = $_POST["bed_id"];

    if (isset($_POST["isBed"]) && $_POST['isBed'] == true) {
      $delete_query = "DELETE FROM beds WHERE id = $bed_id";

      $delete_result = mysqli_query($connection, $delete_query);

      if ($delete_result) {
        echo 1; // Successful deletion
      } else {
        $error_message = mysqli_error($connection);
        echo $error_message;
      }
    } else {
      echo "Invalid diagnosis deletion request.";
    }
  }

  elseif (isset($_POST["medicine_id"])) {
    $medicine_id = $_POST["medicine_id"];

    if (isset($_POST["isMedicine"]) && $_POST['isMedicine'] == true) {
      $delete_query = "DELETE FROM medicines WHERE id = $medicine_id";

      $delete_result = mysqli_query($connection, $delete_query);

      if ($delete_result) {
        echo 1; // Successful deletion
      } else {
        $error_message = mysqli_error($connection);
        echo $error_message;
      }
    } else {
      echo "Invalid diagnosis deletion request.";
    }
  }

  elseif (isset($_POST["ward_id"])) {
    $ward_id = $_POST["ward_id"];

    if (isset($_POST["isWard"]) && $_POST['isWard'] == true) {
      $delete_query = "DELETE FROM wards WHERE id = $ward_id";

      $delete_result = mysqli_query($connection, $delete_query);

      if ($delete_result) {
        echo 1; // Successful deletion
      } else {
        $error_message = mysqli_error($connection);
        echo $error_message;
      }
    } else {
      echo "Invalid ward deletion request.";
    }
  }

  else {
    echo "Missing email or department_id.";
  }
}

else if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["doctor"])) {

  if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
  }

  // Get form data
  $patientId = $_POST["patient_id"] ?? $_SESSION['id'];
  $role = isset($_SESSION["role"]) ? $_SESSION["role"] : 'patients';
  $patientName = isset($_POST['first_name']) ? $_POST['first_name'] : (isset($_SESSION['user']) ? $_SESSION['first_name'] : null);
  $lastName = isset($_POST['last_name']) ? $_POST['last_name'] : null;
  $diagnosis = $_POST["diagnosis"];
  $doctorId = $_POST["doctor"];
  $opdDate = $_POST["opd_date"];
  $description = $_POST["description"];
  // Check if email is received
  $email = isset($_POST["email"]) ? $_POST["email"] : null;
  if ($email === null && $role === "patients" || $role === "receptionists") {

    // Insert data into the appointments table
    $sql = "INSERT INTO appointments (patient_id, patient_name, diagnosis, doctor_id,status, opd_date, description)
            VALUES ('$patientId','$patientName', '$diagnosis', '$doctorId','Pending', '$opdDate', '$description')";
    if (mysqli_query($connection, $sql)) {
      // Appointment saved successfully
      echo 1;
      // header("Location: /patient/dashboard.php");
    } else {
      // Error in saving appointment
      echo mysqli_error($connection);
    }
  } else if ($email && $role === "patients" || $role === "receptionists") {
    // If email is received, check if the user already exists
    $checkUserSql = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($connection, $checkUserSql);

    if (mysqli_num_rows($result) > 0) {
      // User with the provided email already exists
      echo "User with this email already exists. Please login.";
    } else {
      // If user doesn't exist, proceed with the user and patient data validation and insertion
      if($_POST['password'] !== $_POST['password_confirmation']){
        echo 'Both Password are not same';
        exit;
      }
      $password = isset($_POST["password"]) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
      $passwordConfirmation = isset($_POST["password_confirmation"]) ? $_POST["password_confirmation"] : null;

      // Validate and insert data into the users table
      $userSql = "INSERT INTO users (email, roles) VALUES ('$email', 'p')";
      if (mysqli_query($connection, $userSql)) {
        $userId = mysqli_insert_id($connection);

        // Validate and insert data into the patients table
        $patientSql = "INSERT INTO patients (first_name,last_name, email, status, password)
        VALUES ('$patientName','$lastName', '$email', 1, '$password')";

        if (mysqli_query($connection, $patientSql)) {
          $patientId = mysqli_insert_id($connection);
          // Insert data into the appointments table
          $appointmentSql = "INSERT INTO appointments (patient_id, patient_name, diagnosis, doctor_id, opd_date, description,status)
                              VALUES ($patientId, '$patientName', '$diagnosis', $doctorId, '$opdDate', '$description','Pending')";

          if (mysqli_query($connection, $appointmentSql)) {
            // Appointment saved successfully
            echo 1;
            // header("Location: /patient/dashboard.php");
          } else {
            // Error in saving appointment
            echo mysqli_error($connection);
          }
        } else {
          // Error in saving patient data
          echo mysqli_error($connection);
        }
      } else {
        // Error in saving user data
        echo mysqli_error($connection);
      }
    }
  } else {
    echo "You are not able to book appointment";
  }
}



else if (isset($_POST['cancelAppointment'])) {
  try {
    $appointment_id = $_POST['appointment_id'];
    $canceled_by = $_SESSION['name']; // Assuming you have a user identifier in the session, adjust accordingly

    // Update the appointments table to mark the appointment as canceled and record who canceled it
    $updateQuery = "UPDATE appointments SET status = 'canceled', canceled_by = '$canceled_by' WHERE id = $appointment_id";

    if (mysqli_query($connection, $updateQuery)) {
      echo 1; // Successful cancellation
    } else {
      echo "Failed to cancel appointment. Please try again.";
    }
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
} else if (isset($_POST['prescriptionDetails'])) {
  $appointment_id = $_POST['appointment_id'];
  $prescriptionDetails = $_POST['prescriptionDetails'];

  // Check if there is an existing prescription for the appointment
  $checkExistingQuery = "SELECT * FROM prescriptions WHERE appointment_id = $appointment_id";
  $existingResult = mysqli_query($connection, $checkExistingQuery);

  if ($existingResult && $existingResult->num_rows > 0) {
    // If there is an existing prescription, update it
    $updatePrescriptionQuery = "UPDATE prescriptions SET prescription_details = '$prescriptionDetails' WHERE appointment_id = $appointment_id";
    $result = mysqli_query($connection, $updatePrescriptionQuery);
  } else {
    // If there is no existing prescription, insert a new one
    $insertPrescriptionQuery = "INSERT INTO prescriptions (appointment_id, prescription_details) VALUES ($appointment_id, '$prescriptionDetails')";
    $result = mysqli_query($connection, $insertPrescriptionQuery);
  }

  if ($result) {
    // Update the status in the appointments table
    $updateStatusQuery = "UPDATE appointments SET status = 'Confirm' WHERE id = $appointment_id";
    $resultStatusUpdate = mysqli_query($connection, $updateStatusQuery);

    if (!$resultStatusUpdate) {
      // Handle the error if the status update fails
      $_SESSION['success'] = false;
      $_SESSION['message'] = "Prescription added successfully, but failed to update appointment status.";
    } else {
      $_SESSION['success'] = true;
      $_SESSION['message'] = "Prescription added successfully";
    }
  } else {
    // Handle the error if prescription insertion/update fails
    $_SESSION['success'] = false;
    $_SESSION['message'] = "Failed to add/update prescription.";
  }

  header("location: ../doctor/view-appointment.php?id=$appointment_id");
  exit;
} else if (isset($_POST['updateStatus'])) {
  $doctorId = $_POST['doctor_id'];
  $status = $_POST['status'];

  // Prepare the SQL statement
  $sql = "UPDATE doctors SET status = ? WHERE id = ?";

  // Create a prepared statement
  $stmt = mysqli_prepare($connection, $sql);

  // Bind parameters to the prepared statement
  mysqli_stmt_bind_param($stmt, "ii", $status, $doctorId);

  // Execute the statement
  if (mysqli_stmt_execute($stmt)) {
    echo 1; // Success
  } else {
    echo "Error updating status";
  }

  // Close the statement
  mysqli_stmt_close($stmt);

  exit;
} else if (isset($_POST['updateReceptionistStatus'])) {
  $receptionist_id = $_POST['receptionist_id'];
  $status = $_POST['status'];

  // Prepare the SQL statement
  $sql = "UPDATE receptionists SET status = ? WHERE id = ?";

  // Create a prepared statement
  $stmt = mysqli_prepare($connection, $sql);

  // Bind parameters to the prepared statement
  mysqli_stmt_bind_param($stmt, "ii", $status, $receptionist_id);

  // Execute the statement
  if (mysqli_stmt_execute($stmt)) {
    echo 1; // Success
  } else {
    echo "Error updating status";
  }

  // Close the statement
  mysqli_stmt_close($stmt);

  exit;
} else if (isset($_POST['updatePatientStatus'])) {
  $patient_id = $_POST['patient_id'];
  $status = $_POST['status'];

  // Prepare the SQL statement
  $sql = "UPDATE patients SET status = ? WHERE id = ?";

  // Create a prepared statement
  $stmt = mysqli_prepare($connection, $sql);

  // Bind parameters to the prepared statement
  mysqli_stmt_bind_param($stmt, "ii", $status, $patient_id);

  // Execute the statement
  if (mysqli_stmt_execute($stmt)) {
    echo 1; // Success
  } else {
    echo "Error updating status";
  }

  // Close the statement
  mysqli_stmt_close($stmt);

  exit;
} else if (isset($_POST['save_medicine'])) {
  $appointment_id = $_POST['appointment_id'];
  $medicine_id = $_POST['medicine_id'];
  $dosage = $_POST['dosage'];
  $frequency = $_POST['frequency'];
  $duration = $_POST['duration'];

  $sql = "INSERT INTO prescribed_medicines (appointment_id, medicine_id, dosage, frequency, duration) 
          VALUES ($appointment_id, $medicine_id, '$dosage', '$frequency', '$duration')";

  if (mysqli_query($connection, $sql)) {
    $_SESSION['success'] = true;
    $_SESSION['message'] = "Medicine added successfully.";
  } else {
    $_SESSION['success'] = false;
    $_SESSION['message'] = "Error adding medicine.";
  }

  // $appointmentQuery = "SELECT appointment_id FROM prescriptions WHERE id = $prescription_id";
  // $appointmentResult = mysqli_query($connection, $appointmentQuery);
  // $appointmentRow = $appointmentResult->fetch_assoc();
  // $appointment_id = $appointmentRow['appointment_id'];

  header("location: doctor/view-appointment.php?id=$appointment_id");
  exit;
}

else if (isset($_POST['bed_id'] ) && isset($_POST['isUnAssigned'])) {
  $bed_id = mysqli_real_escape_string($connection, $_POST['bed_id']);

  // Delete from bed_assignments table
  $delete_query = "DELETE FROM bed_assignments WHERE bed_id = '$bed_id'";

  if (mysqli_query($connection, $delete_query)) {
      // Optionally, update the beds table to mark the bed as available
      $update_bed_query = "UPDATE beds SET status = 1 WHERE id = '$bed_id'";
      mysqli_query($connection, $update_bed_query);

      // Respond with success
      $response = [
          'success' => true,
          'message' => 'Bed unassigned successfully'
      ];
      echo json_encode($response);
      exit();
  } else {
      // Respond with failure
      $response = [
          'success' => false,
          'message' => 'Failed to unassign bed'
      ];
      echo json_encode($response);
      exit();
  }
} else {
  // Respond with failure if bed_id is not provided
  $response = [
      'success' => false,
      'message' => 'Bed ID not provided'
  ];
  echo json_encode($response);
  exit();
}