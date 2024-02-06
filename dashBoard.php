<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: radial-gradient(circle at top left, #f5f5dc, #faf0e6); /* Beige background */
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center; /* Center the text horizontally */
        }

        .container {
            max-width: 600px;
            margin: 20px;
            padding: 20px;
            background-color: #fff; /* White container */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.9);
            border-radius: 10px;
        }

        h2 {
            color: #6b8e23; /* Olive green heading */
        }

        .button {
            display: block;
            padding: 10px 20px;
            margin: 10px;
            background-color: #6b8e23; /* Olive green button */
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none; /* Remove underline */
        }

        .button:hover {
            background-color: #556b2f; /* Darker shade on hover */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #dcdcdc; /* Light gray border */
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

        .dash {
            text-align: center;
            margin-bottom: 20px; /* Adjust margin as needed */
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="dash">Dashboard</h2>

    <!-- Buttons to Access Forms -->
    <div>
        <a href="patient.php" class="button">Manage Patients</a>
        <a href="sched.php" class="button">Manage Schedules</a>
        <a href="appointmentform.php" class="button">Manage Appointments</a>
    </div>

    <!-- Display Appointments Table -->
    <h2>Appointments</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient Name</th>
                <th>Schedule Date</th>
                <th>Schedule Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Include the database connection file
            include 'include/db_connection.php';

            // Fetch appointments
            $sqlAppointments = "SELECT appt_id, patient.patient_name AS patientName, schedule.sched_date, schedule.sched_time, appt_status FROM appointment
            INNER JOIN patient ON appointment.patient_id = patient.patient_id
            INNER JOIN schedule ON appointment.sched_id = schedule.sched_id";

            $resultAppointments = $db->query($sqlAppointments);

            if ($resultAppointments === FALSE) {
                die("Error executing the query: " . $db->error);
            }

            if ($resultAppointments->num_rows > 0) {
                while ($row = $resultAppointments->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['appt_id']}</td>";
                    echo "<td>{$row['patientName']}</td>";
                    echo "<td>{$row['sched_date']}</td>";
                    echo "<td>{$row['sched_time']}</td>";
                    echo "<td>{$row['appt_status']}</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No appointments found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
