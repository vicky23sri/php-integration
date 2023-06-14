<!DOCTYPE html>
<html>

<head>
    <title>Student Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        h2 {
            color: #333;
            margin-top: 0;
            text-align: center;
            font-weight: 1000;
            margin-bottom: 20px;
            font-size: 2rem;
            font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th{
            
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ccc;
            text-align: center;
            font-weight: 700;
            font-size: 1.4rem;
            margin-bottom: 20px;
        }

        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }

        .container {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
        }

        .popup {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .close {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            background-color: #000;
            color:#fff;
            height: 30px;
            width: 28px;
            font-size: 25px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        textarea,
        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .open-registration-btn {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 10px 20px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 9999;
        }

        .registered-students {
            margin-top: 100px;
        }

        .registered-students h2 {
            margin-bottom: 10px;
        }

        .registered-students table {
            width: 100%;
            border-collapse: collapse;
        }

        .registered-students th,
        .registered-students td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }

        .registered-students th {
            background-color: #f2f2f2;
        }

        .registered-students td:last-child {
            text-align: center;
        }

        .registered-students a {
            text-decoration: none;
            color: blue;
            text-align: left;
        }
    </style>
</head>

<body>
    <?php
    // Connect to the database
    $conn = new mysqli('localhost', 'Vignesh', 'Vignesh@98', 'Registration');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Display the button
    echo '<button class="open-registration-btn" onclick="openRegistrationForm()">Open Registration Form</button>';

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $year = $_POST['year'];

        if ($id == "") {
            // Insert new student into the database
            $insertQuery = "INSERT INTO students (name, email, phone, address, year) VALUES ('$name', '$email', '$phone', '$address', '$year')";
            if ($conn->query($insertQuery) === TRUE) {
                echo '<script>alert("Student registered successfully.")</script>';
            } else {
                echo '<script>alert("Error registering student: ' . $conn->error . '")</script>';
            }
        } else {
            // Update student details in the database
            $updateQuery = "UPDATE students SET name='$name', email='$email', phone='$phone', address='$address', year='$year' WHERE id='$id'";
            if ($conn->query($updateQuery) === TRUE) {
                echo '<script>alert("Student with ID: ' . $id . ' details updated successfully.")</script>';
            } else {
                echo '<script>alert("Error updating student details: ' . $conn->error . '")</script>';
            }
        }
    }

    // Delete student from the database if delete button is clicked
    if (isset($_GET['delete_id'])) {
        $deleteId = $_GET['delete_id'];

        // Delete the student from the database
        $deleteQuery = "DELETE FROM students WHERE id='$deleteId'";
        if ($conn->query($deleteQuery) === TRUE) {
            echo '<script>alert("Student with ID: ' . $deleteId . ' deleted successfully.")</script>';
        } else {
            echo '<script>alert("Error deleting student: ' . $conn->error . '")</script>';
        }
    }


    // Fetch the registered students' id, names, phone numbers, email, address, and year from the database
    $query = "SELECT id, name, phone, email, address, year FROM students";
    $result = $conn->query($query);

    echo '<div class="registered-students">';
    if ($result->num_rows > 0) {
        echo "<h2>Registered Students</h2>";
        echo "<table>";
        echo "<tr><th>Id</th><th>Name</th><th>Phone</th><th>Email</th><th>Address</th><th>Year</th><th>Action</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['phone'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['address'] . "</td>";
            echo "<td>" . $row['year'] . "</td>";
            echo '<td><a href="#" onclick="updateStudent(' . $row['id'] . ', \'' . $row['name'] . '\', \'' . $row['phone'] . '\', \'' . $row['email'] . '\', \'' . $row['address'] . '\', ' . $row['year'] . ')">Edit</a> | <a href="?delete_id=' . $row['id'] . '">Delete</a></td>';
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<h2>No students registered yet.</h2>";
    }
    echo '</div>';

    // Close the database connection
    $conn->close();
    ?>

    <div class="container" id="registrationForm">
        <div class="popup">
            <span class="close" onclick="closeRegistrationForm()">&times;</span>
            <h2 id="formTitle">Student Registration Form</h2>
            <form method="POST" action="./index.php">
                <input type="hidden" name="id" id="studentId" value="">

                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>

                <label for="phone">Phone:</label>
                <input type="tel" name="phone" id="phone" required>

                <label for="address">Address:</label>
                <textarea name="address" id="address" required></textarea>

                <label for="year">Year of Study:</label>
                <input type="number" name="year" id="year" required><br />

                <input type="submit" name="submit" value="Submit">
            </form>
        </div>
    </div>

    <script>
        function openRegistrationForm() {
            var container = document.getElementById("registrationForm");
            container.style.display = "flex";
        }

        function closeRegistrationForm() {
            var container = document.getElementById("registrationForm");
            container.style.display = "none";
        }

        function updateStudent(id, name, phone, email, address, year) {
            var form = document.querySelector("#registrationForm form");
            var studentIdInput = document.getElementById("studentId");
            var nameInput = document.getElementById("name");
            var phoneInput = document.getElementById("phone");
            var emailInput = document.getElementById("email");
            var addressInput = document.getElementById("address");
            var yearInput = document.getElementById("year");
            var formTitle = document.getElementById("formTitle");

            studentIdInput.value = id;
            nameInput.value = name;
            phoneInput.value = phone;
            emailInput.value = email;
            addressInput.value = address;
            yearInput.value = year;
            form.action = "?id=" + id;
            formTitle.textContent = "Update Student (ID: " + id + ")";

            openRegistrationForm();
        }
    </script>
</body>

</html>
