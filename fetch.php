<?php
include "connection.php";
session_start();
function queryToJSON($query)
{
    include "connection.php";
    $result = mysqli_query($connection, $query);
    $data = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    header("Content-Type: application/json");
    echo json_encode($data);
}

if (isset($_GET['allPatients'])) {
    try {
        $query = "SELECT * FROM patients;";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else if (isset($_GET['allDoctors'])) {
    try {
        $query = "SELECT * FROM doctors";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else if (isset($_GET['allBeds'])) {
    try {
        $query = "SELECT * FROM beds";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}  else if (isset($_GET['allWards'])) {
    try {
        $query = "SELECT * FROM wards";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else if (isset($_GET['allReceptionists'])) {
    try {
        $query = "SELECT * FROM receptionists";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else if (isset($_GET['allDepartments'])) {
    try {
        $query = "SELECT * FROM diagnosis";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else if (isset($_POST['department_id'])) {
    try {
        $department_id = $_POST['department_id'];
        $query = "SELECT * FROM diagnosis where id = $department_id";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else if (isset($_POST['bed_id'])) {
    try {
        $bed_id = $_POST['bed_id'];
        $query = "SELECT * FROM beds where id = $bed_id";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else if (isset($_POST['ward_id'])) {
    try {
        $ward_id = $_POST['ward_id'];
        $query = "SELECT * FROM wards where id = $ward_id";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['diagnosis'])) {
    $diagnosis = $connection->real_escape_string($_POST['diagnosis']);

    // Fetch doctors based on the selected diagnosis
    $query = "SELECT id, CONCAT(first_name, ' ', last_name) AS full_name FROM doctors WHERE diagnosis_id = '$diagnosis'";
    $result = $connection->query($query);

    if ($result) {
        $doctors = $result->fetch_all(MYSQLI_ASSOC);

        // Generate options for the doctor dropdown
        $options = '<option value="">Select Doctor</option>';
        foreach ($doctors as $doctor) {
            $options .= "<option value='{$doctor['id']}'>{$doctor['full_name']}</option>";
        }

        echo $options;
    } else {
        // Handle query error
        echo "Error: " . $connection->error;
    }
} else if (isset($_GET['allAppointments'])) {
    try {
        $query = "SELECT CONCAT(d.first_name, ' ', d.last_name) AS doctor_name, a.*, p.email
                      FROM appointments a
                      JOIN doctors d ON a.doctor_id = d.id
                      JOIN patients p ON a.patient_id = p.id";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else if (isset($_GET['patientAppointments'])) {
    try {
        $id = $_SESSION['id'];
        $query = "SELECT CONCAT(d.first_name, ' ', d.last_name) AS doctor_name, a.*, p.email, pr.prescription_details
                  FROM appointments a
                  JOIN doctors d ON a.doctor_id = d.id
                  JOIN patients p ON a.patient_id = p.id
                  LEFT JOIN prescriptions pr ON a.id = pr.appointment_id
                  WHERE a.patient_id = $id";

        queryToJSON($query);  // Call the function with the specified query
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else if (isset($_GET['doctorAppointments'])) {
    try {
        $id = $_SESSION['id'];

        $query = "SELECT CONCAT(d.first_name, ' ', d.last_name) AS doctor_name,
                         CONCAT(p.first_name, ' ', p.last_name) AS patient_namee,
                         a.*,
                         p.email
                  FROM appointments a
                  JOIN doctors d ON a.doctor_id = d.id
                  JOIN patients p ON a.patient_id = p.id
                  WHERE a.doctor_id = $id";

        queryToJSON($query);  // Call the function with the specified query
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else if (isset($_GET['allMedicines'])) {
    try {
        $query = "SELECT * FROM medicines;";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else if (isset($_POST['medicine_id'])) {
    try {
        $medicine_id = $_POST['medicine_id'];
        $query = "SELECT * FROM medicines where id = $medicine_id";
        queryToJSON($query);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
