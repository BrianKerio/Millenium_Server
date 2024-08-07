<?php
include '../server.php'; 

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
   
        if ($password === $admin['password']) {
            $response['success'] = true;
            $response['message'] = 'Admin login Successful!';
          
            $response['admin'] = array(
                'id' => $admin['id'],
                'username' => $admin['username'],
                'email' => $admin['email']
            );
        } else {
            $response['success'] = false;
            $response['message'] = 'Incorrect Password!';
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Email Is Not Registered!';
    }

    $stmt->close();
}

$conn->close();
echo json_encode($response);
?>
