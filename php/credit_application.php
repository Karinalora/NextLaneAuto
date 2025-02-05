<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Determinar si hay un co-buyer
    $isCoBuyer = isset($_POST['coBuyer']) && $_POST['coBuyer'] === 'yes';

    // Campos básicos (sin co-buyer)
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
    ];

    // Si hay un co-buyer, agregar datos adicionales
    if ($isCoBuyer) {
        $fields["Co-Buyer Information"] = [
            "First Name" => $_POST['cofirstName'] ?? '',
            "Middle Name" => $_POST['comiddleName'] ?? '',
            "Last Name" => $_POST['colastName'] ?? '',
            "SSN/ITIN" => $_POST['cossn'] ?? '',
            "Date of Birth" => $_POST['codob'] ?? '',
            "Driver License #" => $_POST['codriverLicense'] ?? '',
            "Phone" => $_POST['cophone'] ?? '',
            "Email Address" => $_POST['coemail'] ?? '',
        ];
        $fields["Co-Buyer Address"] = [
            "Address" => $_POST['coaddress'] ?? '',
            "Apt/Unit" => $_POST['coaptUnit'] ?? '',
            "City" => $_POST['cocity'] ?? '',
            "County" => $_POST['cocounty'] ?? '',
            "State" => $_POST['costate'] ?? '',
            "Zip Code" => $_POST['cozipCode'] ?? '',
            "Time At Current Address" => $_POST['cotimeAtAddress'] ?? '',
            "Ownership" => $_POST['ownership'] ?? '',
            "Rent/Mortgage Payment" => $_POST['corentPayment'] ?? '',
        ];
        $fields["Co-Buyer Employment"] = [
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
        ];
        $fields["Co-Buyer Income"] = [
            "Gross Monthly Income" => $_POST['cogrossIncome'] ?? '',
            "Other Monthly Income" => $_POST['cootherIncome'] ?? '',
            "Description" => $_POST['coincomeDescription'] ?? '',
        ];
    }

    // Formatear el correo en HTML
    $to = "main@nextlaneauto.net";
    $subject = "New Credit Application Submission Form";
    $emailBody = "<html><body>";
    $emailBody .= "<h1>New Credit Application Submission</h1>";

    foreach ($fields as $section => $data) {
        $emailBody .= "<h2 style='color: #e00e0e;'><strong>$section</strong></h2>";
        $emailBody .= "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
        foreach ($data as $label => $value) {
            $emailBody .= "<tr>";
            $emailBody .= "<td style='font-weight: bold; background-color: #f2f2f2;'>$label</td>";
            $emailBody .= "<td>" . htmlspecialchars($value) . "</td>";
            $emailBody .= "</tr>";
        }
        $emailBody .= "</table><br>";
    }

    $emailBody .= "</body></html>";

    // Configurar encabezados del correo
    $headers = "From: " . ($_POST['email'] ?? 'noreply@nextlaneauto.net') . "\r\n";
    $headers .= "Reply-To: " . ($_POST['email'] ?? 'noreply@nextlaneauto.net') . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8";

    // Enviar el correo
    if (mail($to, $subject, $emailBody, $headers)) {
        header('Location: /success.html');
        exit();
    } else {
        header('Location: /failed.html');
        exit();
    }
}
?>
