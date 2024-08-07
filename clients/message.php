<?php
include '../server.php';
$response = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the raw POST data
    $postData = file_get_contents("php://input");
    $data = json_decode($postData, true);
    $name = $data['name'];
    $phone = $data['phone'];
    $email = $data['email'];
    $message = $data['message'];
    $rating = $data['rating']; 



    $stmt = $conn->prepare("INSERT INTO messages (name, phone, email, message, rating) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $phone, $email, $message, $rating);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Message Successfully Sent To Admin!';
    } else {
        $response['success'] = false;
        $response['message'] = 'Message Not Sent!';
    }
    $stmt->close();
}
$conn->close();
echo json_encode($response);
?>