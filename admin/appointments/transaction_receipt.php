<?php
session_start();
@include '../../config.php';

if (!isset($_SESSION['admin_name'])) {
    header('location:../login_form.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('location:transactions.php');
    exit;
}

$transaction_id = $_GET['id'];

$query = "SELECT * FROM transactions WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $transaction_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header('location:transactions.php');
    exit;
}

$transaction = $result->fetch_assoc();

$heading = "ABC Laboratory | Payment transaction report\n\n";

$reportContent = $heading;
$reportContent .= "Transaction ID: #" . $transaction['id'] . "\n";
$reportContent .= "Appointment ID: #" . $transaction['appointment_id'] . "\n";
$reportContent .= "Amount: " . $transaction['amount'] . "\n";
$reportContent .= "Status: " . $transaction['status'] . "\n";

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=transaction_report_" . $transaction['id'] . ".txt");

echo $reportContent;
?>
