<?php
// Include the database connection file
include 'db_connection.php';

// Add new patient
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $stmt = $db->prepare("INSERT INTO patient (patient_name, patient_email) VALUES (?, ?)");

    if ($stmt) {
        $stmt->bind_param("ss", $name, $email);
        $stmt->execute();
        $stmt->close();
        echo "Patient added successfully!";
    } else {
        // Handle the case where the prepared statement is not created successfully
        echo "Error preparing statement: " . $db->error;
    }
}

// Edit patient
if (isset($_POST['edit'])) {
    $patient_id = $_POST['patient_id'];
    $name = $_POST['patient_name'];
    $email = $_POST['patient_email'];

    $stmt = $db->prepare("UPDATE patient SET patient_name=?, patient_email=? WHERE patient_id=?");

    if ($stmt) {
        $stmt->bind_param("ssi", $name, $email, $patient_id);
        $stmt->execute();
        $stmt->close();
        echo "Patient edited successfully!";
    } else {
        // Handle the case where the prepared statement is not created successfully
        echo "Error preparing statement: " . $db->error;
    }
}

// Delete patient
if (isset($_GET['delete'])) {
    $patient_id = $_GET['delete'];

    // Use a prepared statement for deletion to avoid SQL injection
    $stmt = $db->prepare("DELETE FROM patient WHERE patient_id=?");

    if ($stmt) {
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $stmt->close();
        echo "Patient deleted successfully!";
    } else {
        // Handle the case where the prepared statement is not created successfully
        echo "Error preparing statement: " . $db->error;
    }
}

// Redirect back to the patient form after processing requests
header("Location: ../patient.php");
exit();
?>
