<?php
include '../server.php';

$response = array();
$query = "SELECT id, name, email, message, rating FROM messages";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
} else {
    $response['success'] = false;
    $response['message'] = 'No messages found';
}

$conn->close();
echo json_encode($response);
?>
