<?php
session_start();
@include '../config.php';

if (!isset($_SESSION['patient_id'])) {
   header('location:../login_form.php');
   exit;
}

$patient_id = $_SESSION['patient_id'];

$query = "SELECT * FROM appointments WHERE patient_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

if(isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_query = "DELETE FROM appointments WHERE appointment_id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $delete_id);
    $delete_stmt->execute();
    header("Location: appointment.php");
    exit;
}

$pageTitle = 'Patient Appointments';
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
        <li class="active_li"><a href="appointment.php">Appointments</a></li>
        <li><a href="transactions.php">Transactions</a></li>
        <li><a href="tests.php">Tests</a></li>
        <li ><a href="edit_profile.php">Edit Profile</a></li>
        <li><a href="change_password.php">Change Password</a></li>
    </ul>
</div>
<div class="dashboard_content" id="dashboard_content">
    <div class="dashboard_header">
        <h2 class="h5">Your<span class="text-danger"> Appointments</span></h2>
        <div>
        <a href="create_appointment.php" class="btn btn-primary">Create Appointment</a>
        <a href="../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <div class="appointments_list">
        <?php if ($result->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Appointment ID</th>
                        <th>Test Type</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Full Details</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['appointment_id']; ?></td>
                            <td><?php echo $row['test_type']; ?></td>
                            <td><?php echo $row['date']; ?></td>
                            <td><?php echo $row['time']; ?></td>
                            <td><?php echo $row['appointment_status']; ?></td>
                            <td><a href="single_appointment.php?appointment_id=<?php echo $row['appointment_id']; ?>" class="btn btn-primary">More Info</a></td>
                            <td>
                                <?php
                                ob_start();
                                $check_query = "SELECT * FROM transactions WHERE appointment_id = ?";
                                $check_stmt = $conn->prepare($check_query);
                                $check_stmt->bind_param("i", $row['appointment_id']);
                                $check_stmt->execute();
                                $check_result = $check_stmt->get_result();
                                
                                if ($check_result->num_rows > 0) {
                                    if ($row['payment_status'] == 'Pending Payment') {
                                        $update_query = "UPDATE appointments SET payment_status = 'Processing Payment' WHERE appointment_id = ?";
                                        $update_stmt = $conn->prepare($update_query);
                                        $update_stmt->bind_param("i", $row['appointment_id']);
                                        $update_stmt->execute();
                                        ob_end_clean(); 
                                        header("Location: appointment.php");
                                        exit; 
                                    } elseif ($row['payment_status'] == 'Processing Payment') {
                                        echo '<button class="btn btn-secondary" disabled>Processing Payment</button>';
                                    }elseif ($row['payment_status'] == 'Paid') {
                                        echo '<button class="btn btn-success" disabled>Payment Successful</button>';
                                    }
                                } elseif ($row['doctor_availability'] == 'yes' && $row['technician_availability'] == 'yes') {
                                    $_SESSION['appointment_id'] = $row['appointment_id'];
                                    echo '<a href="checkout.php" class="btn btn-warning">Proceed Payment</a>';
                                } else {
                                    echo '<a href="?delete='.$row['appointment_id'].'" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this Appointment?\')">Cancel Appointment</a>';
                                }
                                ?>
                            </td>

                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No appointments Made.</p>
        <?php endif; ?>
    </div>
</div>
<script src="../js/admin.js"></script>
<?php include('../templates/footer.php'); ?>
