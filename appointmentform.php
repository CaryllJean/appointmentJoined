<?php
include_once 'templates/header.php';
include 'include/db_connection.php';

// Fetch patients for dropdown
$sqlPatients = "SELECT patient_id, patient_name FROM patient";
$resultPatients = $db->query($sqlPatients);
$patients = $resultPatients->fetch_all(MYSQLI_ASSOC);

// Fetch schedules for dropdown
$sqlSchedule = "SELECT sched_id, sched_time, sched_date FROM schedule";
$resultSchedule = $db->query($sqlSchedule);
$schedules = $resultSchedule->fetch_all(MYSQLI_ASSOC);

// Fetch appointments
$sqlAppointments = "SELECT appt_id, patient_id, sched_id, appt_status FROM appointment";
$resultAppointments = $db->query($sqlAppointments);

// Check for errors
if ($resultAppointments === FALSE) {
    die("Error executing the query: " . $db->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Form</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5dc; /* Beige background */
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 1000px;
        margin: 20px auto;
        padding: 20px;
        background-color: #faf0e6; /* Light beige container */
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        margin-bottom: 20px;
        color: pear; /* Pear heading */
        text-align: center;
    }

    label {
        display: block;
        margin-bottom: 8px;
        color: #6b8e23; /* Olive green label */
    }

    select {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #dcdcdc; /* Light gray border */
        border-radius: 5px;
        background-color: #fff; /* White background */
        font-size: 16px;
    }

    .buttons {
        text-align: center;
        padding: 20px 20px;
    }

    button {
        display: block;
        padding: 10px 20px;
        background-color: #6b8e23; /* Olive green button */
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        text-decoration: none;
    }

    button:hover {
        background-color: #556b2f; /* Darker shade on hover */
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        border: 1px solid #dcdcdc;
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #6b8e23; /* Olive green header */
        color: #fff;
    }

    tr:nth-child(even) {
        background-color: #fff; /* White background for even rows */
    }

    tr:nth-child(odd) {
        background-color: #f5f5dc; /* Light beige background for odd rows */
    }

    tr:hover {
        background-color: #ffffe0; /* Light yellow on hover */
    }

    .edit, .delete {
        padding: 8px 12px;
        border-radius: 3px;
        text-decoration: none;
        color: #fff;
    }

    .edit {
        background-color: #6b8e23; /* Olive green edit button */
    }

    .edit:hover {
        background-color: #556b2f; /* Darker shade on hover */
    }

    .delete {
        background-color: #8b0000; /* Dark red delete button */
    }

    .delete:hover {
        background-color: #b22222; /* Darker shade on hover */
    }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="text-shadow: 4px 4px 8px #a67b5b; ">APPOINTMENT FORM</h2>
        <form action="include/appt_process.php" method="post" style="max-width: 400px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; border-radius: 10px; border: 1px solid #ddd;">
            <input type="hidden" name="id" id="id">

            <label for="patient_id">Patient Name:</label>
            <select name="patient_id" id="patient_id" required>
                <?php foreach ($patients as $patient): ?>
                    <option value="<?php echo $patient['patient_id']; ?>"><?php echo $patient['patient_name']; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="sched_id">Schedule Time:</label>
            <select name="sched_id" id="sched_id" required>
                <?php foreach ($schedules as $schedule): ?>
                    <option value="<?php echo $schedule['sched_id']; ?>"><?php echo $schedule['sched_time']; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="sched_date">Schedule Date:</label>
            <select name="sched_date" id="sched_date" required>
                <?php foreach ($schedules as $schedule): ?>
                    <option value="<?php echo $schedule['sched_id']; ?>"><?php echo $schedule['sched_date']; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="appt_status">Status:</label>
            <select name="appt_status" id="appt_status" required>
                <option value="Pending">Pending</option>
                <option value="Confirmed">Confirmed</option>
                <option value="Cancelled">Cancelled</option>
            </select>

            <!-- Submit and Reset Buttons -->
            <div>
                <button type="submit" name="add">Add Appointment</button>
            </div>
            <div class="buttons">
                <a href="dashBoard.php" style=" background-color: #4CAF50; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none;"> Back </a>
            </div>
        </form>
    </div>

    <!-- Display Appointments Table -->
    <div class="container">
        <h2>Appointments</h2>
        <table>
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Schedule Date</th>
                    <th>Schedule Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultAppointments->num_rows > 0): ?>
                    <?php while ($row = $resultAppointments->fetch_assoc()): ?>
                        <?php
                        // Fetch additional details based on foreign keys (e.g., patient name, schedule details)
                        $patientId = $row['patient_id'];
                        $scheduleId = $row['sched_id'];

                        // Fetch patient name
                        $patientQuery = "SELECT patient_name FROM patient WHERE patient_id = $patientId";
                        $resultPatient = $db->query($patientQuery);
                        $patient = $resultPatient->fetch_assoc();

                        // Fetch schedule details
                        $scheduleQuery = "SELECT sched_time, sched_date FROM schedule WHERE sched_id = $scheduleId";
                        $resultSchedule = $db->query($scheduleQuery);
                        $schedule = $resultSchedule->fetch_assoc();
                        ?>
                        <tr>
                            <td><?php echo $patient['patient_name']; ?></td>
                            <td><?php echo $schedule['sched_date']; ?></td>
                            <td><?php echo $schedule['sched_time']; ?></td>
                            <td><?php echo $row['appt_status']; ?></td>
                            <td>
                                <form action='include/appt_process.php' method='post'>
                                    <a href='editForm.php?id=<?php echo $row['appt_id']; ?>' class='edit'>Edit</a> |
                                    <a href='include/appt_process.php?delete=<?php echo $row['appt_id']; ?>' class='button delete'>Delete</a>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan='5'>No appointments found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
