<?php
include 'config.php';

session_start();
$student_id = $_SESSION['student_id']; // Assuming you store student_id in session

$query = "SELECT c.day, c.time, v.full_name AS venue 
          FROM classes c 
          JOIN venues v ON c.venue = v.room 
          JOIN attendance a ON c.class_id = a.class_id 
          WHERE a.student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$schedule = array();
while ($row = $result->fetch_assoc()) {
    $schedule[] = $row;
}

header('Content-Type: application/json');
echo json_encode($schedule);
?>