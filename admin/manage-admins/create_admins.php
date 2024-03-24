<?php
@include '../../config.php';

class UserRegistration
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function registerUser($name, $email, $phone, $password, $cpassword)
    {
        $name = mysqli_real_escape_string($this->conn, $name);
        $email = mysqli_real_escape_string($this->conn, $email);
        $pass = password_hash($password, PASSWORD_DEFAULT); 

        $select = "SELECT * FROM user_form WHERE email = '$email' AND password = '$pass'";
        $result = mysqli_query($this->conn, $select);

        if (mysqli_num_rows($result) > 0) {
            $message = "Email already exists. Please choose a different email.";
            echo "<script type='text/javascript'>alert('$message'); window.location='manage_technicians.php';</script>";
            return 'Email already exists!';
        }
         else {
            if ($password != $cpassword) {
                return 'Passwords do not match!';
            } else {
                $insert = "INSERT INTO user_form (name, email, phone_number, password, user_type)
                           VALUES ('$name', '$email', '$phone', '$pass', 'admin')";
                if (mysqli_query($this->conn, $insert)) {
                    header('location: manage_admins.php');
                    exit;
                } else {
                    return 'Error: ' . mysqli_error($this->conn);
                }
            }
        }
    }
}

session_start();

if (!isset($_SESSION['admin_name'])) {
    header('location:../../login_form.php');
    exit;
}

$pageTitle = 'Create New Admin';

include('../../templates/header.php');

function include_css() {
    echo '<link rel="stylesheet" type="text/css" href="../../css/dashboard.css">';
}include_css();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $registration = new UserRegistration($conn);
    $error = $registration->registerUser($_POST['name'], $_POST['email'], $_POST['phone_number'], $_POST['password'], $_POST['cpassword']);
}

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
        <h2 class="h5">New Technician <span class="text-danger">Registration</span></h2>
        <div>
            <a href="manage_admins.php" class="btn btn-secondary">👈 Back to All Admins</a>
            <a href="../../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <div class="main_content_shadow">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control">
            </div>
            <div class="input-group border rounded my-4">
            <input type="password" name="password" id="password" class="form-control no-border p-2" placeholder="Enter password" required autocomplete="on">
            <button class="btn no-border" type="button" id="togglePassword">
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
            </button>
        </div>
        <div class="input-group border rounded">
            <input type="password" name="cpassword" id="confirmPassword" class="form-control no-border p-2" placeholder="Confirm password" required autocomplete="on">
            <button class="btn no-border" type="button" id="toggleConfirmPassword">
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
            </button>
        </div>
            <button type="submit" class="btn btn-primary mt-3">Create</button>
            <a href="manage_admins.php" class="btn btn-secondary mt-3">Cancel</a>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($error)) {
            echo '<div class="alert alert-danger mt-3">' . $error . '</div>';
        }
        ?>
    </div>
</div>
<script src="../../js/admin.js"></script>
<?php include('../../templates/footer.php'); ?>
