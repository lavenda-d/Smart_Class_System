<?php
include 'config.php';

$query = "SELECT e.day, e.time, v.full_name AS venue 
          FROM exam_details e 
          JOIN venues v ON e.venue = v.room";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$exam_dates = array();
while ($row = $result->fetch_assoc()) {
    $exam_dates[] = $row;
}

header('Content-Type: application/json');
echo json_encode($exam_dates);
?>