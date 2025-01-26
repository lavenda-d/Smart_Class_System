<?php
include 'config.php'; // Include the database connection

// Get the posted data
$username = $_POST['username'];
$password = $_POST['password'];
$userType = $_POST['userType'];

// Sanitize inputs to prevent SQL injection
$username = $conn->real_escape_string($username);
$password = $conn->real_escape_string($password);

// Initialize response
$response = [];

if ($userType === 'student') {
    // Check for student
    $sql = "SELECT * FROM students WHERE username = '$username' AND password = '$password'";
} elseif ($userType === 'admin') {
    // Check for admin
    $sql = "SELECT * FROM admins WHERE username = '$username' AND password = '$password'";
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid user type.';
    echo json_encode($response);
    exit;
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // If login is successful
    $response['status'] = 'success';
    if ($userType === 'student') {
        $response['redirect'] = 'student.html';
    } else {
        $response['redirect'] = 'admin.html';
    }
} else {
    // If login fails
    $response['status'] = 'error';
    $response['message'] = 'Invalid username or password.';
}

// Return the response as JSON
echo json_encode($response);

// Close the connection
$conn->close();
?>