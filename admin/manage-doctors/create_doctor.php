<?php
@include '../../config.php';

class UserRegistration
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function registerUser($name, $email, $gender, $dob, $street, $city, $state, $postal, $country, $phone, $nic, $medicalLicense, $docSpecialty, $password, $cpassword)
    {
        $name = mysqli_real_escape_string($this->conn, $name);
        $email = mysqli_real_escape_string($this->conn, $email);
        $gender = mysqli_real_escape_string($this->conn, $gender);
        $dob = mysqli_real_escape_string($this->conn, $dob);
        $pass = md5($password);
        $cpass = md5($cpassword);

        $select = "SELECT * FROM user_form WHERE email = '$email' AND password = '$pass'";
        $result = mysqli_query($this->conn, $select);

        if (mysqli_num_rows($result) > 0) {
            $message = "Email already exists. Please choose a different email.";
            echo "<script type='text/javascript'>alert('$message'); window.location='manage_doctor.php';</script>";
            return 'Email already exists!';
        }
         else {
            if ($password != $cpassword) {
                return 'Passwords do not match!';
            } else {
                $insert = "INSERT INTO user_form (name, email, gender, dob, address_street, address_city, address_state, address_postal_code, address_country, phone_number, nic, medical_license_number, doc_specialty, password, user_type)
                           VALUES ('$name', '$email', '$gender', '$dob', '$street', '$city', '$state', '$postal', '$country', '$phone', '$nic', '$medicalLicense', '$docSpecialty', '$pass', 'doctor')";
                if (mysqli_query($this->conn, $insert)) {
                    header('location: manage_doctor.php');
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

// Page title
$pageTitle = 'Create New Doctor';

include('../../templates/header.php');

function include_css() {
    echo '<link rel="stylesheet" type="text/css" href="../../css/dashboard.css">';
}include_css();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $registration = new UserRegistration($conn);
    $error = $registration->registerUser($_POST['name'], $_POST['email'], $_POST['gender'], $_POST['dob'], $_POST['address_street'], $_POST['address_city'], $_POST['address_state'], $_POST['address_postal_code'], $_POST['address_country'], $_POST['phone_number'], $_POST['nic'], $_POST['medical_license_number'], $_POST['doc_specialty'], $_POST['password'], $_POST['cpassword']);
}

?>

<button id="toggle_menu" class="btn btn-danger rounded"><span aria-hidden="true">Toggle Panel</span></button>

<div class="dashboard_sidebar hide" id="dashboard_sidebar">
    <h1>Admin Panel</h1>
    <ul>
        <li><a href="../admin_dashboard.php">Dashboard</a></li>
        <li><a href="../manage-patients/manage_patients.php">Patients</a></li>
        <li class="active_li"><a href="manage_doctor.php">Doctors</a></li>
        <li><a href="../manage-technicians/manage_technicians.php">Technician</a></li>
        <li><a href="../manage-admins/manage_admins.php">Administrators</a></li>
        <li><a href="../appointments/appointment.php">Appointments</a></li>
        <li><a href="../appointments/transactions.php">Transactions</a></li>
    </ul>
</div>

<div class="dashboard_content" id="dashboard_content">
    <div class="dashboard_header" style="display: flex; justify-content: space-between;">
        <h2 class="h5">New Doctor <span class="text-danger">Registration</span></h2>
        <div>
            <a href="manage_doctor.php" class="btn btn-secondary">👈 Back to All Doctors</a>
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
                <label for="gender">Gender</label>
                <select name="gender" id="gender" class="form-control" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" name="dob" id="dob" class="form-control">
            </div>
            <div class="form-group">
                <label for="address_street">Street Address</label>
                <input type="text" name="address_street" id="address_street" class="form-control" >
            </div>
            <div class="form-group">
                <label for="address_city">City</label>
                <input type="text" name="address_city" id="address_city" class="form-control" >
            </div>
            <div class="form-group">
                <label for="address_state">State/Province</label>
                <input type="text" name="address_state" id="address_state" class="form-control" >
            </div>
            <div class="form-group">
                <label for="address_postal_code">Postal Code</label>
                <input type="text" name="address_postal_code" id="address_postal_code" class="form-control" >
            </div>
            <div class="form-group">
                <label for="address_country">Country</label>
                <input type="text" name="address_country" id="address_country" class="form-control">
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control">
            </div>
            <div class="form-group">
                <label for="nic">National ID Number (NIC)</label>
                <input type="text" name="nic" id="nic" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="medical_license_number">Medical License Number</label>
                <input type="text" name="medical_license_number" id="medical_license_number" class="form-control">
            </div>
            <div class="form-group">
    <label for="doc_specialty">Specialty</label>
    <select name="doc_specialty" id="doc_specialty" class="form-control">
    <option value="Anesthesiology">Anesthesiology</option>
        <option value="Cardiology">Cardiology</option>
        <option value="Dermatology">Dermatology</option>
        <option value="Emergency Medicine">Emergency Medicine</option>
        <option value="Endocrinology">Endocrinology</option>
        <option value="Gastroenterology">Gastroenterology</option>
        <option value="Geriatrics">Geriatrics</option>
        <option value="Hematology">Hematology</option>
        <option value="Infectious Disease">Infectious Disease</option>
        <option value="Internal Medicine">Internal Medicine</option>
        <option value="Nephrology">Nephrology</option>
        <option value="Neurology">Neurology</option>
        <option value="Obstetrics and Gynecology">Obstetrics and Gynecology</option>
        <option value="Oncology">Oncology</option>
        <option value="Ophthalmology">Ophthalmology</option>
        <option value="Orthopedics">Orthopedics</option>
        <option value="Otolaryngology (ENT)">Otolaryngology (ENT)</option>
        <option value="Pathology">Pathology</option>
        <option value="Pediatrics">Pediatrics</option>
        <option value="Physical Medicine and Rehabilitation">Physical Medicine and Rehabilitation</option>
        <option value="Psychiatry">Psychiatry</option>
        <option value="Pulmonology">Pulmonology</option>
        <option value="Radiology">Radiology</option>
        <option value="Rheumatology">Rheumatology</option>
        <option value="Surgery">Surgery</option>
        <option value="Urology">Urology</option>
    </select>
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
            <a href="manage_doctor.php" class="btn btn-secondary mt-3">Cancel</a>
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

