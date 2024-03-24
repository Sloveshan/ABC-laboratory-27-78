<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
@include '../../config.php';
if (!isset($_SESSION['admin_name'])) {
    header('location:../../login_form.php');
    exit;
}

require_once '../../phpmailer/src/Exception.php';
require_once '../../phpmailer/src/PHPMailer.php';
require_once '../../phpmailer/src/SMTP.php';

function updateStatus($conn, $transactionId, $status) {
    $update_query = "UPDATE transactions SET status = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("si", $status, $transactionId);

    if ($update_stmt->execute()) {
        if ($status == 'Completed') {
            $update_appointment_query = "UPDATE appointments SET appointment_status = 'Appointed', payment_status = 'Paid' WHERE appointment_id = (SELECT appointment_id FROM transactions WHERE id = ? AND status = 'Completed')";
            $update_appointment_stmt = $conn->prepare($update_appointment_query);
            $update_appointment_stmt->bind_param("i", $transactionId);
            $update_appointment_stmt->execute();
            sendConfirmationEmail($conn, $transactionId);
        }
        return 'Confirmed Successfully. Email Sent to Patient';
    } else {
        return 'Failed to update status.';
    }
}

function sendConfirmationEmail($conn, $transactionId) {
    $mail = new PHPMailer(true);

    try {
        $patient_stmt = $conn->prepare("SELECT name, email, phone_number FROM user_form WHERE id = (SELECT patient_id FROM transactions WHERE id = ?)");
        $patient_stmt->bind_param("i", $transactionId);
        $patient_stmt->execute();
        $patient_result = $patient_stmt->get_result();

        if ($patient_result->num_rows > 0) {
            $patient_row = $patient_result->fetch_assoc();
            $patient_name = $patient_row['name'];
            $patient_email = $patient_row['email'];
            $patient_phone = $patient_row['phone_number'];

            $appointment_stmt = $conn->prepare("SELECT test_type, date, time FROM appointments WHERE appointment_id = (SELECT appointment_id FROM transactions WHERE id = ?)");
            $appointment_stmt->bind_param("i", $transactionId);
            $appointment_stmt->execute();
            $appointment_result = $appointment_stmt->get_result();

            if ($appointment_result->num_rows > 0) {
                $appointment_row = $appointment_result->fetch_assoc();
                $test_type = $appointment_row['test_type'];
                $date = $appointment_row['date'];
                $time = $appointment_row['time'];

                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'abclaboratories2024@gmail.com';
                $mail->Password = 'glvxudxdfhvhvqce';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                $mail->setFrom('abclaboratories2024@gmail.com');
                $mail->addAddress($patient_email);

                $mail->isHTML(true);
                $mail->Subject = 'Your Appointment Is Confirmed Successfully';
                $mail->Body = 'Dear ' . $patient_name . ', We have received your payment, Your appointment for Medical Test ' . $test_type . ' on ' . $date . ' at ' . $time . ' has been confirmed successfully.<br><br> Regards,<br>ABC Medical Laboratories, Crafted By Sloveshan Dayalan (CL/BSCSD/27/78)';

                $mail->send();
            } else {
                return "Appointment details not found.";
            }
        } else {
            return "Patient details not found.";
        }
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['status'])) {
    $transactionId = $_POST['id'];
    $status = $_POST['status'];

    echo updateStatus($conn, $transactionId, $status);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $transactionId = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM transactions WHERE id = ?");
    $stmt->bind_param("i", $transactionId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $patientId = $row['patient_id'];

        $patient_stmt = $conn->prepare("SELECT name, email, phone_number FROM user_form WHERE id = ?");
        $patient_stmt->bind_param("i", $patientId);
        $patient_stmt->execute();
        $patient_result = $patient_stmt->get_result();

        if ($patient_result->num_rows > 0) {
            $patient_row = $patient_result->fetch_assoc();
            $patient_name = $patient_row['name'];
            $patient_email = $patient_row['email'];
            $patient_phone = $patient_row['phone_number'];
        } else {
            $patient_email = 'kokikumar2023@gmail.com';
        }
    } else {
        echo "<script>alert('Invalid request.'); window.location.href = 'appointments.php';</script>";
    }
}

$pageTitle = 'All Transactions';
include('../../templates/header.php');

function include_css() {
    echo '<link rel="stylesheet" type="text/css" href="../../css/dashboard.css">';
}
include_css();
?>




        <button id="toggle_menu" class="btn btn-danger rounded"><span aria-hidden="true">Toggle Panel</span></button>
        <div class="dashboard_sidebar hide" id="dashboard_sidebar">
            <h1>Admin Panel</h1>
            <ul>
                <li><a href="../admin_dashboard.php">Dashboard</a></li>
                <li><a href="../manage-patients/manage_patients.php">Patients</a></li>
                <li><a href="../manage-doctors/manage_doctor.php">Doctors</a></li>
                <li><a href="../manage-technicians/manage_technicians.php">Technician</a></li>
                <li><a href="../manage-admins/manage_admins.php">Administrators</a></li>
                <li><a href="appointment.php">Appointments</a></li>
                <li class="active_li"><a href="transactions.php">Transactions</a></li>
            </ul>
        </div>
        <div class="dashboard_content" id="dashboard_content">
            <div class="dashboard_header">
                <h2 class="h5">Manage Transaction ID: <span class="text-danger">#<?php echo $row['id']; ?></span></h2>
                <div>
                    <a href="transactions.php" class="btn btn-secondary">👈 Back</a>
                    <a href="../../logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th scope="row">Transaction ID</th>
                        <td>#<?php echo $row['id']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Appointment ID</th>
                        <td>#<?php echo $row['appointment_id']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Patient ID</th>
                        <td>#<?php echo $patientId; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Patient Name</th>
                        <td><?php echo $patient_name; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Patient Email</th>
                        <td><?php echo $patient_email; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Patient Phone Number</th>
                        <td><?php echo $patient_phone; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Amount Paid</th>
                        <td><?php echo $row['amount']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Status</th>
                        <td class="text-primary"><?php echo $row['status']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Transaction was created on</th>
                        <td><?php echo $row['transaction_timestamp']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Patient's Receipt</th>
                        <td>
                            <?php
                            if (!empty($row['receipt'])) {
                                $filename = basename($row['receipt']);
                                $file_path = '../../patient/uploads/Transaction Receipts/Patient ID_' . $patientId . '/' . $filename;
                                if (file_exists($file_path)) {
                                    echo '<a href="' . $file_path . '" download>' . $filename . '</a><br>';
                                } else {
                                    echo 'Error: File not found<br>';
                                }
                            } else {
                                echo 'No receipt uploaded';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Card Name</th>
                        <td>
                            <?php 
                            if ($row['card_name'] == 'Not Saved') {
                                echo "Not Saved";
                            } else {
                                echo $row['card_name'];
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Card Number</th>
                        <td>
                            <?php 
                            if ($row['card_number'] == '0') {
                                echo "Not Saved";
                            } else {
                                echo $row['card_number'];
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Card Expiration</th>
                        <td>
                            <?php 
                            if ($row['card_exp'] == '0') {
                                echo "Not Saved";
                            } else {
                                echo $row['card_exp'];
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Card CVV</th>
                        <td>
                            <?php 
                            if ($row['card_cvv'] == '0') {
                                echo "Not Saved";
                            } else {
                                echo $row['card_cvv'];
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>TAKE ACTION</th>
                        <td>
                            <?php if ($row['status'] != 'Completed') {
                                echo '<button id="confirm_appointment" class="btn btn-success confirm-payment" data-transaction-id="' . $row['id'] . '">Confirm Payment & Appointment</button>';
                            } else {
                                echo '<button class="btn btn-success" disabled>Confirmed</button>';
                            } ?>
                            <?php if ($row['status'] != 'Completed'): ?>
                                <button class="btn btn-danger decline-payment" data-transaction-id="<?php echo $row['id']; ?>">Decline Payment</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $(".confirm-payment").click(function() {
        var transactionId = $(this).data('transaction-id');
        $(this).html('Please wait...');

        updateStatusAndSendEmail(transactionId, 'Completed');
    });

    $(".decline-payment").click(function() {
        var transactionId = $(this).data('transaction-id');
        $(this).html('Please wait...');

        updateStatus(transactionId, 'Declined');
    });

    function updateStatusAndSendEmail(transactionId, status) {
        $.ajax({
            type: 'POST',
            url: '<?php echo $_SERVER['PHP_SELF']; ?>',
            data: {
                id: transactionId,
                status: status
            },
            success: function(response) {
                alert(response);
                if (response === 'Confirmed Successfully. Email Sent to Patient') {
                    window.location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('An error occurred while updating the status.');
            }
        });
    }
});


</script>
<script src="../../js/admin.js"></script>
<?php include('../../templates/footer.php'); ?>
