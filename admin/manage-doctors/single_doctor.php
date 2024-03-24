<?php
@include '../../config.php';

session_start();

if (!isset($_SESSION['admin_name'])) {
    header('location:../../login_form.php');
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('location:manage_doctor.php');
    exit;
}

$doctor_id = $_GET['id'];

class Database
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getDoctorDetails($id)
    {
        $query = "SELECT * FROM user_form WHERE id = '$id' AND user_type = 'doctor'";
        $result = mysqli_query($this->conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $doctor = mysqli_fetch_assoc($result);
            return $doctor;
        } else {
            return false;
        }
    }
}

$database = new Database($conn);

$doctor = $database->getDoctorDetails($doctor_id);

if (!$doctor) {
    header('location:manage_doctor.php');
    exit;
}

$pageTitle = 'Single Doctor';

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
        <li class="active_li"><a href="manage_doctor.php">Doctors</a></li>
        <li><a href="../manage-technicians/manage_technicians.php">Technician</a></li>
        <li><a href="../manage-admins/manage_admins.php">Administrators</a></li>
        <li><a href="../appointments/appointment.php">Appointments</a></li>
        <li><a href="../appointments/transactions.php">Transactions</a></li>
    </ul>
</div>

<div class="dashboard_content" id="dashboard_content">
    <div class="dashboard_header" style="display: flex; justify-content: space-between;">
        <h2 class="h5">Details of Doctor <span class="text-danger"><?php echo $doctor['name']; ?></span></h2>
        <div>
        <a href="manage_doctor.php" class="btn btn-secondary">👈 Back to All Doctors</a>
            <a href="../../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th scope="row">Full Name</th>
                        <td><?php echo $doctor['name']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Medical License Number</th>
                        <td><?php echo $doctor['medical_license_number']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Specialty</th>
                        <td><?php echo $doctor['doc_specialty']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Email</th>
                        <td><a href="mailto:<?php echo $doctor['email']; ?>"><?php echo $doctor['email']; ?></a></td>
                    </tr>
                    <tr>
                        <th scope="row">Phone Number</th>
                        <td><a href="tel:<?php echo $doctor['phone_number']; ?>"><?php echo $doctor['phone_number']; ?></a></td>
                    </tr>
                    <tr>
                        <th scope="row">Gender</th>
                        <td><?php echo $doctor['gender']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Date Of Birth</th>
                        <td><?php echo $doctor['dob']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">NIC</th>
                        <td><?php echo $doctor['nic']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Address Street</th>
                        <td><?php echo $doctor['address_street']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">City</th>
                        <td><?php echo $doctor['address_city']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">State</th>
                        <td><?php echo $doctor['address_state']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Postal Code</th>
                        <td><?php echo $doctor['address_postal_code']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Country</th>
                        <td><?php echo $doctor['address_country']; ?></td>
                    </tr>
                </tbody>
            </table>
</div>
<script src="../../js/admin.js"></script>
<?php include('../../templates/footer.php'); ?>

