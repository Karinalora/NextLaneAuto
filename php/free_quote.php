<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Asegúrate de que PHPMailer esté instalado mediante Composer

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recopilar y sanitizar los datos del formulario
    $first_name = htmlspecialchars(trim($_POST['firstName']));
    $last_name = htmlspecialchars(trim($_POST['lastName']));
    $email = htmlspecialchars($_POST['email']);
    $phone_no = htmlspecialchars(trim($_POST['phoneNo']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message_details = htmlspecialchars(trim($_POST['message']));

    // Validar los campos requeridos
    if (
        empty($first_name) || empty($last_name) || empty($phone_no) || 
        empty($subject) || empty($message_details) || empty($email) ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) || 
        !preg_match('/^\d{10}$/', $phone_no) || 
        !preg_match('/^[a-zA-Z ]+$/', $first_name) || 
        !preg_match('/^[a-zA-Z ]+$/', $last_name)
    ) {
        die('Todos los campos son obligatorios y deben estar correctamente llenos.');
    }

    // Configuración del correo
    $to = "sales@nextlaneauto.com"; // Dirección de correo del destinatario
    $email_subject = "Nuevo mensaje del formulario de contacto: $subject";

    // Cuerpo del correo
    $email_body = "
        <strong>Nombre:</strong> $first_name<br>
        <strong>Apellido:</strong> $last_name<br>
        <strong>Teléfono:</strong> $phone_no<br>
        <strong>Asunto:</strong> $subject<br>
        <strong>Mensaje:</strong><br>$message_details
    ";

    // Configuración de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sales@nextlaneauto.com'; // Tu correo de Gmail
        $mail->Password = 'Teslam440q60'; // Contraseña o contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configuración del remitente y destinatario
        $mail->setFrom($emai, $first_name); // Dirección de envío
        $mail->addAddress($to, 'NextLane Auto'); // Dirección del destinatario
        $mail->addReplyTo($email, "$first_name $last_name"); // Responder al remitente

        // Configuración del contenido del correo
        $mail->isHTML(true);
        $mail->Subject = $email_subject;
        $mail->Body = $email_body;
        $mail->AltBody = strip_tags($email_body);

        // Enviar el correo
        $mail->send();

        // Redireccionar a la página de éxito
        header('Location: /success.html');
        exit();
    } catch (Exception $e) {
        error_log("Error al enviar el correo: {$mail->ErrorInfo}");

        // Redireccionar a la página de error
        header('Location: /failed.html');
        exit();
    }
}
?>
