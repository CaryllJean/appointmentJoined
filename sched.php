<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Schedule Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: radial-gradient(circle at top left, #f3e5ab, #ceb888);
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center; /* Center the text horizontally */
        }

        .container {
            max-width: 800px;
            margin: 20px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.9);
            border-radius: 10px;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .button {
            flex: 1;
            padding: 8px; /* Adjusted padding */
            background-color: #b8a28f; /* Beige button */
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none; /* Added to make it look like a button */
            text-align: center;
        }

        .button:hover {
            background-color: #8e7d6f; /* Darker shade on hover */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #b8a28f; /* Beige table header */
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2; /* Alternate row color */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Schedule Form</h2>

        <?php
        // Include the database connection file
        include 'include/db_connection.php';

        // Display the edit form if "Edit" button is clicked
        if (isset($_GET['edit'])) {
            // Reopen the database connection for edit form
            $db = new mysqli($servername, $username, $password, $database);

            $editId = $_GET['edit'];
            $editResult = $db->query("SELECT * FROM schedule WHERE sched_id=$editId");

            if ($editResult !== false && $editResult->num_rows == 1) {
                $editRow = $editResult->fetch_assoc();
                ?>
                <!-- Edit form -->
                <h2>Edit Schedule</h2>
                <form action="include/sched_process.php" method="post">
                    <input type="hidden" name="sched_id" value="<?php echo $editRow['sched_id']; ?>">
                    <label for="sched_time">Schedule Time:</label>
                    <input type="time" name="sched_time" value="<?php echo $editRow['sched_time']; ?>" required>
                    <label for="sched_date">Schedule Date:</label>
                    <input type="date" name="sched_date" value="<?php echo $editRow['sched_date']; ?>" required>
                    <label for="sched_status">Status:</label>
                    <select name="status" required>
                        <option value="Vacant" <?php echo ($editRow['sched_status'] == 'Vacant') ? 'selected' : ''; ?>>Vacant</option>
                        <option value="Occupied" <?php echo ($editRow['sched_status'] == 'Occupied') ? 'selected' : ''; ?>>Occupied</option>
                    </select>
                    <div class="button-container">
                        <button type="submit" name="edit" class="button">Update</button>
                        <a href="dashboard.php" class="button">Back</a>
                    </div>
                </form>
                <?php

                // Close the edit form database connection
                $db->close();
                exit(); // Exit to prevent further execution
            }

            // Close the edit form database connection
            $db->close();
        } else {
            // Display the add form if "Edit" button is not clicked
            ?>
            <!-- Add form -->
            <form action="include/sched_process.php" method="post">
                <label for="sched_time">Schedule Time:</label>
                <input type="time" name="sched_time" required>
                <label for="sched_date">Schedule Date:</label>
                <input type="date" name="sched_date" required>
                <div class="button-container">
                    <button type="submit" name="add" class="button">Add</button>
                    <a href="dashboard.php" class="button">Back</a>
                </div>
            </form>
            <?php
        }
        ?>

        <!-- Display added schedules -->
        <h2>Schedules</h2>

        <?php
        // Reopen the database connection for the schedule list
        $db = new mysqli($servername, $username, $password, $database);

        // Fetch and display schedule data from the database
        $result = $db->query("SELECT * FROM schedule");

        if ($result !== false && $result->num_rows > 0) {
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Schedule Time</th>';
            echo '<th>Schedule Date</th>';
            echo '<th>Status</th>';
            echo '<th>Action</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo "<td>{$row['sched_time']}</td>";
                echo "<td>{$row['sched_date']}</td>";
                echo "<td>{$row['sched_status']}</td>";
                echo '<td>';
                echo "<a href='sched.php?edit={$row['sched_id']}' class='button'>Edit</a>";
                echo " | ";
                echo "<a href='include/sched_process.php?delete={$row['sched_id']}' class='button'>Delete</a>";
                echo '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>No schedules found.</p>';
        }

        if ($result !== false) {
            $result->free(); // Free the result set
        }

        // Close the schedule list database connection
        $db->close();
        ?>
    </div>
</body>
</html>
