<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = "your_email@example.com"; // Replace with your email address
    $subject = "New Credit Application Form Submission";
    
    // Collect form data
    $firstName = htmlspecialchars($_POST['firstName']);
    $middleName = htmlspecialchars($_POST['middleName']);
    $lastName = htmlspecialchars($_POST['lastName']);
    $dob = htmlspecialchars($_POST['dob']);
    $driversLicense = htmlspecialchars($_POST['driversLicense']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = htmlspecialchars($_POST['email']);
    $address = htmlspecialchars($_POST['address']);
    $apt = htmlspecialchars($_POST['apt']);
    $city = htmlspecialchars($_POST['city']);
    $state = htmlspecialchars($_POST['state']);
    $zipCode = htmlspecialchars($_POST['zipCode']);
    $type = htmlspecialchars($_POST['type']);
    $year = htmlspecialchars($_POST['year']);
    $make = htmlspecialchars($_POST['make']);
    $model = htmlspecialchars($_POST['model']);
    $trim = htmlspecialchars($_POST['trim']);
    $salesAgent = htmlspecialchars($_POST['salesAgent']);

    // Build the email message
    $message = "
    Credit Application Form Submission:

    Personal Info:
    First Name: $firstName
    Middle Name: $middleName
    Last Name: $lastName
    Date of Birth: $dob
    Driver's License: $driversLicense

    Contact Info:
    Phone: $phone
    Email: $email
    Address: $address
    Apt/Unit: $apt
    City: $city
    State: $state
    Zip Code: $zipCode

    Vehicle Info:
    Type: $type
    Year: $year
    Make: $make
    Model: $model
    Trim: $trim

    Sales Agent: $salesAgent
    ";

    // Email headers
    $headers = "From: noreply@example.com"; // Replace with a valid sender email address

    // Send the email
    if (mail($to, $subject, $message, $headers)) {
        echo "Thank you! Your form has been submitted successfully.";
    } else {
        echo "Sorry, there was an error sending your form. Please try again later.";
    }
} else {
    echo "Invalid request.";
}
?>
