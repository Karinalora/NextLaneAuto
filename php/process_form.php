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
    $secret_key = '6LeDJrsqAAAAAOSnnGEdd8eTxPEfHYIhFEMUEbRe';

    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$captcha");
    $response_keys = json_decode($response, true);

    if (!$response_keys['success']) {
        die('CAPTCHA verification failed. Please try again.');
    }

    // Process the data (e.g., save to database or send email)
    $to = "sales@nextlaneauto.net";
    $subject = "New Claim Form Submission";
    $message = "
    <h2>New Claim Form Submission</h2>
    <p><strong>First Name:</strong> $first_name</p>
    <p><strong>Last Name:</strong> $last_name</p>
    <p><strong>Phone No:</strong> $phone</p> 
    <p><strong>Email:</strong> $email</p> 
    <p><strong>Comments:</strong> $comments</p>
    <p><strong>Lease or Finance:</strong> $lease_or_finance</p>
    <p><strong> Agreed to Terms:</strong> $agree</p>
    ";
    $headers = "From: " . $email . "\r\n" .
    "Reply-To: " . $email . "\r\n" .
    "Content-Type: text/html; charset=UTF-8";

    if (mail($to, $subject, $message, $headers)) {
        header('Location: /success.html');
        exit();  // Asegúrate de terminar el script después de la redirección
        echo "Thank you! Your submission has been received.";
    } else {
              //echo "Failed to Send Message.";
             // Si todo es correcto, redireccionas a la página de agradecimiento
        header('Location: /failed.html');
        echo "Sorry, there was an error processing your form. Please try again.";
    }
}
?>
