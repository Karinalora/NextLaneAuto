<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    if (!$response_keys['success']) {
        die('CAPTCHA verification failed. Please try again.');
    }

    $mail = new PHPMailer(true);
    try {
        // GoDaddy SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'localhost'; // GoDaddy relay server
        $mail->Port = 25;
        $mail->SMTPAuth = false; // No authentication
        $mail->SMTPSecure = false; // No encryption
        
        // Sender must be an email hosted on GoDaddy
        $mail->setFrom("sales@nextlaneauto.net", "$first_name $last_name");
        $mail->addAddress("sales@nextlaneauto.net");
        $mail->addReplyTo($email, $first_name);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "New Contact Form Submission: " . $subject;
        $mail->Body = "<html><body>";
        $mail->Body .= "<h1>New Contact Form Submission</h1>";
        $mail->Body .= "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
        $mail->Body .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>First Name</td><td>$first_name</td></tr>";
        $mail->Body .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Last Name</td><td>$last_name</td></tr>";
        $mail->Body .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Email</td><td>$email</td></tr>";
        $mail->Body .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Phone No</td><td>$phone_no</td></tr>";
        $mail->Body .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Subject</td><td>$subject</td></tr>";
        $mail->Body .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Message Details</td><td>$message_details</td></tr>";
        $mail->Body .= "</table>";
        $mail->Body .= "</body></html>";
        
        $mail->send();
        header('Location: /success.html');
        exit();
    } catch (Exception $e) {
        header('Location: /failed.html');
        exit();
    }
}
?>

