<?php
include 'db_connection.php';

// Add new appointment
if (isset($_POST['add'])) {
    // Retrieve data from the form
    $patientId = $_POST['patient_id'];
    $scheduleId = $_POST['sched_id'];
    $status = $_POST['appt_status'];

    // Using prepared statement to prevent SQL injection
    $stmt = $db->prepare("INSERT INTO appointment (patient_id, sched_id, appt_status) VALUES (?, ?, ?)");
    
    // Bind parameters to the prepared statement
    $stmt->bind_param("iis", $patientId, $scheduleId, $status);

    // Execute the statement
    if ($stmt->execute()) {       
        echo "Appointment added successfully!";
    } else {
        echo "Error adding appointment: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();

    // Redirect to the appointmentform.php page
    header("Location: ../appointmentform.php");
    exit();
}

// Handle appointment edits
if (isset($_POST['edit'])) {
    $editAppointmentId = $_POST['edit_appt_id'];
    $editPatientId = $_POST['edit_patient_id'];
    $editScheduleId = $_POST['edit_sched_id'];
    $editStatus = $_POST['edit_appt_status'];

    $editQuery = "UPDATE appointment
                  SET patient_id = '$editPatientId', 
                      sched_id = '$editScheduleId', 
                      appt_status = '$editStatus' 
                  WHERE appt_id = '$editAppointmentId'";

    $resultEdit = $db->query($editQuery);

    if ($resultEdit === TRUE) {
        // Redirect to appointmentform.php on successful update
        header("Location: ../appointmentform.php");
        exit(); // Ensure that subsequent code is not executed
    } else {
        echo "Error updating appointment: " . $db->error;
    }
}



// Handle appointment deletion
if (isset($_GET['delete'])) {
    $deleteAppointmentId = $_GET['delete'];

    $deleteQuery = "DELETE FROM appointment WHERE appt_id = '$deleteAppointmentId'";
    $resultDelete = $db->query($deleteQuery);

    if ($resultDelete === TRUE) {
        echo "Appointment deleted successfully!";
    } else {
        echo "Error deleting appointment: " . $db->error;
    }

    // Redirect to the appointmentform.php page after deleting
    header("Location: ../appointmentform.php");
    exit();
}
?>
