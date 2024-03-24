<?php
ob_start();

@include '../../config.php';

session_start();

if (!isset($_SESSION['admin_name'])) {
    header('location:../../login_form.php');
    exit;
}

class Database
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllPatients()
    {
        $query = "SELECT * FROM user_form WHERE user_type = 'patient'";
        $result = mysqli_query($this->conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $patients = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $patients;
        } else {
            return [];
        }
    }

    public function deletePatient($id)
    {
        $query = "DELETE FROM user_form WHERE id = '$id'";
        mysqli_query($this->conn, $query);
    }
}

$database = new Database($conn);

$patients = $database->getAllPatients();

$pageTitle = 'Admin';

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
        <h2 class="h5">Manage <span class="text-danger">Patients</span></h2>
        <div>
        <a href="create_patient.php" class="btn btn-success">Add New Patient</a>
            <a href="../../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <div class="main_content_shadow">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>More Details</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($patients)): ?>
                    <tr>
                        <td colspan="4">No patients registered</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($patients as $patient): ?>
                        <tr>
                        <td><?php echo $patient['id']; ?></td>
                            <td><?php echo $patient['name']; ?></td>
                            <td><?php echo $patient['email']; ?></td>
                            <td><a href="single_patient.php?id=<?php echo $patient['id']; ?>" class="btn btn-primary">View Info</a></td>
                            <td><a href="edit_patient.php?id=<?php echo $patient['id']; ?>" class="btn btn-warning">Edit</a></td>
                            <td><a href="?delete=<?php echo $patient['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this patient?')">Delete</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
if (isset($_GET['delete'])) {
    $database->deletePatient($_GET['delete']);
    header('location: manage_patients.php');
}
ob_end_flush();
?>
<script src="../../js/admin.js"></script>
<?php include('../../templates/footer.php'); ?>