<?php
@include '../../config.php';

session_start();

if (!isset($_SESSION['admin_name'])) {
    header('location:../../login_form.php');
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('location:manage_technicians.php');
    exit;
}

$technician_id = $_GET['id'];

class Database
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getTechnicianDetails($id)
    {
        $query = "SELECT * FROM user_form WHERE id = '$id' AND user_type = 'technician'";
        $result = mysqli_query($this->conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $technician = mysqli_fetch_assoc($result);
            return $technician;
        } else {
            return false;
        }
    }
}

$database = new Database($conn);

$technician = $database->getTechnicianDetails($technician_id);

if (!$technician) {
    header('location:manage_technicians.php');
    exit;
}

$pageTitle = 'Single Technician';

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
        <li class="active_li"><a href="manage_technicians.php">Technician</a></li>
        <li><a href="../manage-admins/manage_admins.php">Administrators</a></li>
        <li><a href="../appointments/appointment.php">Appointments</a></li>
        <li><a href="../appointments/transactions.php">Transactions</a></li>
    </ul>
</div>

<div class="dashboard_content" id="dashboard_content">
    <div class="dashboard_header" style="display: flex; justify-content: space-between;">
        <h2 class="h5">Details of Technician <span class="text-danger"><?php echo $technician['name']; ?></span></h2>
        <div>
        <a href="manage_technicians.php" class="btn btn-secondary">👈 Back to All Technicians</a>
            <a href="../../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th scope="row">Full Name</th>
                        <td><?php echo $technician['name']; ?></td>
                    </tr>

                    <tr>
                        <th scope="row">Email</th>
                        <td><a href="mailto:<?php echo $technician['email']; ?>"><?php echo $technician['email']; ?></a></td>
                    </tr>
                    <tr>
                        <th scope="row">Phone Number</th>
                        <td><a href="tel:<?php echo $technician['phone_number']; ?>"><?php echo $technician['phone_number']; ?></a></td>
                    </tr>
                    <tr>
                        <th scope="row">Gender</th>
                        <td><?php echo $technician['gender']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Date Of Birth</th>
                        <td><?php echo $technician['dob']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">NIC</th>
                        <td><?php echo $technician['nic']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Specialization</th>
                        <td><?php echo $technician['technician_specialization']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Address Street</th>
                        <td><?php echo $technician['address_street']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">City</th>
                        <td><?php echo $technician['address_city']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">State</th>
                        <td><?php echo $technician['address_state']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Postal Code</th>
                        <td><?php echo $technician['address_postal_code']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Country</th>
                        <td><?php echo $technician['address_country']; ?></td>
                    </tr>



                </tbody>
            </table>
</div>
            <script src="../../js/admin.js"></script>
<?php include('../../templates/footer.php'); ?>
