<?php
@include '../../config.php';

session_start();

if (!isset($_SESSION['admin_name'])) {
    header('location:../../login_form.php');
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('location:manage_patients.php');
    exit;
}

$patient_id = $_GET['id'];

class Database
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getPatientDetails($id)
    {
        $query = "SELECT * FROM user_form WHERE id = '$id' AND user_type = 'patient'";
        $result = mysqli_query($this->conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $patient = mysqli_fetch_assoc($result);
            return $patient;
        } else {
            return false;
        }
    }
}

$database = new Database($conn);

$patient = $database->getPatientDetails($patient_id);

if (!$patient) {
    header('location:manage_patients.php');
    exit;
}

$pageTitle = 'Single Patient';

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
        <h2 class="h5">Details of Patient ID: <span class="text-danger"><?php echo $patient['id']; ?></span></h2>
        <div>
        <a href="manage_patients.php" class="btn btn-secondary">👈 Back to All Patients</a>
            <a href="../../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th scope="row">ID</th>
                        <td><?php echo $patient['id']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Name</th>
                        <td><?php echo $patient['name']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Email</th>
                        <td><a href="mailto:<?php echo $patient['email']; ?>"><?php echo $patient['email']; ?></a></td>
                    </tr>
                    <tr>
                        <th scope="row">Gender</th>
                        <td><?php echo $patient['gender']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Date Of Birth</th>
                        <td><?php echo $patient['dob']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Address Street</th>
                        <td><?php echo $patient['address_street']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">City</th>
                        <td><?php echo $patient['address_city']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">State</th>
                        <td><?php echo $patient['address_state']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Postal Code</th>
                        <td><?php echo $patient['address_postal_code']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Country</th>
                        <td><?php echo $patient['address_country']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Phone Number</th>
                        <td><a href="tel:<?php echo $patient['phone_number']; ?>"><?php echo $patient['phone_number']; ?></a></td>
                    </tr>
                    <tr>
                        <th scope="row">NIC</th>
                        <td><?php echo $patient['nic']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Medical Conditions</th>
                        <td><?php echo $patient['medical_conditions']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Allergies</th>
                        <td><?php echo $patient['allergies']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Medications</th>
                        <td><?php echo $patient['medications']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Previous Medical Procedures</th>
                        <td><?php echo $patient['previous_procedures']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Family Medical History</th>
                        <td><?php echo $patient['family_medical_history']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Emergency Contact Name</th>
                        <td><?php echo $patient['emergency_contact_name']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Relationship to Patient</th>
                        <td><?php echo $patient['emergency_contact_relationship']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Emergency Contact Number</th>
                        <td><a href="tel:<?php echo $patient['emergency_contact_phone']; ?>"><?php echo $patient['emergency_contact_phone']; ?></a></td>
                    </tr>
                </tbody>
            </table>
</div>
<script src="../../js/admin.js"></script>
<?php include('../../templates/footer.php'); ?>
            