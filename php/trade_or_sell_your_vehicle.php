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

       // Process the data (e.g., save to database or send email)
       $to = "sales@nextlaneauto.net";
       $subject = "New Claim Form Credit Application Submission";
    
    foreach ($_FILES as $fileKey => $file) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            if (in_array($file['type'], $allowedTypes)) {
                $filename = $uploadDir . basename($file['name']);
                if (move_uploaded_file($file['tmp_name'], $filename)) {
                    $uploadedFiles[$fileKey] = $filename;
                } else {
                    echo "Error uploading file: " . $fileKey;
                }
            } else {
                echo "Invalid file type for: " . $fileKey;
            }
        }
    }

    // You can add email sending or database storage here
    $headers = "From: " . $email . "\r\n" .
    "Reply-To: " . $email . "\r\n" .
    "Content-Type: text/html; charset=UTF-8";

    if (mail($to, $subject, $fileKey, $headers)) {
        header('Location: /success.html');
        exit();  // Asegúrate de terminar el script después de la redirección
        echo "Thank you! Your submission has been received.";
    } else {
              //echo "Failed to Send Message.";
             // Si todo es correcto, redireccionas a la página de agradecimiento
        header('Location: /failed.html');
        echo "Sorry, there was an error processing your form. Please try again.";
    }
}
?>
