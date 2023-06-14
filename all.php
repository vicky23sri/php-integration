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

// Check if query is provided as a GET parameter
if (isset($_GET['query'])) {
    // Sanitize and validate the query
    $query = $_GET['query'];
    $query = $conn->real_escape_string($query); // Escape special characters

    // Prepare the query with a WHERE clause for filtering based on the provided query
    $query = "SELECT id, name, phone, email, address, year FROM students WHERE name LIKE '%$query%'";
    $result = $conn->query($query);

    // Check if rows are found
    if ($result->num_rows > 0) {
        $users = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($users);
    } else {
        echo json_encode([]); // Return an empty array if no results are found
    }

    // Close the database connection
    $conn->close();
    exit(); // Exit the script after sending the response
}

// If the code reaches here, it means no query parameter was provided
// Return an empty array
echo json_encode([]);

// Close the database connection
$conn->close();
?>
