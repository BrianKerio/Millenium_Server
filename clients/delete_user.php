<?php
include '../server.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['success'] = false;
        $response['message'] = 'Invalid email address';
    } else {
        $stmt = $conn->prepare("DELETE FROM clients WHERE email = ?");
        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'User deleted successfully';
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to delete user';
        }

        $stmt->close();
    }
}

$conn->close();
echo json_encode($response);
?>
