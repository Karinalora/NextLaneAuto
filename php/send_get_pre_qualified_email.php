<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // CAPTCHA verification (optional)
        $captcha = $_POST['g-recaptcha-response'];
        $secret_key = '6LeDJrsqAAAAAOSnnGEdd8eTxPEfHYIhFEMUEbRe';
    
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$captcha");
        $response_keys = json_decode($response, true);
    
        if (!$response_keys['success']) {
            die('CAPTCHA verification failed. Please try again.');
        }


    $to = "sales@nextlaneauto.net"; // Replace with your email address
    $subject = "New Get pre-qualified Form Submission";
    
    // Collect form data
    $firstName = htmlspecialchars($_POST['firstName']);
    $middleName = htmlspecialchars($_POST['middleName']);
    $lastName = htmlspecialchars($_POST['lastName']);
    $dob = htmlspecialchars($_POST['dob']);
    $driversLicense = htmlspecialchars($_POST['driversLicense']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = htmlspecialchars($_POST['email']);
    $address = htmlspecialchars($_POST['address']);
    $apt = htmlspecialchars($_POST['apt']);
    $city = htmlspecialchars($_POST['city']);
    $state = htmlspecialchars($_POST['state']);
    $zipCode = htmlspecialchars($_POST['zipCode']);
    $type = htmlspecialchars($_POST['type']);
    $year = htmlspecialchars($_POST['year']);
    $make = htmlspecialchars($_POST['make']);
    $model = htmlspecialchars($_POST['model']);
    $trim = htmlspecialchars($_POST['trim']);
    $salesAgent = htmlspecialchars($_POST['salesAgent']);
    $agree = isset($_POST['agree']) ? 'Yes' : 'No';

    // Build the email message
    $message = "
    <h1>Get pre-qualified Form Submission:</h1>

    <h3>Personal Info:</h3>
    <p><strong>First Name:</strong> $firstName</p>
    <p><strong>Middle Name:</strong> $middleName</p>
    <p><strong>Last Name:</strong> $lastName</p>
    <p><strong>Date of Birth:</strong> $dob</p>
    <p><strong>Driver's License:</strong> $driversLicense</p>

    <h3>Contact Info:</h3>
    <p><strong>Phone:</strong> $phone</p>
    <p><strong>Email:</strong> $email</p>
    <p><strong>Address:</strong> $address</p>
    <p><strong>Apt/Unit:</strong> $apt</p>
    <p><strong>City:</strong> $city</p>
    <p><strong>State:</strong> $state</p>
    <p><strong>Zip Code:</strong> $zipCode</p>

    <h3>Vehicle Info:</h3>
    <p><strong>Type:</strong> $type</p>
    <p><strong>Year:</strong> $year</p>
    <p><strong>Make:</strong> $make</p>
    <p><strong>Model:</strong> $model</p>
    <p><strong>Trim:</strong> $trim</p>
    <p><strong> Agreed to Terms:</strong> $agree</p>
    ";

    // Email headers
    $headers = "From: " . $email . "\r\n" .
    "Reply-To: " . $email . "\r\n" .
    "Content-Type: text/html; charset=UTF-8"; // Replace with a valid sender email address

    // Send the email
    if (mail($to, $subject, $message, $headers)) {
        header('Location: /success.html');
        exit();  // Asegúrate de terminar el script después de la redirección
        echo "Thank you! Your form has been submitted successfully.";
    } else {
        header('Location: /failed.html');
        echo "Sorry, there was an error sending your form. Please try again later.";
    }
} else {
    echo "Invalid request.";
}
?>
