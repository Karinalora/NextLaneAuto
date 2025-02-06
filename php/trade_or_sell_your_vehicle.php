<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php';

// Your SendGrid API Key
$sendgrid_api_key = 'YOUR_SENDGRID_API_KEY';

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

    $message = "<html><body>";
    $message .= "<h2>Submission Details</h2>";
    $message .= "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
    $fields = [
        'First Name' => $firstName,
        'Last Name' => $lastName,
        'Email' => $email,
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

   // $to = "main@nextlaneauto.net";
   // $subject = "New Trade Or Sell Your Vehicle Submission";
    $boundary = md5(time());
  //  $headers = "From: " . $email . "\r\n";
  //  $headers .= "Reply-To: " . $email . "\r\n";
  //  $headers .= "MIME-Version: 1.0\r\n";
  //  $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

    $email = new \SendGrid\Mail\Mail();
    $email->setFrom("sales@nextlaneauto.net");
    $email->setSubject("New Trade Or Sell Your Vehicle Submission:" . $subject);
    $email->addTo("sales@nextlaneauto.net");

    $emailBody = "--$boundary\r\n";
    $emailBody .= "Content-Type: text/html; charset=UTF-8\r\n";
    $emailBody .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $emailBody .= $message . "\r\n";

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

    // Add the multipart content to the email
    $email->addContent("multipart/mixed; boundary=\"$boundary\"", $emailBody);
          //$sendgrid = new \SendGrid\SendGrid(getenv($sendgrid_api_key));
     // ✅ Correct SendGrid Initialization
     $sendgrid = new \SendGrid($sendgrid_api_key);

     try {
         $response = $sendgrid->send($email);
         echo 'Email sent successfully!';
         header('Location: /success.html');
         exit();        
     } catch (\SendGrid\Exception $e) {
         echo 'Caught exception: '. $e->getMessage() ."\n";
         header('Location: /failed.html');
         exit();
     }

   // if (mail($to, $subject, $emailBody, $headers)) {
   //     header('Location: /success.html');
   //     exit();
   // } else {
   //     header('Location: /failed.html');
   //     exit();
   // }
}
?>
