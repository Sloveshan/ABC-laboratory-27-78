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

$pageTitle = 'Change Password';

include('../templates/header.php');

function include_css() {
    echo '<link rel="stylesheet" type="text/css" href="../css/dashboard.css">';
}include_css();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = isset($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : '';
    $confirm_password = isset($_POST['cpassword']) ? mysqli_real_escape_string($conn, $_POST['cpassword']) : '';

    if ($new_password != $confirm_password) {
        $message = "Passwords do not match.";
        echo "<script type='text/javascript'>alert('$message'); window.location='change_password.php';</script>";
        exit;
    }

    $hashed_password = md5($new_password);
    $update_query = "UPDATE user_form SET password = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("si", $hashed_password, $patient_id);

    if ($update_stmt->execute()) {
        header('location:patient_page.php?success=Password updated successfully.');
        exit;
    } else {
        header('location:change_password.php?error=Failed to update password.');
        exit;
    }
}
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
        <li><a href="edit_profile.php">Edit Profile</a></li>
        <li class="active_li"><a href="change_password.php">Change Password</a></li>
    </ul>
</div>
<div class="dashboard_content" id="dashboard_content">
    <div class="dashboard_header">
        <h2 class="h5">Change Your <span class="text-danger">Password</span></h2>
        <div>
        <a href="patient_page.php" class="btn btn-secondary">👈 Back to Profile</a>
        <a href="../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
    <div>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="hidden" name="patient_id" value="<?php echo $patient_details['id']; ?>">
    <div class="input-group border rounded mb-3">
            <input type="password" name="password" id="password" class="form-control no-border p-2" placeholder="Enter your password" required autocomplete="on">
            <button class="btn no-border" type="button" id="togglePassword">
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
            </button>
        </div>
        <div class="input-group border rounded mb-3">
            <input type="password" name="cpassword" id="confirmPassword" class="form-control no-border p-2" placeholder="Confirm your password" required autocomplete="on">
            <button class="btn no-border" type="button" id="toggleConfirmPassword">
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
            </button>
        </div>
    
    <button type="submit" class="btn btn-warning mt-3">Change Password</button>
    <a href="patient_page.php" class="btn btn-secondary mt-3">Cancel</a>
</form>
    </div>
</div>
<script src="../js/admin.js"></script>
<?php include('../templates/footer.php'); ?>