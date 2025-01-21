<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and retrieve input data
    $firstName = htmlspecialchars($_POST['first_name']);
    $lastName = htmlspecialchars($_POST['last_name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars($_POST['phone']);
    $color = htmlspecialchars($_POST['color']);
    $odometer = htmlspecialchars($_POST['odometer']);
    $year = htmlspecialchars($_POST['year']);
    $make = htmlspecialchars($_POST['make']);
    $model = htmlspecialchars($_POST['model']);
    $trim = htmlspecialchars($_POST['trim']);
    $cylinders = htmlspecialchars($_POST['cylinders']);
    $transmission = htmlspecialchars($_POST['transmission']);
    $vin = htmlspecialchars($_POST['vin']);
    $damages = htmlspecialchars($_POST['damages']);
    $source = htmlspecialchars($_POST['source']);

    // File upload handling
    $uploadDir = 'uploads/';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    $uploadedFiles = [];
    $errors = [];

    // Ensure upload directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    foreach ($_FILES as $fileKey => $file) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            if (in_array($file['type'], $allowedTypes)) {
                $uniqueFilename = $uploadDir . uniqid() . '-' . basename($file['name']);
                if (move_uploaded_file($file['tmp_name'], $uniqueFilename)) {
                    $uploadedFiles[$fileKey] = $uniqueFilename;
                } else {
                    $errors[] = "Error uploading file: " . $fileKey;
                }
            } else {
                $errors[] = "Invalid file type for: " . $fileKey;
            }
        } elseif ($file['error'] !== UPLOAD_ERR_NO_FILE) {
            $errors[] = "File upload error code " . $file['error'] . " for: " . $fileKey;
        }
    }

    // Format the email body with all form data
    $message = "
    <html>
    <head>
        <title>New Trade or Sell Your Vehicle Submission</title>
    </head>
    <body>
        <h2>Submission Details</h2>
        <table border='1' cellpadding='5' cellspacing='0'>
            <tr><th>Field</th><th>Value</th></tr>
            <tr><td>First Name</td><td>{$firstName}</td></tr>
            <tr><td>Last Name</td><td>{$lastName}</td></tr>
            <tr><td>Email</td><td>{$email}</td></tr>
            <tr><td>Phone</td><td>{$phone}</td></tr>
            <tr><td>Color</td><td>{$color}</td></tr>
            <tr><td>Odometer</td><td>{$odometer}</td></tr>
            <tr><td>Year</td><td>{$year}</td></tr>
            <tr><td>Make</td><td>{$make}</td></tr>
            <tr><td>Model</td><td>{$model}</td></tr>
            <tr><td>Trim</td><td>{$trim}</td></tr>
            <tr><td>Cylinders</td><td>{$cylinders}</td></tr>
            <tr><td>Transmission</td><td>{$transmission}</td></tr>
            <tr><td>VIN</td><td>{$vin}</td></tr>
            <tr><td>Damages</td><td>{$damages}</td></tr>
            <tr><td>Source</td><td>{$source}</td></tr>
        </table>
    </body>
    </html>
    ";

    // Email headers for attachments
    $to = "sales@nextlaneauto.net";
    $subject = "New Trade Or Sell Your Vehicle Submission";
    $boundary = md5(time());
    $headers = "From: " . $email . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

    // Email body with attachments
    $emailBody = "--$boundary\r\n";
    $emailBody .= "Content-Type: text/html; charset=UTF-8\r\n";
    $emailBody .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $emailBody .= $message . "\r\n";

    // Add attachments
    foreach ($uploadedFiles as $filePath) {
        $fileContent = file_get_contents($filePath);
        $fileName = basename($filePath);
        $emailBody .= "--$boundary\r\n";
        $emailBody .= "Content-Type: application/octet-stream; name=\"$fileName\"\r\n";
        $emailBody .= "Content-Transfer-Encoding: base64\r\n";
        $emailBody .= "Content-Disposition: attachment; filename=\"$fileName\"\r\n\r\n";
        $emailBody .= chunk_split(base64_encode($fileContent)) . "\r\n";
    }

    $emailBody .= "--$boundary--";

    // Send the email
    if (mail($to, $subject, $emailBody, $headers)) {
        header('Location: /success.html');
        exit();
    } else {
        header('Location: /failed.html');
        exit();
    }
}
?>
