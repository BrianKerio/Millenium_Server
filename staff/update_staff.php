<?php
include '../server.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $name = $_POST['name'];
    $role = $_POST['role'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $image = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : null;

    $target_dir = "staffs/";
    $target_file = $target_dir . basename($image);

    if ($image) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $imageSql = ", image='$image'";
        } else {
            echo json_encode(['success' => false, 'message' => 'Image upload failed']);
            exit;
        }
    } else {
        $imageSql = "";
    }

    $sql = "UPDATE staff SET name='$name', role='$role', phone='$phone', password='$password'$imageSql WHERE email='$email'";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
}
?>
