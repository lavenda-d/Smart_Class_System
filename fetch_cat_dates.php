<?php
include 'config.php';

$query = "SELECT cat.day, cat.time, v.full_name AS venue 
          FROM cat_details cat 
          JOIN venues v ON cat.venue = v.room";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$cat_dates = array();
while ($row = $result->fetch_assoc()) {
    $cat_dates[] = $row;
}

header('Content-Type: application/json');
echo json_encode($cat_dates);
?>