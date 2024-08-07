<?php
include '../server.php';

$response = array();

$sql = "SELECT * FROM tickets ORDER BY date";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['images'] = json_decode($row['images']);
        $response[] = $row;
    }
    echo json_encode($response);
} else {
    echo json_encode([]);
}

$conn->close();
?>
