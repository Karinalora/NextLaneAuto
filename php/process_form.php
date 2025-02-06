<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php';
// Your SendGrid API Key
  $sendgrid_api_key = 'YOUR_SENDGRID_API_KEY';
 // $sendgrid_api_key = getenv('SENDGRID_API_KEY');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $phone = htmlspecialchars($_POST['phone']);
    $emailfromform = htmlspecialchars($_POST['email']);
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

    $email = new \SendGrid\Mail\Mail();
    $email->setFrom("sales@nextlaneauto.net");
    $email->setSubject("New Request a Free Quote Form Submission:" . $subject);
    $email->addTo("sales@nextlaneauto.net");
    $email->addContent(
        "text/html", "<html><body>
        <h1>Request a Free Quote Form Submission</h1>
        <table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>
        <tr><td style='font-weight: bold; background-color: #f2f2f2;'>First Name</td><td>$first_name</td></tr>
        <tr><td style='font-weight: bold; background-color: #f2f2f2;'>Last Name</td><td>$last_name</td></tr>
        <tr><td style='font-weight: bold; background-color: #f2f2f2;'>Phone No</td><td>$phone</td></tr>
        <tr><td style='font-weight: bold; background-color: #f2f2f2;'>Email</td><td>$emailfromform</td></tr>
        <tr><td style='font-weight: bold; background-color: #f2f2f2;'>Vehicle</td><td>$vehicle</td></tr>
        <tr><td style='font-weight: bold; background-color: #f2f2f2;'>Comments</td><td>$comments</td></tr>
        <tr><td style='font-weight: bold; background-color: #f2f2f2;'>Lease or Finance</td><td>$lease_or_finance</td></tr>
        <tr><td style='font-weight: bold; background-color: #f2f2f2;'>Agreed to Privacy Policy</td><td>$agree</td></tr>
        </table>
        </body></html>"
    );

      //$sendgrid = new \SendGrid\SendGrid(getenv($sendgrid_api_key));
     // ✅ Correct SendGrid Initialization
     $sendgrid = new \SendGrid($sendgrid_api_key);

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
   // $subject = "New Request a Free Quote Form Submission";
   // $message = "<html><body>";
   // $message .= "<h1>Request a Free Quote Form Submission</h1>";
   // $message .= "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
   // $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>First Name</td><td>$first_name</td></tr>";
   // $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Last Name</td><td>$last_name</td></tr>";
   // $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Phone No</td><td>$phone</td></tr>";
   // $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Email</td><td>$emailfromform</td></tr>";
   // $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Vehicle</td><td>$vehicle</td></tr>";
   // $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Comments</td><td>$comments</td></tr>";
   // $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Lease or Finance</td><td>$lease_or_finance</td></tr>";
   // $message .= "<tr><td style='font-weight: bold; background-color: #f2f2f2;'>Agreed to Privacy Policy</td><td>$agree</td></tr>";
   // $message .= "</table>";
   // $message .= "</body></html>";

   // $headers = "From: " . $email . "\r\n" .
   // "Reply-To: " . $email . "\r\n" .
   // "Content-Type: text/html; charset=UTF-8";

    /// if (mail($to, $subject, $message, $headers)) {
     //   header('Location: /success.html');
     //   exit();
    //} else {
    //    header('Location: /failed.html');
    //    exit();
   // }
}
?>
