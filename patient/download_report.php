<?php
session_start();
@include '../config.php';

if (!isset($_SESSION['patient_id'])) {
    header('location:../login_form.php');
    exit;
}

if (!isset($_GET['transaction_id'])) {
    header('location:transactions.php');
    exit;
}

$transaction_id = $_GET['transaction_id'];
$patient_id = $_SESSION['patient_id'];

$query = "SELECT * FROM transactions WHERE id = ? AND patient_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $transaction_id, $patient_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header('location:transactions.php');
    exit;
}

$transaction = $result->fetch_assoc();

$heading = "Thank you for the payment, Good luck for your medical test at ABC Laboratories\n\n";

$reportContent = $heading;
$reportContent .= "Transaction ID: #" . $transaction['id'] . "\n";
$reportContent .= "Appointment ID: #" . $transaction['appointment_id'] . "\n";
$reportContent .= "Amount: " . $transaction['amount'] . "\n";
$reportContent .= "Status: " . $transaction['status'] . "\n";

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=transaction_report_" . $transaction['id'] . ".txt");

echo $reportContent;
?>
