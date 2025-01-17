
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

   // $recaptchaResponse = $_POST['g-recaptcha-response'];
   // $secretKey = '6Lc3pUoqAAAAAJTyWRL1fsh0jXqTqaCrxmxdAr8U';
   // $verifyURL = 'https://www.google.com/recaptcha/api/siteverify';

        // Verificar la respuesta de reCAPTCHA
//    $response = file_get_contents($verifyURL . '?secret=' . $secretKey . '&response=' . $recaptchaResponse);
 //   $responseKeys = json_decode($response, true);

    // Obtener los datos del formulario
//    $name = htmlspecialchars($_POST['name']);
 //   $email = htmlspecialchars($_POST['email']);
 //   $phone = htmlspecialchars($_POST['phone']);
 //   $project = htmlspecialchars($_POST['project']);
 //   $subject = htmlspecialchars($_POST['subject']);
 //   $message = htmlspecialchars($_POST['message']);

    $first_name = htmlspecialchars(trim($_POST['firstName']));
    $last_name = htmlspecialchars(trim($_POST['lastName']));
    $email = htmlspecialchars($_POST['email']);
    $phone_no = htmlspecialchars(trim($_POST['phoneNo']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message_details = htmlspecialchars(trim($_POST['message']));

    // Verificar si los campos requeridos están llenos
    if(!empty($email) && !empty($message_details)){ // && ($responseKeys["success"]

        // Configuración del servidor de correo SMTP
        $to = "sales@nextlaneauto.com";  // Reemplaza con tu correo de recepción
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

        // Enviar el correo usando mail() de PHP
        if(mail($to, $email_subject, $email_body, $headers)) {
           // echo "Message Sent Successfully!";
           // Si todo es correcto, redireccionas a la página de agradecimiento
           header('Location: /success.html');
           exit();  // Asegúrate de terminar el script después de la redirección
        } else {
            //echo "Failed to Send Message.";
             // Si todo es correcto, redireccionas a la página de agradecimiento
           header('Location: /failed.html');
           exit();  // Asegúrate de terminar el script después de la redirección
            
        }
    } else {
        echo "Please fill all required fields.";
    }
}
?>

