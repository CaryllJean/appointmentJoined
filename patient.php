<?php
// Include the database connection file
include 'include/db_connection.php';

// Initialize variables for the edit form
$editMode = false;
$editId = '';
$editName = '';
$editEmail = '';

// Check if edit button is clicked
if (isset($_GET['edit'])) {
    $editId = $_GET['edit'];
    $editMode = true;

    // Fetch data of the selected patient for editing
    $result = $db->query("SELECT * FROM patient WHERE patient_id = $editId");

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $editName = $row['patient_name'];
        $editEmail = $row['patient_email'];
    }
}

// Process form submission for adding new patient
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $stmt = $db->prepare("INSERT INTO patient (patient_name, patient_email) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $email);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the patient form
    header("Location: patient.php");
    exit();
}

// Process form submission for editing existing patient
if (isset($_POST['edit'])) {
    $id = $_POST['patient_id']; // Change 'id' to 'patient_id'
    $name = $_POST['name'];
    $email = $_POST['email'];

    $stmt = $db->prepare("UPDATE patient SET patient_name=?, patient_email=? WHERE patient_id=?");
    $stmt->bind_param("ssi", $name, $email, $id);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the patient form
    header("Location: patient.php");
    exit();
}

// Fetch and display patient data from the database
$result = $db->query("SELECT * FROM patient");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Form</title>
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
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #6b8e23; /* Olive green label */
        }

        input[type="text"],
        input[type="email"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #dcdcdc; /* Light gray border */
            border-radius: 5px;
            background-color: #fff; /* White background */
            font-size: 16px;
        }

        .button, button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #6b8e23; /* Olive green button */
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none; /* Added to make it look like a button */
            width: 100px; /* Fixed width for both buttons */
            text-align: center; /* Center the text horizontally */
            margin-right: 10px; /* Add margin to separate the buttons */
        }

        .button:hover {
            background-color: #556b2f; /* Darker shade on hover */
        }
        button:hover {
            background-color: #556b2f;
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

        .button {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 3px;
            text-decoration: none;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
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
    <?php if (!$editMode): ?>
        <h2>Patient Form</h2>
        <!-- Form to add new patient -->
        <form action="patient.php" method="post">
            <label for="name">Name:</label>
            <input type="text" name="name" required>
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            <!-- Add button and Back link in the same form -->
            <button type="submit" name="add">Add</button>
            <a href="dashboard.php" class="button">Back</a>
        </form>
    <?php endif; ?>

    <?php if ($editMode): ?>
        <!-- Form to edit existing patient -->
        <h2>Edit Patient</h2>
        <form action="patient.php" method="post">
            <input type="hidden" name="patient_id" value="<?php echo $editId; ?>"> <!-- Change 'id' to 'patient_id' -->
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo $editName; ?>" required>
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo $editEmail; ?>" required>
            <button type="submit" name="edit">Edit</button>
        </form>
    <?php endif; ?>

    <!-- Display added patients -->
    <h2>Patients</h2>
    <?php
    if ($result->num_rows > 0) {
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Name</th>';
        echo '<th>Email</th>';
        echo '<th>Action</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo "<td>{$row['patient_name']}</td>";
            echo "<td>{$row['patient_email']}</td>";
            echo "<td><a href='patient.php?edit={$row['patient_id']}' class='button edit'>Edit</a> | <a href='include/patient_process.php?delete={$row['patient_id']}' class='button delete'>Delete</a>";
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>No patients found.</p>';
    }

    $result->free(); // Free the result set
    ?>
</div>

</body>
</html>
