<?php
include '../server.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['success'] = false;
        $response['message'] = 'Invalid email address';
    } else {
        $stmt = $conn->prepare("UPDATE clients SET username = ?, phone = ?, password = ? WHERE email = ?");
        $stmt->bind_param("ssss", $username, $phone, $password, $email);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Profile Update Successful!';
        } else {
            $response['success'] = false;
            $response['message'] = 'Fail Updating.';
        }

        $stmt->close();
    }
}

$conn->close();
echo json_encode($response);
?>
