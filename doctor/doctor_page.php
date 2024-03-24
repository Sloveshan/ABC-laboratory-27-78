<?php
session_start();
@include '../config.php';

if (!isset($_SESSION['doctor_id'])) {
   header('location:../../login_form.php');
   exit;
}

$doctor_id = $_SESSION['doctor_id'];
$query = "SELECT * FROM user_form WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $doctor_details = $result->fetch_assoc();
} else {
    echo "Error: Patient details not found.";
    exit;
}

$pageTitle = 'Doctor Details';

include('../templates/header.php');

function include_css() {
    echo '<link rel="stylesheet" type="text/css" href="../css/dashboard.css">';
}include_css();
?>



<button id="toggle_menu" class="btn btn-danger rounded"><span aria-hidden="true">Toggle Panel</span></button>
<div class="dashboard_sidebar hide" id="dashboard_sidebar" >
    <h1>Doctor Panel</h1>
    <ul>
    <li class="active_li"><a href="doctor_page.php">Medical Profile</a></li>
        <li ><a href="appointments.php">Appointments</a></li>
        <li><a href="edit_profile.php">Edit Profile</a></li>
        <li><a href="change_password.php">Change Password</a></li>
        <li><a href="tests.php">Tests</a></li>
    </ul>
</div>
<div class="dashboard_content" id="dashboard_content">
    <div class="dashboard_header">
        <h2 class="h5">Welcome,<span class="text-danger"> Dr. <?php echo $doctor_details['name']; ?></span></h2>
        <div>
        <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
        <a href="../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
    <div class="border shadow rounded my-4 p-3 bg-light">
        <h5 class="mb-3">Functionalities as a doctor in this platform</h5>
        <small>➡️ You can view, edit & change password of your account.</small>
        <br>
        <small>➡️ You can view & approve specific appointment & download report</small>
        <br>
        <small>➡️ You can view Advanced test details & also download test report</small>
    </div>
    <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th scope="row">ID</th>
                        <td><?php echo $doctor_details['id']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Name</th>
                        <td><?php echo $doctor_details['name']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Email</th>
                        <td><?php echo $doctor_details['email']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Medical License Number</th>
                        <td><?php echo $doctor_details['medical_license_number']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Specialty</th>
                        <td><?php echo $doctor_details['doc_specialty']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Gender</th>
                        <td><?php echo $doctor_details['gender']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Date Of Birth</th>
                        <td><?php echo $doctor_details['dob']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Address Street</th>
                        <td><?php echo $doctor_details['address_street']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">City</th>
                        <td><?php echo $doctor_details['address_city']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">State</th>
                        <td><?php echo $doctor_details['address_state']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Postal Code</th>
                        <td><?php echo $doctor_details['address_postal_code']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Country</th>
                        <td><?php echo $doctor_details['address_country']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Phone Number</th>
                        <td><?php echo $doctor_details['phone_number']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">NIC</th>
                        <td><?php echo $doctor_details['nic']; ?></td>
                    </tr>
                </tbody>
            </table>
</div>
<script src="../js/admin.js"></script>
<?php include('../templates/footer.php'); ?>