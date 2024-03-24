<?php

@include '../config.php';

session_start();

if(!isset($_SESSION['admin_name'])){
   header('location:login_form.php');
   exit;
}

class UserOperations{
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function getAdminCount() {
        $query = "SELECT COUNT(*) AS admin_count FROM user_form WHERE user_type = 'admin'";
        $result = mysqli_query($this->conn, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['admin_count']; 
    }

    public function getPatientCount() {
        $query = "SELECT COUNT(*) AS patient_count FROM user_form WHERE user_type = 'patient'";
        $result = mysqli_query($this->conn, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['patient_count'];
    }

    public function getDoctorCount() {
        $query = "SELECT COUNT(*) AS doctor_count FROM user_form WHERE user_type = 'doctor'";
        $result = mysqli_query($this->conn, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['doctor_count'];
    }

    public function getUnPaidAppointmentCount() {
        $query = "SELECT COUNT(*) AS unPaidAppointment_count FROM appointments WHERE payment_status = 'Pending Payment'";
        $result = mysqli_query($this->conn, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['unPaidAppointment_count'];
    }

    public function getPaidAppointmentCount() {
        $query = "SELECT COUNT(*) AS paidAppointment_count FROM appointments WHERE payment_status = 'Paid'";
        $result = mysqli_query($this->conn, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['paidAppointment_count'];
    }
    public function getWaitingTransaction() {
        $query = "SELECT COUNT(*) AS waiting_transaction_count FROM transactions WHERE status = 'Processing'";
        $result = mysqli_query($this->conn, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['waiting_transaction_count'];
    }
}

$userOperations = new UserOperations($conn);

$adminCount = $userOperations->getAdminCount();

$patientCount = $userOperations->getPatientCount();

$doctorCount = $userOperations->getDoctorCount();

$getUnPaidAppointmentCount = $userOperations->getUnPaidAppointmentCount();

$getPaidAppointmentCount = $userOperations->getPaidAppointmentCount();

$getWaitingTransaction = $userOperations->getWaitingTransaction();

$pageTitle = 'Admin';

include('../templates/header.php');

function include_css() {
    echo '<link rel="stylesheet" type="text/css" href="../css/dashboard.css">';
}include_css();
?>

<button id="toggle_menu" class="btn btn-danger rounded"><span aria-hidden="true">Toggle Panel</span></button>
<div class="dashboard_sidebar hide" id="dashboard_sidebar" >
    <h1>Admin Panel</h1>
    <ul>
        <li class="active_li"><a href="admin_dashboard.php">Dashboard</a></li>
        <li><a href="./manage-patients/manage_patients.php">Patients</a></li>
        <li><a href="./manage-doctors/manage_doctor.php">Doctors</a></li>
        <li><a href="./manage-technicians/manage_technicians.php">Technician</a></li>
        <li><a href="./manage-admins/manage_admins.php">Administrators</a></li>
        <li><a href="./appointments/appointment.php">Appointments</a></li>
        <li><a href="./appointments/transactions.php">Transactions</a></li>
    </ul>
</div>

<div class="dashboard_content" id="dashboard_content">
    <div class="dashboard_header">
        <h2 class="h5">Welcome,<span class="text-danger"> <?php echo $_SESSION['admin_name'] ?></span></h2>
        <a href="../logout.php" class="btn btn-danger">Logout</a>
    </div>

    <div class="main_content">
    <div class="dash_card bg-secondary text-center border shadow">
        <p>Unpaid Appointments</p>
        <span><?php echo $getUnPaidAppointmentCount; ?></span> 
    </div>
    <div class="dash_card text-center bg-success border shadow">
        <p>Paid Appointments</p>
        <span><?php echo $getPaidAppointmentCount; ?></span> 
    </div>
    <div class="dash_card bg-warning text-center border shadow">
        <p class="text-dark">Waiting Payments</p>
        <span><?php echo $getWaitingTransaction; ?></span> 
    </div>
   
   
    <div class="dash_card text-center border shadow">
        <p>Total Admins</p>
        <span><?php echo $adminCount; ?></span> 
    </div>
    <div class="dash_card text-center border shadow">
        <p>Total Patients</p>
        <span><?php echo $patientCount; ?></span> 
    </div>
    <div class="dash_card text-center border shadow">
        <p>Total Doctors</p>
        <span><?php echo $doctorCount; ?></span> 
    </div>
    </div>
    <div class="border shadow rounded my-4 p-3 bg-light">
        <h5 class="mb-3">Functionalities of an admin in this system</h5>
        <small>➡️ You can manage (Create, Read, Update, Delete) patients, doctors, technicians & administrators</small>
        <br>
        <small>➡️ You can view each detailed appointments & download specific reports</small>
        <br>
        <small>➡️ You can view each transaction, Confirm or Decline the transaction & download receipt</small>
    </div>
</div>

<script src="../js/admin.js"></script>
<?php include('../templates/footer.php'); ?>