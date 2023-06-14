<?php
// Allow requests from any origin
header('Access-Control-Allow-Origin: *');

// Allow specific HTTP methods
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

// Allow specific HTTP headers
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Requested-With, Accept');

// Set response content type as JSON
header('Content-Type: application/json');


// Connect to the database
$conn = new mysqli('localhost', 'Vignesh', 'Vignesh@98', 'Registration');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is provided as a query parameter
if (isset($_GET['id'])) {
    // Sanitize and validate the ID
    $id = $_GET['id'];
    if (!is_numeric($id)) {
        die("Invalid ID format");
    }

    // Prepare the query with a WHERE clause for the specified ID
    $query = "SELECT id, name, phone, email, address, year FROM students WHERE id = $id";
    $result = $conn->query($query);

    // Check if a row is found
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode($user);
    } else {
        echo "No data found for the specified ID";
    }
} else {
    // Query all students if no ID is provided
    $query = "SELECT id, name, phone, email, address, year FROM students";
    $result = $conn->query($query);

    $users = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($users);
}

// Close the database connection
$conn->close();
?>