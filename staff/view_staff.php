<?php
include '../server.php';

$sql = "SELECT * FROM staff";
$result = $conn->query($sql);

$staffs = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $staffs[] = $row;
    }
}

echo json_encode($staffs);
?>
