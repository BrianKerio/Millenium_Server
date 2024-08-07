<?php
include '../server.php'; 

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM clients WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response['success'] = true;
    } else {
        $response['success'] = false;
        $response['message'] = 'Email Not Found!';
    }

    $stmt->close();
}

$conn->close();
echo json_encode($response);
?>