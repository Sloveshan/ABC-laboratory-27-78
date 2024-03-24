<?php
session_start();
@include '../config.php';

if (!isset($_SESSION['patient_id'])) {
   header('location:../login_form.php');
   exit;
}

$patient_id = $_SESSION['patient_id'];

$query = "SELECT t.*, a.patient_id FROM transactions t INNER JOIN appointments a ON t.appointment_id = a.appointment_id WHERE a.patient_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

$pageTitle = 'All Appointments';
include('../templates/header.php');

function include_css() {
    echo '<link rel="stylesheet" type="text/css" href="../css/dashboard.css">';
}include_css();
?>

<button id="toggle_menu" class="btn btn-danger rounded"><span aria-hidden="true">Toggle Panel</span></button>
<div class="dashboard_sidebar hide" id="dashboard_sidebar" >
    <h1>Patient Panel</h1>
    <ul>
    <li><a href="patient_page.php">Dashboard</a></li>
        <li ><a href="profile.php">Medical Profile</a></li>
        <li ><a href="appointment.php">Appointments</a></li>
        <li class="active_li"><a href="transactions.php">Transactions</a></li>
        <li><a href="tests.php">Tests</a></li>
        <li ><a href="edit_profile.php">Edit Profile</a></li>
        <li><a href="change_password.php">Change Password</a></li>
    </ul>
</div>
<div class="dashboard_content" id="dashboard_content">
    <div class="dashboard_header">
        <h2 class="h5">View <span class="text-danger">Transactions</span></h2>
        <div>
            <a href="../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <div class="appointments_list">
        <?php if ($result->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Appointment ID</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Download Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td>#<?php echo $row['appointment_id']; ?></td>
                            <td><?php echo $row['amount']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <a href="download_report.php?transaction_id=<?php echo $row['id']; ?>" class="btn btn-primary">Download Receipt</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No Transactions found.</p>
        <?php endif; ?>
    </div>
</div>
<script src="../js/admin.js"></script>
<?php include('../templates/footer.php'); ?>