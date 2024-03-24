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

$pageTitle = 'Edit Patient Details';

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
        <li><a href="transactions.php">Transactions</a></li>
        <li><a href="tests.php">Tests</a></li>
        <li class="active_li"><a href="edit_profile.php">Edit Profile</a></li>
        <li><a href="change_password.php">Change Password</a></li>
    </ul>
</div>
<div class="dashboard_content" id="dashboard_content">
    <div class="dashboard_header">
        <h2 class="h5">Edit Your <span class="text-danger">Medical Profile</span></h2>
        <div>
        <a href="patient_page.php" class="btn btn-secondary">👈 Back to Profile</a>
        <a href="../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
    <div>
    <form method="POST" action="update_profile.php">
        <input type="hidden" name="patient_id" value="<?php echo $patient_details['id']; ?>">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?php echo $patient_details['name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?php echo $patient_details['email']; ?>" required>
        </div>
        <div class="form-group">
            <label for="gender">Gender</label>
            <select name="gender" id="gender" class="form-control" required>
                <option value="Male" <?php if ($patient_details['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if ($patient_details['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                <option value="Other" <?php if ($patient_details['gender'] == 'Other') echo 'selected'; ?>>Other</option>
            </select>
        </div>
        <div class="form-group">
            <label for="dob">Date of Birth</label>
            <input type="date" name="dob" id="dob" class="form-control" value="<?php echo $patient_details['dob']; ?>" required>
        </div>



        <div class="form-group">
                <label for="address_street">Street Address</label>
                <input type="text" name="address_street" id="address_street" class="form-control" value="<?php echo !empty($patient_details['address_street']) ? $patient_details['address_street'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="address_city">City</label>
                <input type="text" name="address_city" id="address_city" class="form-control" value="<?php echo !empty($patient_details['address_city']) ? $patient_details['address_city'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="address_state">State/Province</label>
                <input type="text" name="address_state" id="address_state" class="form-control" value="<?php echo !empty($patient_details['address_state']) ? $patient_details['address_state'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="address_postal_code">Postal Code</label>
                <input type="text" name="address_postal_code" id="address_postal_code" class="form-control" value="<?php echo !empty($patient_details['address_postal_code']) ? $patient_details['address_postal_code'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="address_country">Country</label>
                <input type="text" name="address_country" id="address_country" class="form-control" value="<?php echo !empty($patient_details['address_country']) ? $patient_details['address_country'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control" value="<?php echo !empty($patient_details['phone_number']) ? $patient_details['phone_number'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="nic">National ID Number (NIC)</label>
                <input type="text" name="nic" id="nic" class="form-control" value="<?php echo !empty($patient_details['nic']) ? $patient_details['nic'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="medical_conditions">Medical Conditions</label>
                <textarea name="medical_conditions" id="medical_conditions" class="form-control"><?php echo !empty($patient_details['medical_conditions']) ? $patient_details['medical_conditions'] : ''; ?></textarea>
            </div>
            <div class="form-group">
    <label for="allergies">Allergies</label>
    <textarea name="allergies" id="allergies" class="form-control"><?php echo !empty($patient_details['allergies']) ? $patient_details['allergies'] : ''; ?></textarea>
</div>
<div class="form-group">
    <label for="medications">Medications</label>
    <textarea name="medications" id="medications" class="form-control"><?php echo !empty($patient_details['medications']) ? $patient_details['medications'] : ''; ?></textarea>
</div>
<div class="form-group">
    <label for="previous_procedures">Previous Medical Procedures</label>
    <textarea name="previous_procedures" id="previous_procedures" class="form-control"><?php echo !empty($patient_details['previous_procedures']) ? $patient_details['previous_procedures'] : ''; ?></textarea>
</div>
<div class="form-group">
    <label for="family_medical_history">Family Medical History</label>
    <textarea name="family_medical_history" id="family_medical_history" class="form-control"><?php echo !empty($patient_details['family_medical_history']) ? $patient_details['family_medical_history'] : ''; ?></textarea>
</div>
<div class="form-group">
    <label for="emergency_contact_name">Emergency Contact Name <span class="text-danger">*</span></label>
    <input type="text" name="emergency_contact_name" id="emergency_contact_name" class="form-control" value="<?php echo !empty($patient_details['emergency_contact_name']) ? $patient_details['emergency_contact_name'] : ''; ?>">
</div>
<div class="form-group">
    <label for="emergency_contact_relationship">Relationship to Patient <span class="text-danger">*</span></label>
    <input type="text" name="emergency_contact_relationship" id="emergency_contact_relationship" class="form-control" value="<?php echo !empty($patient_details['emergency_contact_relationship']) ? $patient_details['emergency_contact_relationship'] : ''; ?>">
</div>
<div class="form-group">
    <label for="emergency_contact_phone">Emergency Contact Phone <span class="text-danger">*</span></label>
    <input type="text" name="emergency_contact_phone" id="emergency_contact_phone" class="form-control" value="<?php echo !empty($patient_details['emergency_contact_phone']) ? $patient_details['emergency_contact_phone'] : ''; ?>">
</div>
        <button type="submit" class="btn btn-primary mt-3">Update</button>
        <a href="patient_page.php" class="btn btn-secondary mt-3">Cancel</a>
    </form>
    </div>
</div>
<script src="../js/admin.js"></script>
<?php include('../templates/footer.php'); ?>