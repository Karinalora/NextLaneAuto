<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // CAPTCHA verification (optional)
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

    if (!empty($email) && !empty($message_details) && !empty($phone_no) && !empty($subject) && !empty($last_name) && !empty($first_name ) && ($response_keys["success"]) ) {  
        $to = "sales@nextlaneauto.net";
        $headers = "From: " . $email . "\r\n" .
        "Reply-To: " . $email . "\r\n" .
        "Content-Type: text/html; charset=UTF-8";

        $email_subject = "New Contact Form Submission: " . $subject;
        $email_body = "
            <h2>Contact Form Details</h2>
            <p><strong>First Name:</strong> $first_name</p>
            <p><strong>Last Name:</strong> $last_name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Phone No:</strong> $phone_no</p> 
            <p><strong>Subject:</strong> $subject</p>
            <p><strong>Message Details:</strong> $message_details</p>
        ";

        if (mail($to, $email_subject,  $email_body, $headers)) {  
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
