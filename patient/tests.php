<?php
session_start();
@include '../config.php';

if (!isset($_SESSION['patient_id'])) {
   header('location:../../login_form.php');
   exit;
}

$patient_id = (int)$_SESSION['patient_id'];

$query = "SELECT a.appointment_id, a.test_type, a.date, COUNT(t.appointment_id) AS test_count 
          FROM appointments a 
          LEFT JOIN tests t ON a.appointment_id = t.appointment_id 
          WHERE a.appointment_status = 'Appointed' 
          AND a.patient_id = $patient_id 
          GROUP BY a.appointment_id";
$result = $conn->query($query);

$pageTitle = 'Your Tests';
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
        <li ><a href="transactions.php">Transactions</a></li>
        <li class="active_li"><a href="tests.php">Tests</a></li>
        <li ><a href="edit_profile.php">Edit Profile</a></li>
        <li><a href="change_password.php">Change Password</a></li>
    </ul>
</div>
<div class="dashboard_content" id="dashboard_content">
    <div class="dashboard_header">
        <h2 class="h5">Your Appointed<span class="text-danger"> Tests</span></h2>
        <div>
        <a href="../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <div class="appointments_list">
        <?php if ($result->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Appointment ID</th>
                        <th>Test Date</th>
                        <th>Test Type</th>
                        <th>More Info</th>
                        <th>Technician Update</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $row['appointment_id']; ?></td>
                            <td><?php echo $row['date']; ?></td>
                            <td><?php echo $row['test_type']; ?></td>
                            <td><a href="single_test.php?appointment_id=<?php echo $row['appointment_id']; ?>" class="btn btn-primary">More Info</a></td>
                            <td>
                                <?php if ($row['test_count'] > 0): ?>
                                    <button class="btn btn-success" disabled>Results Available</button>
                                <?php else: ?>
                                    <button class="btn btn-secondary" disabled>No Result Updated</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No appointed test found.</p>
        <?php endif; ?>
    </div>
</div>
<script src="../js/admin.js"></script>
<?php include('../templates/footer.php'); ?>