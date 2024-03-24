<?php
session_start();
@include '../../config.php';

if (!isset($_SESSION['admin_name'])) {
   header('location:../../login_form.php');
   exit;
}

$query = "SELECT * FROM appointments";
$result = $conn->query($query);

$pageTitle = 'All Appointments';
include('../../templates/header.php');

function include_css() {
    echo '<link rel="stylesheet" type="text/css" href="../../css/dashboard.css">';
}include_css();
?>

<button id="toggle_menu" class="btn btn-danger rounded"><span aria-hidden="true">Toggle Panel</span></button>
<div class="dashboard_sidebar hide" id="dashboard_sidebar" >
    <h1>Admin Panel</h1>
    <ul>
        <li><a href="../admin_dashboard.php">Dashboard</a></li>
        <li><a href="../manage-patients/manage_patients.php">Patients</a></li>
        <li><a href="../manage-doctors/manage_doctor.php">Doctors</a></li>
        <li><a href="../manage-technicians/manage_technicians.php">Technician</a></li>
        <li><a href="../manage-admins/manage_admins.php">Administrators</a></li>
        <li class="active_li"><a href="appointment.php">Appointments</a></li>
        <li><a href="transactions.php">Transactions</a></li>
    </ul>
</div>
<div class="dashboard_content" id="dashboard_content">
    <div class="dashboard_header">
        <h2 class="h5">View <span class="text-danger"> Appointments</span></h2>
        <div>
            <a href="../../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <div class="appointments_list">
        <?php if ($result->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Test Type</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Payment Status</th>
                        <th>Full Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['appointment_id']; ?></td>
                            <td><?php echo $row['test_type']; ?></td>
                            <td><?php echo $row['date']; ?></td>
                            <td><?php echo $row['time']; ?></td>
                            <td><?php echo $row['payment_status']; ?></td>
                            <td><a href="single_appointment.php?appointment_id=<?php echo $row['appointment_id']; ?>" class="btn btn-primary">More Info</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No appointments found.</p>
        <?php endif; ?>
    </div>
</div>
<script src="../js/admin.js"></script>
<?php include('../../templates/footer.php'); ?>
