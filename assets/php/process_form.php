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

    // CAPTCHA verification (optional)
    $captcha = $_POST['g-recaptcha-response'];
    $secret_key = 'your-secret-key';

    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$captcha");
    $response_keys = json_decode($response, true);

    if (!$response_keys['success']) {
        die('CAPTCHA verification failed. Please try again.');
    }

    // Process the data (e.g., save to database or send email)
    $to = "your-email@example.com";
    $subject = "New Claim Form Submission";
    $message = "
        First Name: $first_name\n
        Last Name: $last_name\n
        Phone: $phone\n
        Email: $email\n
        Vehicle: $vehicle\n
        Comments: $comments\n
        Lease or Finance: $lease_or_finance\n
        Agreed to Terms: $agree
    ";
    $headers = "From: no-reply@yourdomain.com";

    if (mail($to, $subject, $message, $headers)) {
        echo "Thank you! Your submission has been received.";
    } else {
        echo "Sorry, there was an error processing your form. Please try again.";
    }
}
?>
