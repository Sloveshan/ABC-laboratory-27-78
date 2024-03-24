<?php
session_start();
@include '../config.php';

if (!isset($_SESSION['patient_id'])) {
   header('location:../../login_form.php');
   exit;
}

$patient_id = $_SESSION['patient_id'];
$query = "SELECT * FROM user_form WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $patient_details = $result->fetch_assoc();
} else {
    echo "Error: Patient details not found.";
    exit;
}

$pageTitle = 'Patient Details';

include('../templates/header.php');

function include_css() {
    echo '<link rel="stylesheet" type="text/css" href="../css/dashboard.css">';
}include_css();
?>



<button id="toggle_menu" class="btn btn-danger rounded"><span aria-hidden="true">Toggle Panel</span></button>
<div class="dashboard_sidebar hide" id="dashboard_sidebar" >
    <h1>Patient Panel</h1>
    <ul>
    <li class="active_li"><a href="patient_page.php">Dashboard</a></li>
        <li ><a href="profile.php">Medical Profile</a></li>
        <li ><a href="appointment.php">Appointments</a></li>
        <li><a href="transactions.php">Transactions</a></li>
        <li><a href="tests.php">Tests</a></li>
        <li><a href="edit_profile.php">Edit Profile</a></li>
        <li><a href="change_password.php">Change Password</a></li>
    </ul>
</div>
<div class="dashboard_content" id="dashboard_content">
    <div class="dashboard_header">
        <h2 class="h5">Welcome,<span class="text-danger"> <?php echo $patient_details['name']; ?></span></h2>
        <div>
        <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
        <a href="../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <div class="border shadow rounded my-4 p-3 bg-light">
        <h5 class="mb-3">Functionalities as a patient in this platform</h5>
        <small>➡️ You can view, edit & change password of your account.</small>
        <br>
        <small>➡️ You can create unlimited new appointments or cancel appointment.</small>
        <br>
        <small>➡️ You can make payments using credit/debit card or you can upload paid receipt.</small>
        <br>
        <small>➡️ You can view your transactions & download receipt</small>
        <br>
        <small>➡️ You can view your test completed results & download report</small>
    </div>
    <div class="border shadow rounded my-3 p-3 bg-light">
        <small>Please update your medical profile with valid details to receive a accurate medical report 💯</small>
    </div>
    <a href="create_appointment.php" class="dash_card bg-success text-center border shadow text-light">
    <h5 >New Appointment</h5>
    <i style="font-size:30px; " class="fa fa-plus-circle" aria-hidden="true"></i>
    </a>
    <a href="tests.php" class="dash_card bg-danger text-center border shadow text-light">
    <h5 >View Test Results</h5>
    <i style="font-size:30px; " class="fa fa-heartbeat" aria-hidden="true"></i>
    </a>
    <a href="tests.php" class="dash_card bg-warning text-center border shadow text-dark">
    <h5 >Download Report</h5>
    <i style="font-size:30px; " class="fa fa-medkit" aria-hidden="true"></i>
    </a>
</div>
<script src="../js/admin.js"></script>
<?php include('../templates/footer.php'); ?>