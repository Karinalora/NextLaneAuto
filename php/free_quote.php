<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $first_name = htmlspecialchars(trim($_POST['firstName']));
    $last_name = htmlspecialchars(trim($_POST['lastName']));
    $phone_no = htmlspecialchars(trim($_POST['phoneNo']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message_details = htmlspecialchars(trim($_POST['message']));

    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($phone_no) || empty($subject) || empty($message_details)) {
        die('All fields are required.');
    }

    // Email recipient and subject
    $to = "sales@nextlaneauto.com"; // Replace with your email address
    $email_subject = "New Message from Contact Form: $subject";

    // Email body
    $email_body = "
        <strong>First Name:</strong> $first_name<br>
        <strong>Last Name:</strong> $last_name<br>
        <strong>Phone No.:</strong> $phone_no<br>
        <strong>Subject:</strong> $subject<br>
        <strong>Message:</strong><br>$message_details
    ";

    // Email headers
    $headers = "From:  sales@nextlaneauto.com\r\n"; // Replace with a valid "from" email
    //$headers .= "Reply-To: no-reply@example.com\r\n"; // Replace as needed
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    // Send the email
    if (mail($to, $email_subject, $email_body, $headers)) {
        // Redirect to a success page
        header('Location: /success.html');
        exit();
    } else {
        // Redirect to a failure page
        header('Location: /failed.html');
        exit();
    }
}
?>
