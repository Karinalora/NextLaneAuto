<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php';

// Your SendGrid API Key
$sendgrid_api_key = getenv('SENDGRID_API_KEY'); // ✅ Use environment variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and retrieve input data
    $firstName = htmlspecialchars($_POST['first_name']);
    $lastName = htmlspecialchars($_POST['last_name']);
    $emailfromform = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
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
   // $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
   $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/heic', 'image/heif'];

    $uploadedFiles = [];
    $errors = [];

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

    // Prepare Email Content
    $message = "<html><body>";
    $message .= "<h2>Submission Details</h2>";
    $message .= "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
    $fields = [
        'First Name' => $firstName,
        'Last Name' => $lastName,
        'Email' => $emailfromform,
        'Phone' => $phone,
        'Color' => $color,
        'Odometer' => $odometer,
        'Year' => $year,
        'Make' => $make,
        'Model' => $model,
        'Trim' => $trim,
        'Cylinders' => $cylinders,
        'Transmission' => $transmission,
        'VIN' => $vin,
        'Damages' => $damages,
        'Source' => $source
    ];

    foreach ($fields as $label => $value) {
        $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>$label</td><td>$value</td></tr>";
    }
    $message .= "</table></body></html>";

    // Send Email with SendGrid
    $email = new \SendGrid\Mail\Mail();
    $email->setFrom("sales@nextlaneauto.net", "NextLane Auto Sales");
    $email->setSubject("New Trade Or Sell Your Vehicle Submission:");
    $email->addTo("sales@nextlaneauto.net");
    $email->addContent("text/html", $message); // ✅ Correct content type

    // Add attachments
    foreach ($uploadedFiles as $filePath) {
        $fileContent = file_get_contents($filePath);
        $fileName = basename($filePath);
        $email->addAttachment(
            base64_encode($fileContent),
            mime_content_type($filePath),
            $fileName,
            "attachment"
        );
    }

    $sendgrid = new \SendGrid($sendgrid_api_key);

    try {
        $response = $sendgrid->send($email);
        if ($response->statusCode() == 202) {
            header('Location: /success.html');
            exit();
        } else {
            throw new Exception("SendGrid API error: " . $response->body());
        }
    } catch (\Exception $e) {
        error_log('Email Error: ' . $e->getMessage());
        header('Location: /failed.html');
        exit();
    }
}
?>
