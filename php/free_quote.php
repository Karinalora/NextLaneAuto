<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // CAPTCHA verification
    $captcha = $_POST['g-recaptcha-response'];
    $secret_key = '6LeDJrsqAAAAAOSnnGEdd8eTxPEfHYIhFEMUEbRe';

    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$captcha");
    $response_keys = json_decode($response, true);

    $first_name = htmlspecialchars(trim($_POST['firstName'] ?? ''));
    $last_name = htmlspecialchars(trim($_POST['lastName'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $phone_no = htmlspecialchars(trim($_POST['phoneNo'] ?? ''));
    $subject = htmlspecialchars(trim($_POST['subject'] ?? ''));
    $message_details = htmlspecialchars(trim($_POST['message'] ?? ''));

    if (!empty($email) && !empty($message_details) && !empty($phone_no) && !empty($subject) && !empty($last_name) && !empty($first_name) && ($response_keys["success"])) {  
        $to = "sales@nextlaneauto.net";
        $headers = "From: " . $email . "\r\n" .
        "Reply-To: " . $email . "\r\n" .
        "Content-Type: text/html; charset=UTF-8";

        $email_subject = "New Contact Form Submission: " . $subject;

        // Formatear el correo en HTML
        $email_body = "<html><body>";
        $email_body .= "<h1>New Contact Form Submission</h1>";
        $email_body .= "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
        $email_body .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>First Name</td><td>$first_name</td></tr>";
        $email_body .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Last Name</td><td>$last_name</td></tr>";
        $email_body .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Email</td><td>$email</td></tr>";
        $email_body .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Phone No</td><td>$phone_no</td></tr>";
        $email_body .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Subject</td><td>$subject</td></tr>";
        $email_body .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Message Details</td><td>$message_details</td></tr>";
        $email_body .= "</table>";
        $email_body .= "</body></html>";

        if (mail($to, $email_subject, $email_body, $headers)) {  
            header('Location: /success.html');
            exit();
        } else {
            error_log("Failed to send email to $to from $email.");
            header('Location: /failed.html');
            exit();
        }
    } else {
        echo "Please fill in all required fields.";
    }
}
?>

