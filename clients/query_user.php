<?php
include '../server.php'; 

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['success'] = false;
        $response['message'] = 'Invalid email address';
    } else {
        $stmt = $conn->prepare("SELECT * FROM clients WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $response['success'] = true;
            $response['message'] = 'Profile Found!';
            $response['data'] = $user;
        } else {
            $response['success'] = false;
            $response['message'] = 'Profile Not Found!';
        }

        $stmt->close();
    }
}

$conn->close();
echo json_encode($response);
?>
