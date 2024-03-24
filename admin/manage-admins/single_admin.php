<?php
@include '../../config.php';

session_start();

if (!isset($_SESSION['admin_name'])) {
    header('location:../../login_form.php');
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('location:manage_admins.php');
    exit;
}

$admin_id = $_GET['id'];

class Database
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAdminDetails($id)
    {
        $query = "SELECT * FROM user_form WHERE id = '$id' AND user_type = 'admin'";
        $result = mysqli_query($this->conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $admin = mysqli_fetch_assoc($result);
            return $admin;
        } else {
            return false;
        }
    }
}

$database = new Database($conn);

$admin = $database->getAdminDetails($admin_id);

if (!$admin) {
    header('location:manage_admins.php');
    exit;
}

$pageTitle = 'Single Admin';

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
        <h2 class="h5">Details of Administrator <span class="text-danger"><?php echo $admin['name']; ?></span></h2>
        <div>
        <a href="manage_admins.php" class="btn btn-secondary">👈 Back to All Admins</a>
            <a href="../../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th scope="row">Full Name</th>
                        <td><?php echo $admin['name']; ?></td>
                    </tr>

                    <tr>
                        <th scope="row">Email</th>
                        <td><a href="mailto:<?php echo $admin['email']; ?>"><?php echo $admin['email']; ?></a></td>
                    </tr>
                    <tr>
                        <th scope="row">Phone Number</th>
                        <td><a href="tel:<?php echo $admin['phone_number']; ?>"><?php echo $admin['phone_number']; ?></a></td>
                    </tr>
                </tbody>
            </table>
</div>
            <script src="../js/admin.js"></script>
<?php include('../../templates/footer.php'); ?>

