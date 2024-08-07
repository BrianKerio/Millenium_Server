<?php
include '../server.php';

$email = $_POST['email'] ?? '';

if (!empty($email)) {
    $sql = "SELECT * FROM staff WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $staff = $result->fetch_assoc();
        echo json_encode($staff);
    } else {
        echo json_encode(['message' => 'Profile Not Found']);
    }

    $stmt->close();
} else {
    echo json_encode(['message' => 'Email is required']);
}

$conn->close();
?>
