<?php
include '../server.php'; 

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Update the user's existing password
    $stmt = $conn->prepare("UPDATE clients SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $password, $email);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Password reset successful';
    } else {
        $response['success'] = false;
        $response['message'] = 'Password reset failed';
    }

    $stmt->close();
}

$conn->close();
echo json_encode($response);
?>
