<?php
// Include the database connection configurations
require 'config.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Fetch attendance details with student information
$sql = "SELECT students.name, students.registration_number, students.telephone 
        FROM attendance 
        INNER JOIN students ON attendance.student_id = students.student_id
        WHERE attendance.status = 'Present'";

$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Close connection
$conn->close();

// Return data as JSON
echo json_encode($data);
?>
