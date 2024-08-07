<?php
include '../server.php';
$response = array();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postData = file_get_contents("php://input");
    $data = json_decode($postData, true);
    $id = $data['id'];

    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Message deleted!';
    } else {
        $response['success'] = false;
        $response['message'] = 'Failed to delete message!';
    }
    $stmt->close();
}
$conn->close();
echo json_encode($response);
?>
