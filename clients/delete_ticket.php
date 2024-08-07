<?php
include '../server.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);

    $selectSql = "SELECT images FROM tickets WHERE id = ?";
    $selectStmt = $conn->prepare($selectSql);
    $selectStmt->bind_param("i", $id);

    if ($selectStmt->execute()) {
        $selectStmt->bind_result($images);
        if ($selectStmt->fetch()) {
            $imagesArray = json_decode($images, true);

            
            foreach ($imagesArray as $image) {
                $filePath = "uploads/" . $image;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $selectStmt->close();

        
            $sql = "DELETE FROM tickets WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Ticket deleted!';
            } else {
                $response['success'] = false;
                $response['message'] = 'Failed to delete Ticket';
            }

            $stmt->close();
        } else {
            $response['success'] = false;
            $response['message'] = 'Ticket not found';
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Failed to retrieve Ticket';
    }

    $selectStmt->close();
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request method';
}

$conn->close();
echo json_encode($response);
?>
