<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = htmlspecialchars($_POST['email']);
    $vehicle = htmlspecialchars($_POST['vehicle']);
    $comments = htmlspecialchars($_POST['comments']);
    $lease_or_finance = htmlspecialchars($_POST['lease_or_finance']);
    $agree = isset($_POST['agree']) ? 'Yes' : 'No';

    // CAPTCHA verification
    $captcha = $_POST['g-recaptcha-response'];
    $secret_key = '6LeDJrsqAAAAAOSnnGEdd8eTxPEfHYIhFEMUEbRe';

    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$captcha");
    $response_keys = json_decode($response, true);

    if (!$response_keys['success']) {
        die('CAPTCHA verification failed. Please try again.');
    }

    // Format the email in HTML
    $to = "sales@nextlaneauto.net";
    $subject = "New Request a Free Quote Form Submission";
    $message = "<html><body>";
    $message .= "<h1>Request a Free Quote Form Submission</h1>";
    $message .= "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>First Name</td><td>$first_name</td></tr>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Last Name</td><td>$last_name</td></tr>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Phone No</td><td>$phone</td></tr>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Email</td><td>$email</td></tr>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Vehicle</td><td>$vehicle</td></tr>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Comments</td><td>$comments</td></tr>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Lease or Finance</td><td>$lease_or_finance</td></tr>";
    $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Agreed to Privacy Policy</td><td>$agree</td></tr>";
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
}
?>
