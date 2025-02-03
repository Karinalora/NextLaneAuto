<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CAPTCHA verification
    $captcha = $_POST['g-recaptcha-response'];
    $secret_key = '6LeDJrsqAAAAAOSnnGEdd8eTxPEfHYIhFEMUEbRe';

    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$captcha");
    $response_keys = json_decode($response, true);

    if (!$response_keys['success']) {
        die('CAPTCHA verification failed. Please try again.');
    }

    $to = "sales@nextlaneauto.net";
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

    if ($make === 'OTHER') {
        $otherMake = htmlspecialchars($_POST['other-make']);
        $make = "Other ($otherMake)";
    }
    if ($model === 'OTHER') {
        $otherModel = htmlspecialchars($_POST['other-model']);
        $model = "Other ($otherModel)";
    }
    if ($trim === 'OTHER') {
        $otherTrim = htmlspecialchars($_POST['other-trim']);
        $trim = "Other ($otherTrim)";
    }

    // Build the email message
    $message = "<html><body>";
    $message .= "<h1>Get pre-qualified Form Submission:</h1>";

    $message .= "<h2>Personal Info:</h2>";
    $message .= "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>First Name</td><td>$firstName</td></tr>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Middle Name</td><td>$middleName</td></tr>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Last Name</td><td>$lastName</td></tr>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Date of Birth</td><td>$dob</td></tr>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Driver's License</td><td>$driversLicense</td></tr>";

    $message .= "<h2>Contact Info:</h2>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Phone</td><td>$phone</td></tr>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Email</td><td>$email</td></tr>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Address</td><td>$address</td></tr>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Apt/Unit</td><td>$apt</td></tr>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>City</td><td>$city</td></tr>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>State</td><td>$state</td></tr>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Zip Code</td><td>$zipCode</td></tr>";

    $message .= "<h2>Vehicle Info:</h2>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Type</td><td>$type</td></tr>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Year</td><td>$year</td></tr>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Make</td><td>$make</td></tr>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Model</td><td>$model</td></tr>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Trim</td><td>$trim</td></tr>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Agreed to Terms</td><td>$agree</td></tr>";
    $message .= "</table>";
    $message .= "</body></html>";

    $headers = "From: " . $email . "\r\n" .
    "Reply-To: " . $email . "\r\n" .
    "Content-Type: text/html; charset=UTF-8";

    if (mail($to, $subject, $message, $headers)) {
        header('Location: /success.html');
        exit();
    } else {
        header('Location: /failed.html');
        exit();
    }
} else {
    echo "Invalid request.";
}
?>
