const express = require("express");
const nodemailer = require("nodemailer");
const bodyParser = require("body-parser");

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware to parse form data
app.use(bodyParser.urlencoded({ extended: true }));

// Nodemailer configuration
const transporter = nodemailer.createTransport({
  service: "gmail", // Replace with your email provider
  auth: {
    user: "your-email@gmail.com", // Your email address
    pass: "your-email-password", // Your email password or app password
  },
});

// Route to handle form submissions
app.post("/send-email", (req, res) => {
  const {
    firstName,
    middleName,
    lastName,
    ssn,
    dob,
    driverLicense,
    phone,
    email,
    address,
    aptUnit,
    city,
    county,
    state,
    zipCode,
    timeAtAddress,
    ownership,
    rentPayment,
    grossIncome,
    otherIncome,
    incomeDescription,
  } = req.body;

  const mailOptions = {
    from: "your-email@gmail.com",
    to: "recipient-email@example.com", // Replace with your recipient email
    subject: "New Credit Application Submission",
    text: `
      New Credit Application Details:

      Primary Applicant:
      - First Name: ${firstName}
      - Middle Name: ${middleName || "N/A"}
      - Last Name: ${lastName}
      - SSN: ${ssn}
      - Date of Birth: ${dob || "N/A"}
      - Driver License #: ${driverLicense || "N/A"}
      - Phone: ${phone}
      - Email: ${email}

      Address:
      - Address: ${address}
      - Apt/Unit: ${aptUnit || "N/A"}
      - City: ${city}
      - County: ${county || "N/A"}
      - State: ${state}
      - Zip Code: ${zipCode}
      - Time at Current Address: ${timeAtAddress}
      - Ownership: ${ownership}
      - Rent/Mortgage Payment: ${rentPayment}

      Income:
      - Gross Monthly Income: ${grossIncome}
      - Other Monthly Income: ${otherIncome || "N/A"}
      - Income Description: ${incomeDescription || "N/A"}
    `,
  };

  transporter.sendMail(mailOptions, (error, info) => {
    if (error) {
      console.error(error);
      return res.status(500).send("Error sending email");
    }
    res.send("Application submitted successfully");
  });
});

// Start the server
app.listen(PORT, () => {
  console.log(`Server running on port ${PORT}`);
});
