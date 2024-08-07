<?php
include '../server.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $image = $_FILES['image']['name'];

    $target_dir = "staffs/";
    $target_file = $target_dir . basename($image);

    
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    chmod($target_dir, 0755);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $sql = "INSERT INTO staff (name, role, email, phone, password, image) VALUES ('$name', '$role', '$email', '$phone', '$password', '$image')";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Image upload failed']);
    }
}
?>
