<?php
@include '../../config.php';

session_start();

if (!isset($_SESSION['admin_name'])) {
    header('location:../../login_form.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $admin_id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM user_form WHERE id = '$admin_id' AND user_type = 'admin'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);
    } else {
        header('location:manage_admins.php?error=Admin not found.');
        exit;
    }
} else {
    header('location:manage_admins.php?error=Invalid request.');
    exit;
}

$pageTitle = 'Edit Admin';

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
        <li><a href="../manage-patients/manage_patients.php">Patients</a></li>
        <li ><a href="../manage-doctors/manage_doctor.php">Doctors</a></li>
        <li><a href="../manage-technicians/manage_technicians.php">Technician</a></li>
        <li class="active_li"><a href="manage_admins.php">Administrators</a></li>
        <li><a href="../appointments/appointment.php">Appointments</a></li>
        <li><a href="../appointments/transactions.php">Transactions</a></li>
    </ul>
</div>

<div class="dashboard_content" id="dashboard_content">
    <div class="dashboard_header" style="display: flex; justify-content: space-between;">
        <h2 class="h5">Edit <span class="text-danger">Admin</span> Details</h2>
        <div>
            <a href="manage_admins.php" class="btn btn-secondary">👈 Back to All Admins</a>
            <a href="../../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <div class="main_content_shadow">
        <form method="POST" action="update_admin.php">
            <input type="hidden" name="admin_id" value="<?php echo $admin['id']; ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo $admin['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input autocomplete="off" type="email" name="email" id="email" class="form-control" value="<?php echo $admin['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control" value="<?php echo !empty($admin['phone_number']) ? $admin['phone_number'] : ''; ?>">
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
            <a href="manage_doctor.php" class="btn btn-secondary mt-3">Cancel</a>
        </form>
    </div>
</div>
<script src="../../js/admin.js"></script>
<?php include('../../templates/footer.php'); ?>
