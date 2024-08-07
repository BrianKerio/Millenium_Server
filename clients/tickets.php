<?php
include '../server.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $company_name = filter_var($_POST['company_name'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $date = $_POST['date'];

    if ($email === false) {
        $response['success'] = false;
        $response['message'] = 'Invalid email format';
        echo json_encode($response);
        exit;
    }

    if (isset($_FILES['images'])) {
        $files = $_FILES['images'];
        $uploadedFiles = array();

        $allowedExtensions = array('jpg', 'jpeg', 'png', 'webp');
        $uploadFileDir = 'uploads/';

        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0755, true);
        }

        for ($i = 0; $i < count($files['name']); $i++) {
            $fileTmpPath = $files['tmp_name'][$i];
            $fileName = $files['name'][$i];
            $fileSize = $files['size'][$i];
            $fileType = $files['type'][$i];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            if (in_array($fileExtension, $allowedExtensions)) {
                $newFileName = uniqid() . '.' . $fileExtension;
                $destPath = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $uploadedFiles[] = $newFileName;
                } else {
                    $response['success'] = false;
                    $response['message'] = 'Failed to move uploaded file!';
                    echo json_encode($response);
                    exit;
                }
            } else {
                $response['success'] = false;
                $response['message'] = 'Image format not allowed!';
                echo json_encode($response);
                exit;
            }
        }

        $images = json_encode($uploadedFiles);
        $stmt = $conn->prepare("INSERT INTO tickets (name, email, company_name, description, date, images) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $company_name, $description, $date, $images);

        if ($stmt->execute()) {
            $serverAddress = '192.168.43.148/millenium/clients';
            $to = 'briankerio47@gmail.com';
            $subject = 'New Ticket raised';
            $message = "Client Name: $name\nEmail: $email\nCompany Name: $company_name\nDescription: $description\nDate Raised: $date\nUploaded Image: " . implode(", ", array_map(function ($file) use ($serverAddress) {
                return "$serverAddress/uploads/$file";
            }, $uploadedFiles));
            $headers = 'From: noreply@client';

            if (mail($to, $subject, $message, $headers)) {
                $response['success'] = true;
                $response['message'] = 'Ticket Raised Successful and email notification sent to Technical!';
            } else {
                $response['success'] = true;
                $response['message'] = 'Ticket successfully raised but failed to send email notification!';
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to raise the ticket!';
        }

        $stmt->close();
    } else {
        $response['success'] = false;
        $response['message'] = 'No images uploaded.';
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request method.';
}

$conn->close();
echo json_encode($response);
?>
