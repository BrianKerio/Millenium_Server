<?php
include '../server.php'; 

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_ends_with($email, '@gmail.com')) {
        $response['success'] = false;
        $response['message'] = 'Please use a valid email account';
    } else if (!preg_match('/^\+[0-9]{10,13}$/', $phone)) {
        $response['success'] = false;
        $response['message'] = 'Not a Valid Phone Number!!!';
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT * FROM clients WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $response['success'] = false;
            $response['message'] = 'Email is registered with another account in our database.';
        } else {
            // Check if phone number already exists
            $stmt = $conn->prepare("SELECT * FROM clients WHERE phone = ?");
            $stmt->bind_param("s", $phone);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $response['success'] = false;
                $response['message'] = 'Phone number is registered with another account in our database.';
            } else {
                // Insert the new user into the database
                $stmt = $conn->prepare("INSERT INTO clients (username, email, phone, password) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $email, $phone, $password);

                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'Registration successful';
                } else {
                    $response['success'] = false;
                    $response['message'] = 'Registration failed';
                }
            }
        }

        $stmt->close();
    }
}

$conn->close();
echo json_encode($response);
?>
