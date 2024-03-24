<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
@include '../config.php';

if (!isset($_SESSION['patient_id'])) {
    header('location:../login_form.php');
    exit;
}

if (!isset($_GET['appointment_id'])) {
    header('location:appointment.php');
    exit;
}

$appointment_id = $_GET['appointment_id'];

$query = "SELECT patient_id, test_type, date, time, price FROM appointments WHERE appointment_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();

$pageTitle = 'Upload Receipt';
include('../templates/header.php');

function include_css() {
    echo '<link rel="stylesheet" type="text/css" href="../css/dashboard.css">';
}include_css();

if (isset($_POST['submit-payment'])) {
    $patient_id = $_POST['patient_id'];
    $appointment_id = $_POST['appointment_id'];
    $amount = $_POST['amount'];

    if (isset($_FILES['upload_receipts']) && $_FILES['upload_receipts']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_name = $_FILES['upload_receipts']['tmp_name'];
        $file_name = basename($_FILES['upload_receipts']['name']);
        $upload_directory = 'uploads/Transaction Receipts/';
        $patient_directory = $upload_directory . 'Patient ID_'.$patient_id . '/';
        
        if (!file_exists($patient_directory)) {
            mkdir($patient_directory, 0777, true); 
        }
        
        $file_path = $patient_directory . $file_name;
        move_uploaded_file($file_tmp_name, $file_path);
    
        $insert_query = "INSERT INTO transactions (appointment_id, patient_id, amount, receipt) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("iiss", $appointment_id, $patient_id, $amount, $file_path);
        $stmt->execute();
        $stmt->close();
        
        echo "<script>alert('Payment submitted successfully.'); window.location='appointment.php'; </script>";
        require '../phpmailer/src/Exception.php';
        require '../phpmailer/src/PHPMailer.php';
        require '../phpmailer/src/SMTP.php';
        
        if (isset($_POST['submit-payment'])) {
            $mail = new PHPMailer(true);
        
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'abclaboratories2024@gmail.com';
            $mail->Password = 'glvxudxdfhvhvqce';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
        
            $mail->setFrom('abclaboratories2024@gmail.com'); 
            
            $email_query = "SELECT email, name FROM user_form WHERE id = ?";
            $stmt = $conn->prepare($email_query);
            $stmt->bind_param("i", $_SESSION['patient_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $user_data = $result->fetch_assoc();
            $stmt->close();
            
            if ($user_data) {
                $recipient_email = $user_data['email'];
                $recipient_name = $user_data['name'];
                $paid_amount = $_POST['amount'];
                $testType = $_POST['testType'];
                $testDate = $_POST['testDate'];
                $testTime = $_POST['testTime'];
                
                $mail->addAddress($recipient_email); 
        
                $mail->isHTML(true);
        
                $mail->Subject = 'Thank You for the Payment';
                $mail->Body = 'Dear ' . $recipient_name . ',<br><br>Your payment has been successfully submitted.<br><br>Your Appointment ID: #' . $appointment_id . '<br><br>Total Amount for Test: ' . $paid_amount . '<br><br>Your Medical Test Type: '. $testType .'<br><br>Your Appointed Test Date: '. $testDate.'<br><br>Your Appointed Test Time: '. $testTime.'<br><br>Regards,<br>ABC Medical Laboratories, Crafted By Sloveshan Dayalan (CL/BSCSD/27/78)';
        
                if ($mail->send()) {
                    echo "<script>alert('Payment submitted successfully. Email Sent Successfully');
                    window.location='appointment.php'; </script>";
                } else {
                    echo "<script>alert('Payment submitted successfully. Error: Unable to send email.');
                    window.location='appointment.php'; </script>";
                }
            } else {
                echo "<script>alert('Error: Unable to fetch user data.');
                window.location='appointment.php'; </script>";
            }
        }
    }}
    ?>







<button id="toggle_menu" class="btn btn-danger rounded"><span aria-hidden="true">Toggle Panel</span></button>
<div class="dashboard_sidebar hide" id="dashboard_sidebar">
    <h1>Patient Panel</h1>
    <ul>
    <li><a href="patient_page.php">Dashboard</a></li>
        <li ><a href="profile.php">Medical Profile</a></li>
        <li class="active_li"><a href="appointment.php">Appointments</a></li>
        <li><a href="transactions.php">Transactions</a></li>
        <li><a href="tests.php">Tests</a></li>
        <li ><a href="edit_profile.php">Edit Profile</a></li>
        <li><a href="change_password.php">Change Password</a></li>
    </ul>
</div>

<div class="dashboard_content" id="dashboard_content">
    <div class="dashboard_header">
        <h2 class="h5">Upload<span class="text-danger"> Receipt</span></h2>
        <div>
            <a href="appointment.php" class="btn btn-secondary">👈 Back</a>
            <a href="../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <div class="col-md-12 order-md-2 mb-4">
        <?php if ($row = $result->fetch_assoc()): ?>
            <ul class="list-group mb-3">
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Appointment ID</h6>
                    </div>
                    <span class="text-muted">#<?php echo $appointment_id; ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Test Type</h6>
                    </div>
                    <span class="text-muted"><?php echo $row['test_type']; ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Test Date</h6>
                    </div>
                    <span class="text-muted"><?php echo $row['date']; ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Test Time</h6>
                    </div>
                    <span class="text-muted"><?php echo $row['time']; ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between bg-light">
                    <span class="text-danger">Total Payable Amount</span>
                    <strong class="text-danger"><?php echo $row['price']; ?></strong>
                </li>
            </ul>
        <?php else: ?>
            <p>No appointment found with the provided ID.</p>
        <?php endif; ?>
    </div>

    <div class="row mx-2">
        <div class="col-7">
            <h5 class="mt-2 text-primary">Bank Payment Details</h5>
        </div>
        <div class="col">
        <a href="checkout.php?appointment_id=<?php echo $appointment_id; ?>" class="btn btn-warning col">Switch to Card Payment</a>
        </div>
    </div>

    <div class="col-md-12 order-md-2 my-4">
        <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <h6 class="my-0">Account Holder Name</h6>
                </div>
                <span class="text-muted">ABC Laboratories</span>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <h6 class="my-0">Bank Name</h6>
                </div>
                <span class="text-muted">Commercial Bank</span>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <h6 class="my-0">Bank Branch</h6>
                </div>
                <span class="text-muted">Colombo</span>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <h6 class="my-0">Bank Code</h6>
                </div>
                <span class="text-muted">LK2004</span>
            </li>
            <li class="list-group-item d-flex justify-content-between bg-light">
                <span class="text-danger">Account Number</span>
                <strong class="text-danger">8012342344</strong>
            </li>
        </ul>
    </div>

    <form method="POST" class="list-group border shadow rounded p-3 mt-4 bg-light" enctype="multipart/form-data">
        <input type="hidden" name="patient_id" value="<?php echo $_SESSION['patient_id']; ?>">
        <input type="hidden" name="appointment_id" value="<?php echo $appointment_id; ?>">
        <input type="hidden" id="amount" name="amount" value="<?php echo $row['price']; ?>">
        <input type="hidden" name="testType" value="<?php echo $row['test_type']; ?>">
        <input type="hidden" name="testDate" value="<?php echo $row['date']; ?>">
        <input type="hidden" name="testTime" value="<?php echo $row['time']; ?>">
        <div>
            <label for="upload_receipts">Upload Receipt</label>
            <input type="file" id="upload_receipts" name="upload_receipts" accept=".pdf, .doc, .docx, .jpg, .jpeg, .png" multiple>
        </div>
        <button class="btn btn-success mt-3 btn-block" name="submit-payment" type="submit">Submit Paid Receipt</button>
    </form>
</div>
<script src="../js/admin.js"></script>
<?php include('../templates/footer.php'); ?>