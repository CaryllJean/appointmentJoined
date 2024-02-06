<?php
// Include the database connection file
include 'db_connection.php';

// Add new schedule
if (isset($_POST['add'])) {
    $scheduleTime = $_POST['sched_time'];
    $scheduleDate = $_POST['sched_date'];
    $status = 'vacant'; // Default status is vacant

    $stmt = $db->prepare("INSERT INTO schedule (sched_time, sched_date, sched_status) VALUES (?, ?, ?)");

    // Check for errors in the prepared statement
    if (!$stmt) {
        die('Error: ' . $db->error);
    }

    $stmt->bind_param("sss", $scheduleTime, $scheduleDate, $status);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the schedule form after adding
    header("Location: ../sched.php");
    exit();
}

// Edit schedule
if (isset($_POST['edit'])) {
    $schedule_id = $_POST['sched_id'];
    $scheduleTime = $_POST['sched_time'];
    $scheduleDate = $_POST['sched_date'];
    $status = $_POST['status']; // Corrected the variable name here

    $stmt = $db->prepare("UPDATE schedule SET sched_time=?, sched_date=?, sched_status=? WHERE sched_id=?");

    // Check for errors in the prepared statement
    if (!$stmt) {
        die('Error: ' . $db->error);
    }

    $stmt->bind_param("sssi", $scheduleTime, $scheduleDate, $status, $schedule_id); // Corrected the parameter order
    $stmt->execute();
    $stmt->close();

    // Redirect back to the schedule form after editing
    header("Location: ../sched.php");
    exit();
}


// Delete schedule
if (isset($_GET['delete'])) {
    $schedule_id = $_GET['delete'];

    // Check if the schedule_id is set, if not, delete all related appointments and then delete the schedule
    if (isset($schedule_id)) {
        // Delete related appointments
        $stmtDeleteAppointments = $db->prepare("DELETE FROM appointment WHERE sched_id=?");

        if ($stmtDeleteAppointments) {
            $stmtDeleteAppointments->bind_param("i", $schedule_id);
            $stmtDeleteAppointments->execute();
            $stmtDeleteAppointments->close();
            
            // Now, delete the schedule
            $stmtDeleteSchedule = $db->prepare("DELETE FROM schedule WHERE sched_id=?");

            if ($stmtDeleteSchedule) {
                $stmtDeleteSchedule->bind_param("i", $schedule_id);
                $stmtDeleteSchedule->execute();
                $stmtDeleteSchedule->close();
                echo "Schedule deleted successfully!";
            } else {
                // Handle the case where the prepared statement for deleting the schedule fails
                echo "Error preparing statement for deleting schedule: " . $db->error;
            }
        } else {
            // Handle the case where the prepared statement for deleting appointments fails
            echo "Error preparing statement for deleting appointments: " . $db->error;
        }
    } else {
        // If schedule_id is not set, delete all rows
        $result = $db->query("DELETE FROM schedule");

        if ($result) {
            echo "All schedules deleted successfully!";
        } else {
            // Handle the case where the query fails
            echo "Error deleting schedules: " . $db->error;
        }
    }
}

// Redirect back to the schedule form after processing requests
header("Location: ../sched.php");
exit();
$db->close();
?>
