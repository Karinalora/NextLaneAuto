<?php
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = htmlspecialchars(trim($_POST['firstName'] ?? ''));
    $last_name = htmlspecialchars(trim($_POST['lastName'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $phone_no = htmlspecialchars(trim($_POST['phoneNo'] ?? ''));
    $subject = htmlspecialchars(trim($_POST['subject'] ?? ''));
    $message_details = htmlspecialchars(trim($_POST['message'] ?? ''));

    if (!empty($email) && !empty($message_details)) {
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
