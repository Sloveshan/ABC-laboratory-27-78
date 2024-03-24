<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
@include '../config.php';

if (!isset($_SESSION['patient_id'])) {
    header('location:../../login_form.php');
    exit;
}

if (!isset($_SESSION['appointment_id'])) {
    header('location:appointment.php');
    exit;
}

$appointment_id = $_SESSION['appointment_id'];

$query = "SELECT patient_id, test_type, date, time, price FROM appointments WHERE appointment_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();

$pageTitle = 'Checkout';
include('../templates/header.php');

function include_css() {
    echo '<link rel="stylesheet" type="text/css" href="../css/dashboard.css">';
}
include_css();

if (isset($_POST['submit-payment'])) {
    $patient_id = $_POST['patient_id'];
    $appointment_id = $_POST['appointment_id'];
    $amount = $_POST['amount'];
    
    if (isset($_POST['save_card'])) {
        $card_name = $_POST['card_name'];
        $card_number = $_POST['card_number'];
        $card_exp = $_POST['card_exp'];
        $card_cvv = $_POST['card_cvv'];
    } else {
        $card_name = $card_number = $card_exp = $card_cvv = ""; 
    }
    
    $insert_query = "INSERT INTO transactions (appointment_id, patient_id, amount, card_name, card_number, card_exp, card_cvv) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("iissiii", $appointment_id, $patient_id, $amount, $card_name, $card_number, $card_exp, $card_cvv);
    $stmt->execute();
    $stmt->close();
    
    echo "<script>alert('Payment submitted successfully. Email Sent Successfully'); window.location='appointment.php'; </script>";
    
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
}
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
        <h2 class="h5">Payment<span class="text-danger"> Checkout</span></h2>
        <div>
            <a href="appointment.php" class="btn btn-secondary">👈 Back</a>
            <a href="../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <div class="col-md-12 order-md-2 mb-4">
        <ul class="list-group mb-3">
            <?php while ($row = $result->fetch_assoc()): ?>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Appointment ID</h6>
                    </div>
                    <span class="text-muted">#<?php echo $appointment_id ?></span>
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
    </div>

    <div class="row mx-2">
        <div class="col-7">
            <h5 class="mt-2 text-primary">Payment Method Credit/Debit Card</h5>
        </div>
        <div class="col">
        <a href="upload_receipt.php?appointment_id=<?php echo $appointment_id; ?>" class="btn btn-warning col">Switch to Upload Paid Receipt</a>
        </div>
    </div>

    <form method="POST" class="list-group border shadow rounded mt-4 p-3 bg-light">
        <input type="hidden" name="patient_id" value="<?php echo $row['patient_id']; ?>">
        <input type="hidden" name="appointment_id" value="<?php echo $appointment_id ?>">
        <input type="hidden" id="amount" name="amount" value="<?php echo $row['price']; ?>">
        <input type="hidden" name="testType" value="<?php echo $row['test_type']; ?>">
        <input type="hidden" name="testDate" value="<?php echo $row['date']; ?>">
        <input type="hidden" name="testTime" value="<?php echo $row['time']; ?>">

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Name on card</label>
                <input type="text" class="form-control" id="card_name" name="card_name" placeholder="Enter Full Name" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Credit card number</label>
                <input type="text" id="card_number" name="card_number" class="form-control" placeholder="4242 4242 4242 4242" minlength="16" maxlength="19" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\d{4})(?=\d)/g, '$1 ')" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label>Expiration</label>
                <input type="text" id="card_exp" name="card_exp" class="form-control" placeholder="MM/YY" minlength="4" maxlength="4" pattern="[0-9]{4}" title="Please enter a valid expiration date (MMYY)" required>
            </div>
            <div class="col-md-3 mb-3">
                <label>CVV</label>
                <input type="number" id="card_cvv" name="card_cvv" class="form-control" placeholder="Enter CVV" minlength="4" maxlength="4" pattern="[0-9]{3}" title="Please enter valid Card CVV" required>
            </div>
            <div class="col-md-6 custom_checkbox_control custom-radio mt-2">
                <input id="save_card" name="save_card" type="checkbox" class="custom_control_input" checked>
                <label class="custom_control_label">Save my Card Details</label>
            </div>
        </div>
        <button class="btn btn-success w-100" name="submit-payment" type="submit">Submit Payment</button>
    </form>
</div>
<?php endwhile; ?>
<script src="../js/admin.js"></script>
<?php include('../templates/footer.php'); ?>