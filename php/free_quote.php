<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('America/New_York');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';
require __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';


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
        $mail = new PHPMailer(true);

        try {
             // Habilitar depuración SMTP para ver el proceso en detalle
             $mail->SMTPDebug = 4;  // Cambiar a 4 si necesitas más detalles
             $mail->Debugoutput = 'html';
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';       // Servidor SMTP de Gmail
            $mail->SMTPAuth   = true;
            $mail->Username   = 'sales@nextlaneauto.net';    // Tu correo de Gmail
            $mail->Password   = 'mszq fjnu adbb wygb'; // Contraseña de la aplicación
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->Timeout    = 15; // Limitar el tiempo de espera a 15 segundos

            // Configuración del remitente y destinatario
            $mail->setFrom($email, $first_name . ' ' . $last_name);
            $mail->addAddress('sales@nextlaneauto.net');  // Destinatario
            $mail->addReplyTo($email, $first_name);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = "New Contact Form Submission: " . $subject;

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

            $mail->Body = $email_body;

            $mail->send();
            header('Location: /success.html');
            exit();
        } catch (Exception $e) {
            error_log("Error al enviar el correo: {$mail->ErrorInfo}");
            echo "Error al enviar el correo: {$mail->ErrorInfo}";
            header('Location: /failed.html');
            exit();
        }
    } else {
        echo "Please fill in all required fields.";
    }
}
?>
