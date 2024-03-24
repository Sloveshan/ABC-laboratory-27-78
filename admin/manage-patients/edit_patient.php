<?php
@include '../../config.php';

session_start();

if (!isset($_SESSION['admin_name'])) {
    header('location:../../login_form.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $patient_id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM user_form WHERE id = '$patient_id' AND user_type = 'patient'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $patient = mysqli_fetch_assoc($result);
    } else {
        header('location:manage_patients.php?error=Patient not found.');
        exit;
    }
} else {
    header('location:manage_patients.php?error=Invalid request.');
    exit;
}

$pageTitle = 'Edit Patient';

include('../../templates/header.php');

function include_css() {
    echo '<link rel="stylesheet" type="text/css" href="../../css/dashboard.css">';
}include_css();
?>

<button id="toggle_menu" class="btn btn-danger rounded"><span aria-hidden="true">Toggle Panel</span></button>

<div class="dashboard_sidebar hide" id="dashboard_sidebar">
    <h1>Admin Panel</h1>
    <ul>
        <li><a href="../admin_dashboard.php">Dashboard</a></li>
        <li  class="active_li"><a href="manage_patients.php">Patients</a></li>
        <li><a href="../manage-doctors/manage_doctor.php">Doctors</a></li>
        <li><a href="../manage-technicians/manage_technicians.php">Technician</a></li>
        <li><a href="../manage-admins/manage_admins.php">Administrators</a></li>
        <li><a href="../appointments/appointment.php">Appointments</a></li>
        <li><a href="../appointments/transactions.php">Transactions</a></li>

    </ul>
</div>

<div class="dashboard_content" id="dashboard_content">
    <div class="dashboard_header" style="display: flex; justify-content: space-between;">
        <h2 class="h5">Edit <span class="text-danger">Patient</span> Details</h2>
        <div>
            <a href="manage_patients.php" class="btn btn-secondary">👈 Back to All Patients</a>
            <a href="../../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <div class="main_content_shadow">
        <form method="POST" action="update_patient.php">
            <input type="hidden" name="patient_id" value="<?php echo $patient['id']; ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo $patient['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo $patient['email']; ?>" required>
            </div>
            <div class="form-group">
    <label for="gender">Gender</label>
    <select name="gender" id="gender" class="form-control" required>
        <option value="">Select Gender</option>
        <option value="Male" <?php echo ($patient['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
        <option value="Female" <?php echo ($patient['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
        <option value="Other" <?php echo ($patient['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
    </select>
</div>

            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" name="dob" id="dob" class="form-control" value="<?php echo !empty($patient['dob']) ? $patient['dob'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="address_street">Street Address</label>
                <input type="text" name="address_street" id="address_street" class="form-control" value="<?php echo !empty($patient['address_street']) ? $patient['address_street'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="address_city">City</label>
                <input type="text" name="address_city" id="address_city" class="form-control" value="<?php echo !empty($patient['address_city']) ? $patient['address_city'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="address_state">State/Province</label>
                <input type="text" name="address_state" id="address_state" class="form-control" value="<?php echo !empty($patient['address_state']) ? $patient['address_state'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="address_postal_code">Postal Code</label>
                <input type="text" name="address_postal_code" id="address_postal_code" class="form-control" value="<?php echo !empty($patient['address_postal_code']) ? $patient['address_postal_code'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="address_country">Country</label>
                <input type="text" name="address_country" id="address_country" class="form-control" value="<?php echo !empty($patient['address_country']) ? $patient['address_country'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control" value="<?php echo !empty($patient['phone_number']) ? $patient['phone_number'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="nic">National ID Number (NIC)</label>
                <input type="text" name="nic" id="nic" class="form-control" value="<?php echo !empty($patient['nic']) ? $patient['nic'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="medical_conditions">Medical Conditions</label>
                <textarea name="medical_conditions" id="medical_conditions" class="form-control"><?php echo !empty($patient['medical_conditions']) ? $patient['medical_conditions'] : ''; ?></textarea>
            </div>
            <div class="form-group">
    <label for="allergies">Allergies</label>
    <textarea name="allergies" id="allergies" class="form-control"><?php echo !empty($patient['allergies']) ? $patient['allergies'] : ''; ?></textarea>
</div>
<div class="form-group">
    <label for="medications">Medications</label>
    <textarea name="medications" id="medications" class="form-control"><?php echo !empty($patient['medications']) ? $patient['medications'] : ''; ?></textarea>
</div>
<div class="form-group">
    <label for="previous_procedures">Previous Medical Procedures</label>
    <textarea name="previous_procedures" id="previous_procedures" class="form-control"><?php echo !empty($patient['previous_procedures']) ? $patient['previous_procedures'] : ''; ?></textarea>
</div>
<div class="form-group">
    <label for="family_medical_history">Family Medical History</label>
    <textarea name="family_medical_history" id="family_medical_history" class="form-control"><?php echo !empty($patient['family_medical_history']) ? $patient['family_medical_history'] : ''; ?></textarea>
</div>
<div class="form-group">
    <label for="emergency_contact_name">Emergency Contact Name <span class="text-danger">*</span></label>
    <input type="text" name="emergency_contact_name" id="emergency_contact_name" class="form-control" value="<?php echo !empty($patient['emergency_contact_name']) ? $patient['emergency_contact_name'] : ''; ?>">
</div>
<div class="form-group">
    <label for="emergency_contact_relationship">Relationship to Patient <span class="text-danger">*</span></label>
    <input type="text" name="emergency_contact_relationship" id="emergency_contact_relationship" class="form-control" value="<?php echo !empty($patient['emergency_contact_relationship']) ? $patient['emergency_contact_relationship'] : ''; ?>">
</div>
<div class="form-group">
    <label for="emergency_contact_phone">Emergency Contact Phone <span class="text-danger">*</span></label>
    <input type="text" name="emergency_contact_phone" id="emergency_contact_phone" class="form-control" value="<?php echo !empty($patient['emergency_contact_phone']) ? $patient['emergency_contact_phone'] : ''; ?>">
</div>
<div class="input-group border rounded my-4">
            <input type="password" name="password" id="password" class="form-control no-border p-2" placeholder="Enter password" autocomplete="on">
            <button class="btn no-border" type="button" id="togglePassword">
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
            </button>
        </div>
        <div class="input-group border rounded">
            <input type="password" name="cpassword" id="confirmPassword" class="form-control no-border p-2" placeholder="Confirm password" autocomplete="on">
            <button class="btn no-border" type="button" id="toggleConfirmPassword">
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
            </button>
        </div>
            <button type="submit" class="btn btn-primary mt-3">Update</button>
            <a href="manage_patients.php" class="btn btn-secondary mt-3">Cancel</a>
        </form>
    </div>
</div>

<script src="../../js/admin.js"></script>
<?php include('../../templates/footer.php'); ?>

