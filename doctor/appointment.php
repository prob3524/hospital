<?php
session_start();
require_once "../connection.php";

// Check cookies exist or not
if (!isset($_SESSION['user'])) {
    $_SESSION['success'] = false;
    $_SESSION['message'] = "Authentication failed";
     header("location: $appUrl/login.php");
    exit;
} else if (isset($_SESSION["role"]) && $_SESSION["role"] != "doctors") {
    setcookie('user', '', time() - 3600, '/');
    $_SESSION['success'] = false;
    $_SESSION['message'] = "You are not authorized to access the Doctor site.";
     header("location: $appUrl/login.php");
    exit;
}

$pageTitle = "Appointment Setting";
require_once "../components/header.php";

// Handle the POST request and insert data into the database
$daysOfWeek = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // $connection = new mysqli("your_host", "your_username", "your_password", "your_database");

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Create a new table for storing schedule information if not exists
    $createTableSQL = "CREATE TABLE IF NOT EXISTS DoctorSchedule (
                        ID INT AUTO_INCREMENT PRIMARY KEY,
                        DoctorID INT,
                        DayOfWeek INT,
                        StartTime TIME,
                        EndTime TIME,
                        ScheduleGap INT,
                        MeetingTiming INT,
                        IsWeekAvailability INT
                    )";
    $connection->query($createTableSQL);

    // Insert schedule information into the new table

    foreach ($daysOfWeek as $day) {
        if (isset($_POST[strtolower($day) . 'Checkbox']) && $_POST[strtolower($day) . 'Checkbox'] == "on") {
            $doctorID = $_SESSION['user']['id'];
            $dayOfWeek = date('N', strtotime($day)); // Get day of the week (1 for Monday, 7 for Sunday)
            $startTime = date('H:i:s', strtotime($_POST[strtolower($day) . 'StartTime']));
            $endTime = date('H:i:s', strtotime($_POST[strtolower($day) . 'EndTime']));
            $scheduleGap = $_POST['scheduleGap'];
            $meetingTiming = $_POST['meetingTiming'];
            $weekAvailability = isset($_POST['weekAvailability']) ? 1 : 0;

            $insertSQL = "INSERT INTO DoctorSchedule (DoctorID, DayOfWeek, StartTime, EndTime, ScheduleGap, MeetingTiming, IsWeekAvailability)
                          VALUES ('$doctorID', '$dayOfWeek', '$startTime', '$endTime', '$scheduleGap', '$meetingTiming', '$weekAvailability')";

            if ($connection->query($insertSQL) === TRUE) {
                $_SESSION['message'] = 'Schedule added successfully!';
                $_SESSION['success'] = true;
            } else {
                $_SESSION['message'] = 'Error adding schedule. Please try again.';
                $_SESSION['success'] = false;
            }
        }
    }

    $connection->close();
    header("Location: " . $_SERVER['PHP_SELF']); // Redirect to clear POST data
    exit();
}

?>
