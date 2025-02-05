<?php
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
    $email = new \SendGrid\Mail\Mail();
    $email->setFrom("sales@nextlaneauto.net");
    $email->setSubject("New Contact Form Submission:" . $subject);
    $email->addTo("main@nextlaneauto.net");
    $email->addContent(
        "text/html", "<html><body>
        <h1>New Contact Form Submission</h1>
        <table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>
        <tr><td style='font-weight: bold; background-color: #f2f2f2;'>First Name</td><td>$first_name</td></tr>
        <tr><td style='font-weight: bold; background-color: #f2f2f2;'>Last Name</td><td>$last_name</td></tr>
        <tr><td style='font-weight: bold; background-color: #f2f2f2;'>Email</td><td>$email</td></tr>
        <tr><td style='font-weight: bold; background-color: #f2f2f2;'>Phone No</td><td>$phone_no</td></tr>
        <tr><td style='font-weight: bold; background-color: #f2f2f2;'>Subject</td><td>$subject</td></tr>
        <tr><td style='font-weight: bold; background-color: #f2f2f2;'>Message Details</td><td>$message_details</td></tr>
        </table>
        </body></html>"
    );

    $sendgrid = new \SendGrid\SendGrid(getenv('SENDGRID_API_KEY'));

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

    // Format the email in HTML
   // $to = "main@nextlaneauto.net";
   // $subject = "New Contact Form Submission:" . $subject;
   // $message = "<html><body>";
   // $message .= "<h1>New Contact Form Submission</h1>";
   // $message .= "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
  //  $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>First Name</td><td>$first_name</td></tr>";
  //  $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Last Name</td><td>$last_name</td></tr>";
  //  $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Email</td><td>$email</td></tr>";
  //  $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Phone No</td><td>$phone_no</td></tr>";
  //  $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Subject</td><td>$subject</td></tr>";
  //  $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Message Details</td><td>$message_details</td></tr>";
  //  $message .= "</table>";
  //  $message .= "</body></html>";

  //  $headers = "From: " . $email . "\r\n" .
  //  "Reply-To: " . $email . "\r\n" .
  //  "Content-Type: text/html; charset=UTF-8";

//    if (mail($to, $subject, $message, $headers)) {
 //       header('Location: /success.html');
 //       exit();
 //   } else {
 //       header('Location: /failed.html');
 //       exit();
 //   }
}
?>
