<?php
@include '../../config.php';

class UserRegistration
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function registerUser($name, $email, $gender, $dob, $street, $city, $state, $postal, $country, $phone, $nic, $medicalConditions, $allergies, $medications, $preProcedures, $famMedHistory, $emrName, $emrRelationship, $emrPhone, $password, $cpassword)
    {
        $name = mysqli_real_escape_string($this->conn, $name);
        $email = mysqli_real_escape_string($this->conn, $email);
        $gender = mysqli_real_escape_string($this->conn, $gender);
        $dob = mysqli_real_escape_string($this->conn, $dob);
        $pass = md5($password);
        $cpass = md5($cpassword);

        $select = "SELECT * FROM user_form WHERE email = '$email' && password = '$pass'";
        $result = mysqli_query($this->conn, $select);
        

        if (mysqli_num_rows($result) > 0) {
            $error[] = 'Email already exists!';
            echo "<script type='text/javascript'>alert('Email already exists. Please choose a different email.'); window.location='manage_patients.php';</script>";
        } else {
            if ($pass != $cpass) {
                $error[] = 'Passwords do not match!';
            } else {
                $insert = "INSERT INTO user_form(name, email, gender, dob, address_street, address_city, address_state, address_postal_code, address_country, phone_number, nic, medical_conditions, allergies, medications, previous_procedures, family_medical_history, emergency_contact_name, emergency_contact_relationship, emergency_contact_phone, password, user_type) VALUES
                ('$name','$email','$gender','$dob', '$street', '$city', '$state', '$postal', '$country', '$phone', '$nic', '$medicalConditions', '$allergies', '$medications', '$preProcedures', '$famMedHistory', '$emrName', '$emrRelationship', '$emrPhone','$pass','patient')";
                mysqli_query($this->conn, $insert);
                header('location: manage_patients.php');
                exit;
            }
        }
        return $error ?? [];
    }
}

session_start();

if (!isset($_SESSION['admin_name'])) {
    header('location:../../login_form.php');
    exit; 
}

$userRegistration = new UserRegistration($conn);

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $street = $_POST['address_street'];
    $city = $_POST['address_city'];
    $state = $_POST['address_state'];
    $postal = $_POST['address_postal_code'];
    $country = $_POST['address_country'];
    $phone = $_POST['phone_number'];
    $nic = $_POST['nic'];
    $medicalConditions = $_POST['medical_conditions'];
    $allergies = $_POST['allergies'];
    $medications = $_POST['medications'];
    $preProcedures = $_POST['previous_procedures'];
    $famMedHistory = $_POST['family_medical_history'];
    $emrName = $_POST['emergency_contact_name'];
    $emrRelationship = $_POST['emergency_contact_relationship'];
    $emrPhone = $_POST['emergency_contact_phone'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    $error = $userRegistration->registerUser($name, $email, $gender, $dob, $street, $city, $state, $postal, $country, $phone, $nic, $medicalConditions, $allergies, $medications, $preProcedures, $famMedHistory, $emrName, $emrRelationship, $emrPhone, $password, $cpassword);
}

$pageTitle = 'Create New Patient';

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
        <h2 class="h5">New Patient <span class="text-danger">Registration</span></h2>
        <div>
            <a href="manage_patients.php" class="btn btn-secondary">👈 Back to All Patients</a>
            <a href="../../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <div class="mb-5">
        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Patient Full Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender <span class="text-danger">*</span></label>
                <select name="gender" id="gender" class="form-control" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth <span class="text-danger">*</span></label>
                <input type="date" name="dob" id="dob" class="form-control" required>
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
    <input type="text" name="address_country" id="address_country" class="form-control" >
</div>
<div class="form-group">
    <label for="phone_number">Phone Number</label>
    <input type="text" name="phone_number" id="phone_number" class="form-control" >
</div>
<div class="form-group">
    <label for="nic">National ID Number (NIC)</label>
    <input type="text" name="nic" id="nic" class="form-control">
</div>
<div class="form-group">
    <label for="medical_conditions">Medical Conditions</label>
    <textarea name="medical_conditions" id="medical_conditions" class="form-control"></textarea>
</div>
<div class="form-group">
    <label for="allergies">Allergies</label>
    <textarea name="allergies" id="allergies" class="form-control"></textarea>
</div>
<div class="form-group">
    <label for="medications">Medications</label>
    <textarea name="medications" id="medications" class="form-control"></textarea>
</div>
<div class="form-group">
    <label for="previous_procedures">Previous Medical Procedures</label>
    <textarea name="previous_procedures" id="previous_procedures" class="form-control"></textarea>
</div>
<div class="form-group">
    <label for="family_medical_history">Family Medical History</label>
    <textarea name="family_medical_history" id="family_medical_history" class="form-control"></textarea>
</div>
<div class="form-group">
    <label for="emergency_contact_name">Emergency Contact Name <span class="text-danger">*</span></label>
    <input type="text" name="emergency_contact_name" id="emergency_contact_name" class="form-control" required>
</div>
<div class="form-group">
    <label for="emergency_contact_relationship">Relationship to Patient <span class="text-danger">*</span></label>
    <input type="text" name="emergency_contact_relationship" id="emergency_contact_relationship" class="form-control" required>
</div>
<div class="form-group">
    <label for="emergency_contact_phone">Emergency Contact Phone <span class="text-danger">*</span></label>
    <input type="text" name="emergency_contact_phone" id="emergency_contact_phone" class="form-control" required>
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
            <button type="submit" name="submit" class="btn btn-primary mt-3">Create</button>
            <a href="manage_patients.php" class="btn btn-secondary mt-3">Cancel</a>
        </form>
    </div>
</div>
<script src="../../js/admin.js"></script>
<?php include('../../templates/footer.php'); ?>