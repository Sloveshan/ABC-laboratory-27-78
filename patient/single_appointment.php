<?php
session_start();
@include '../config.php';

if (!isset($_SESSION['patient_id'])) {
    header('location:../../login_form.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['appointment_id'])) {
    $appointmentId = $_GET['appointment_id'];

    $stmt = $conn->prepare("SELECT * FROM appointments WHERE appointment_id = ?");
    $stmt->bind_param("i", $appointmentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $patient_id = $row['patient_id'];

    $pageTitle = 'Appointment Details';
    include('../templates/header.php');

    function include_css() {
        echo '<link rel="stylesheet" type="text/css" href="../css/dashboard.css">';
    }include_css();
?>
    <button id="toggle_menu" class="btn btn-danger rounded"><span aria-hidden="true">Toggle Panel</span></button>
    <div class="dashboard_sidebar hide" id="dashboard_sidebar">
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
            <h2 class="h5">Appointment ID: <span class="text-danger"><?php echo $row['appointment_id']; ?></span></h2>
            <div>
                <a href="appointment.php" class="btn btn-secondary">👈 Back</a>
                <a href="../logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th scope="row">Appointment ID</th>
                    <td><?php echo $row['appointment_id']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Doctor Status</th>
                    <td>
                        <?php 
                        if ($row['doctor_availability'] == 'yes') {
                            echo "Available for the test ✅";
                        } else {
                            echo "Doctor hasn't confirmed ❌";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Technician Status</th>
                    <td>
                        <?php 
                        if ($row['technician_availability'] == 'yes') {
                            echo "Available for the test ✅";
                        } else {
                            echo "Technician hasn't confirmed ❌";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Total Amount</th>
                    <td><?php echo $row['price']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Payment Status</th>
                    <td><?php echo $row['payment_status']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Appointment Status</th>
                    <td><?php echo $row['appointment_status']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Test Type</th>
                    <td><?php echo $row['test_type']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Date Of Medical Test</th>
                    <td><?php echo $row['date']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Time Of Medical Test</th>
                    <td><?php echo $row['time']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Submission of your previous medical documents</th>
                    <td>
                        <?php 
                        if (!empty($row['patient_prev_reports'])) {
                            $uploaded_files = unserialize($row['patient_prev_reports']);
                            if (!empty($uploaded_files)) {
                                foreach ($uploaded_files as $file) {
                                    $filename = basename($file);
                                    $file_path = 'uploads/Patient Previous Medical Documents/Patient ID_' . $patient_id . '/' . $filename; // Add a directory separator here
                                    if (file_exists($file_path)) {
                                        echo '<a href="' . $file_path . '" download>' . $filename . '</a><br>';
                                    } else {
                                        echo 'Error: File not found<br>';
                                    }
                                }
                            } else {
                                echo 'No documents uploaded';
                            }
                        } else {
                            echo 'No documents uploaded';
                        }
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="border shadow rounded mt-4 p-3 bg-light">
        <?php 
                        if ($row['appointment_status'] == 'Appointed') {
                            echo "<h5>Your Appointment has been confirmed ✅</h5><small class='text-muted'>You can proceed with your medical test on your chosen date and time</small>";
                        } else {
                            echo "<h5>Your Appointment is not yet confirmed 🔃</h5> <small class='text-muted'>➡️ Please wait till doctor & technician approve your appointment</small> <br><small class='text-muted'>➡️ After payment please wait till the admin confirms transaction</small>";
                        }
                        ?>
        </div>
    </div>
<?php
}
?>
<script src="../js/admin.js"></script>
<?php include('../templates/footer.php'); ?>