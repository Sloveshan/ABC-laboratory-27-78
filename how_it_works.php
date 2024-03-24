<?php $pageTitle = 'How It Works'; include('templates/header.php'); ?>
<?php include('templates/navbar.php') ;?>

<div class="container">
        <h1 class="mt-5">How it Works</h1>
        <p class="lead">Our online Medical Test Appointment Platform simplifies the process for patients to schedule appointments, manage their medical information, and access test results seamlessly. Below is a step-by-step guide on how our platform operates:</p>

        <h3 class="mt-4">Registration and Account Creation</h3>
        <p>In the registration form:</p>
        <ul>
            <li>Confirm Password Validation</li>
            <li>Date of Birth Above 18 Years Age Validation</li>
            <li>Email Already Registered Validation</li>
            <li>'Thank You for Registration' Email Sent Upon Successful Registration</li>
        </ul>

        <h3 class="mt-4">Login Form</h3>
        <p>In the login form:</p>
        <ul>
            <li>Incorrect Email or Password Validation</li>
            <li>Javascript function to toggle hide/show Password (Used for all password fields)</li>
            
        </ul>

        <h3 class="mt-4">Patient Dashboard</h3>
        <ol>
            <li><strong>Login and Account Management:</strong> Patients can log in to their dashboard to view, edit, and change their account information and password.</li>
            <li><strong>Appointment Management:</strong> Patients can create unlimited new appointments or cancel existing ones.</li>
            <li><strong>Payment Options:</strong> Payments can be made securely using credit/debit cards, or patients can upload paid receipts.</li>
            <li><strong>Transaction History:</strong> Patients can view their transaction history and download receipts.</li>
            <li><strong>Access to Test Results:</strong> Patients can view completed test results and download test reports.</li>
        </ol>

        <h3 class="mt-4">Appointment Process</h3>
        <ol>
            <li><strong>Availability Confirmation:</strong> Doctors and technicians can approve their availability for the chosen test.</li>
            <li><strong>Payment Submission:</strong> Once both the doctor and technician are available, patients can proceed with payment either by credit/debit card or by uploading payment receipts.</li>
            <li><strong>Payment Confirmation:</strong> Upon payment submission, a 'Thank You for the Payment' email is sent to the patient, with the option to download the receipt.</li>
            <li><strong>Admin Approval:</strong> The admin approves the payment and confirms the appointment.</li>
            <li><strong>Confirmation Email:</strong> Patients receive an email notifying them that their appointment has been confirmed.</li>
        </ol>

        <h3 class="mt-4">Test Completion</h3>
        <ol>
            <li><strong>Test Results Upload:</strong> After the medical test is completed, the technician creates the test results and uploads the test report.</li>
            <li><strong>Email Test Report:</strong> Patients receive an email with their successful test report.</li>
            <li><strong>Report Access:</strong> Patients can easily access and download their test reports.</li>
        </ol>
    </div>

<?php include('templates/footer.php') ?>
