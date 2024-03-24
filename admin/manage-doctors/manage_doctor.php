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

    public function getAllDoctors()
    {
        $query = "SELECT * FROM user_form WHERE user_type = 'doctor'";
        $result = mysqli_query($this->conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $doctors = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $doctors;
        } else {
            return [];
        }
    }

    public function deleteDoctor($id)
    {
        $query = "DELETE FROM user_form WHERE id = '$id'";
        mysqli_query($this->conn, $query);
    }
}

$database = new Database($conn);

$doctors = $database->getAllDoctors();

$pageTitle = 'Doctor';

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
        <h2 class="h5">Manage <span class="text-danger">Doctors</span></h2>
        <div>
        <a href="create_doctor.php" class="btn btn-success">Add New Doctor</a>
            <a href="../../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <div class="main_content_shadow">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>More Details</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($doctors)): ?>
                    <tr>
                        <td colspan="4">No doctors registered</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($doctors as $doctor): ?>
                        <tr>
                            <td><?php echo $doctor['name']; ?></td>
                            <td><?php echo $doctor['email']; ?></td>
                            <td><a href="single_doctor.php?id=<?php echo $doctor['id']; ?>" class="btn btn-primary">View Info</a></td>
                            <td><a href="edit_doctor.php?id=<?php echo $doctor['id']; ?>" class="btn btn-warning">Edit</a></td>
                            <td><a href="?delete=<?php echo $doctor['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this doctor?')">Delete</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
if (isset($_GET['delete'])) {
    $database->deleteDoctor($_GET['delete']);
    header('location: manage_doctor.php');
}
ob_end_flush();
?>

<script src="../../js/admin.js"></script>
<?php include('../../templates/footer.php'); ?>