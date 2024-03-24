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
    <li><a href="patient_page.php">Dashboard</a></li>
        <li class="active_li"><a href="profile.php">Medical Profile</a></li>
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
        <small>Please update your medical profile with valid details to receive a accurate medical report 💯</small>
    </div>
    <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th scope="row">ID</th>
                        <td><?php echo $patient_details['id']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Name</th>
                        <td><?php echo $patient_details['name']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Email</th>
                        <td><?php echo $patient_details['email']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Gender</th>
                        <td><?php echo $patient_details['gender']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Date Of Birth</th>
                        <td><?php echo $patient_details['dob']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Address Street</th>
                        <td><?php echo $patient_details['address_street']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">City</th>
                        <td><?php echo $patient_details['address_city']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">State</th>
                        <td><?php echo $patient_details['address_state']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Postal Code</th>
                        <td><?php echo $patient_details['address_postal_code']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Country</th>
                        <td><?php echo $patient_details['address_country']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Phone Number</th>
                        <td><?php echo $patient_details['phone_number']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">NIC</th>
                        <td><?php echo $patient_details['nic']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Medical Conditions</th>
                        <td><?php echo $patient_details['medical_conditions']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Allergies</th>
                        <td><?php echo $patient_details['allergies']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Medications</th>
                        <td><?php echo $patient_details['medications']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Previous Medical Procedures</th>
                        <td><?php echo $patient_details['previous_procedures']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Family Medical History</th>
                        <td><?php echo $patient_details['family_medical_history']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Emergency Contact Name</th>
                        <td><?php echo $patient_details['emergency_contact_name']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Relationship to Patient</th>
                        <td><?php echo $patient_details['emergency_contact_relationship']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Emergency Contact Number</th>
                        <td><?php echo $patient_details['emergency_contact_phone']; ?></td>
                    </tr>
                </tbody>
            </table>
</div>
<script src="../js/admin.js"></script>
<?php include('../templates/footer.php'); ?>