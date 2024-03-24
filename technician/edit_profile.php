<?php
session_start();
@include '../config.php';

if (!isset($_SESSION['technician_id'])) {
    header('location:../../login_form.php');
    exit;
}

$technician_id = $_SESSION['technician_id'];
$query = "SELECT * FROM user_form WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $technician_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $technician_details = $result->fetch_assoc();
} else {
    echo "Error: Technician details not found.";
    exit;
}

$pageTitle = 'Edit Technician Details';

include('../templates/header.php');

function include_css() {
    echo '<link rel="stylesheet" type="text/css" href="../css/dashboard.css">';
}include_css();
?>
<button id="toggle_menu" class="btn btn-danger rounded"><span aria-hidden="true">Toggle Panel</span></button>
<div class="dashboard_sidebar hide" id="dashboard_sidebar" >
    <h1>Technician Panel</h1>
    <ul>
    <li><a href="technician_page.php">Technical Profile</a></li>
        <li ><a href="appointments.php">Appointments</a></li>
        <li class="active_li"><a href="edit_profile.php">Edit Profile</a></li>
        <li><a href="change_password.php">Change Password</a></li>
        <li><a href="tests/tests.php">Tests</a></li>
    </ul>
</div>
<div class="dashboard_content" id="dashboard_content">
    <div class="dashboard_header">
        <h2 class="h5">Edit Your <span class="text-danger">Technical Profile</span></h2>
        <div>
        <a href="technician_page.php" class="btn btn-secondary">👈 Back to Profile</a>
        <a href="../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
    <div>
    <form method="POST" action="update_profile.php">
        <input type="hidden" name="technician_id" value="<?php echo $technician_details['id']; ?>">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?php echo $technician_details['name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?php echo $technician_details['email']; ?>" required>
        </div>
        <div class="form-group">
                <label for="technician_specialization">Specialization</label>
                <input type="text" name="technician_specialization" id="technician_specialization" class="form-control" value="<?php echo $technician_details['technician_specialization']; ?>" required>
            </div>
        <div class="form-group">
            <label for="gender">Gender</label>
            <select name="gender" id="gender" class="form-control" required>
                <option value="Male" <?php if ($technician_details['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if ($technician_details['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                <option value="Other" <?php if ($technician_details['gender'] == 'Other') echo 'selected'; ?>>Other</option>
            </select>
        </div>
        <div class="form-group">
            <label for="dob">Date of Birth</label>
            <input type="date" name="dob" id="dob" class="form-control" value="<?php echo $technician_details['dob']; ?>" required>
        </div>
        <div class="form-group">
                <label for="address_street">Street Address</label>
                <input type="text" name="address_street" id="address_street" class="form-control" value="<?php echo !empty($technician_details['address_street']) ? $technician_details['address_street'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="address_city">City</label>
                <input type="text" name="address_city" id="address_city" class="form-control" value="<?php echo !empty($technician_details['address_city']) ? $technician_details['address_city'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="address_state">State/Province</label>
                <input type="text" name="address_state" id="address_state" class="form-control" value="<?php echo !empty($technician_details['address_state']) ? $technician_details['address_state'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="address_postal_code">Postal Code</label>
                <input type="text" name="address_postal_code" id="address_postal_code" class="form-control" value="<?php echo !empty($technician_details['address_postal_code']) ? $technician_details['address_postal_code'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="address_country">Country</label>
                <input type="text" name="address_country" id="address_country" class="form-control" value="<?php echo !empty($technician_details['address_country']) ? $technician_details['address_country'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control" value="<?php echo !empty($technician_details['phone_number']) ? $technician_details['phone_number'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="nic">National ID Number (NIC)</label>
                <input type="text" name="nic" id="nic" class="form-control" value="<?php echo !empty($technician_details['nic']) ? $technician_details['nic'] : ''; ?>">
            </div>
        <button type="submit" class="btn btn-primary mt-3">Update</button>
        <a href="technician_page.php" class="btn btn-secondary mt-3">Cancel</a>
    </form>
    </div>
</div>
<script src="../js/admin.js"></script>
<?php include('../templates/footer.php'); ?>