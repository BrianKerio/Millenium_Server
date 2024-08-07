<?php
include '../server.php'; 

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
   
    $stmt = $conn->prepare("SELECT * FROM staff WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $expert = $result->fetch_assoc();

        if ($password === $expert['password']) {
            $response['success'] = true;
            $response['message'] = 'Expert Logged Successful!';

            $response['expert'] = array(
                'id' => $expert['id'],
                'name' => $expert['name'],
                'role' => $expert['role'],
                'email' => $expert['email'],
                'phone' => $expert['phone'],
                'image' => $expert['image']
            );
        } else {
            $response['success'] = false;
            $response['message'] = 'Incorrect Password!';
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Email Not Found Contact Admin!';
    }

    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid Request!';
}

$conn->close();
echo json_encode($response);
?>