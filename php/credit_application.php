<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $fields = [
        "Primary Applicant" => [
            "First Name" => $_POST['firstName'] ?? '',
            "Middle Name" => $_POST['middleName'] ?? '',
            "Last Name" => $_POST['lastName'] ?? '',
            "SSN/ITIN" => $_POST['ssn'] ?? '',
            "Date of Birth" => $_POST['dob'] ?? '',
            "Driver License #" => $_POST['driverLicense'] ?? '',
            "Phone" => $_POST['phone'] ?? '',
            "Email Address" => $_POST['email'] ?? '',
        ],
        "Address" => [
            "Address" => $_POST['address'] ?? '',
            "Apt/Unit" => $_POST['aptUnit'] ?? '',
            "City" => $_POST['city'] ?? '',
            "County" => $_POST['county'] ?? '',
            "State" => $_POST['state'] ?? '',
            "Zip Code" => $_POST['zipCode'] ?? '',
            "Time At Current Address" => $_POST['timeAtAddress'] ?? '',
            "Ownership" => $_POST['ownership'] ?? '',
            "Rent/Mortgage Payment" => $_POST['rentPayment'] ?? '',
        ],
        "Employment" => [
            "Type of Employment" => $_POST['employmentType'] ?? '',
            "Employer Name" => $_POST['employer'] ?? '',
            "Occupation or Rank" => $_POST['occupation'] ?? '',
            "Work Phone" => $_POST['workPhone'] ?? '',
            "Extension" => $_POST['extension'] ?? '',
            "Time at Employment" => $_POST['timeAtEmployment'] ?? '',
            "Address" => $_POST['employmentAddress'] ?? '',
            "Apt/Unit" => $_POST['employmentAptUnit'] ?? '',
            "City" => $_POST['employmentCity'] ?? '',
            "State" => $_POST['employmentState'] ?? '',
            "Zip Code" => $_POST['employmentZipCode'] ?? '',
        ],
        "Income" => [
            "Gross Monthly Income" => $_POST['grossIncome'] ?? '',
            "Other Monthly Income" => $_POST['otherIncome'] ?? '',
            "Description" => $_POST['incomeDescription'] ?? '',
        ],
        "Co-Buyer Information" => [
            "First Name" => $_POST['cofirstName'] ?? '',
            "Middle Name" => $_POST['comiddleName'] ?? '',
            "Last Name" => $_POST['colastName'] ?? '',
            "SSN/ITIN" => $_POST['cossn'] ?? '',
            "Date of Birth" => $_POST['codob'] ?? '',
            "Driver License #" => $_POST['codriverLicense'] ?? '',
            "Phone" => $_POST['cophone'] ?? '',
            "Email Address" => $_POST['coemail'] ?? '',
        ],
        "Co-Buyer Address" => [
            "Address" => $_POST['coaddress'] ?? '',
            "Apt/Unit" => $_POST['coaptUnit'] ?? '',
            "City" => $_POST['cocity'] ?? '',
            "County" => $_POST['cocounty'] ?? '',
            "State" => $_POST['costate'] ?? '',
            "Zip Code" => $_POST['cozipCode'] ?? '',
            "Time At Current Address" => $_POST['cotimeAtAddress'] ?? '',
            "Ownership" => $_POST['ownership'] ?? '',
            "Rent/Mortgage Payment" => $_POST['corentPayment'] ?? '',
        ],
        "Co-Buyer Employment" => [
            "Type of Employment" => $_POST['coemploymentType'] ?? '',
            "Employer Name" => $_POST['coemployer'] ?? '',
            "Occupation or Rank" => $_POST['cooccupation'] ?? '',
            "Work Phone" => $_POST['coworkPhone'] ?? '',
            "Extension" => $_POST['coextension'] ?? '',
            "Time at Employment" => $_POST['cotimeAtEmployment'] ?? '',
            "Address" => $_POST['coemploymentAddress'] ?? '',
            "Apt/Unit" => $_POST['coemploymentAptUnit'] ?? '',
            "City" => $_POST['coemploymentCity'] ?? '',
            "State" => $_POST['coemploymentState'] ?? '',
            "Zip Code" => $_POST['coemploymentZipCode'] ?? '',
        ],
        "Co-Buyer Income" => [
            "Gross Monthly Income" => $_POST['cogrossIncome'] ?? '',
            "Other Monthly Income" => $_POST['cootherIncome'] ?? '',
            "Description" => $_POST['coincomeDescription'] ?? '',
        ],
    ];

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
    $subject = "New Claim Form Credit Application Submission";
    // Prepare email content
    $emailBody = "New Form Submission For Credit Application\n\n";
    foreach ($fields as $section => $data) {
        $emailBody .= "$section:\n";
        foreach ($data as $label => $value) {
            $emailBody .= "  $label: $value\n";
        }
        $emailBody .= "\n";
    }

    $headers = "From: " . $email . "\r\n" .
    "Reply-To: " . $email . "\r\n" .
    "Content-Type: text/html; charset=UTF-8";

    if (mail($to, $subject, $emailBody, $headers)) {
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