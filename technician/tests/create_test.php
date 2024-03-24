<?php
session_start();
@include '../../config.php';

if (!isset($_SESSION['technician_id'])) {
   header('location:../../login_form.php');
   exit;
}

$pageTitle = 'Create Report';
include('../../templates/header.php');

function include_css() {
    echo '<link rel="stylesheet" type="text/css" href="../../css/dashboard.css">';
}include_css();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $test_result = $_POST['test_result'];
    $appointment_id = $_POST['appointment_id'];
    $patientId = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $technician_id = $_POST['technician_id'];
    $test_date = $_POST['test_date'];
    $test_type = $_POST['test_type'];
    $test_result = $_POST['test_result'];
    $room = $_POST['test_room'];
    $test_status = $_POST['test_status'];

    $targetDir = "uploads/Test Documents/Appointment ID_$appointment_id/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = basename($_FILES["documents"]["name"]); 
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES["documents"]["tmp_name"], $targetFilePath)) {
        $insert_query = "INSERT INTO tests (appointment_id, patient_id, doctor_id, technician_id, test_date, test_type, test_result, test_room, test_status, documents) VALUES ('$appointment_id', '$patientId', '$doctor_id', '$technician_id', '$test_date', '$test_type', '$test_result', '$room', '$test_status', '$fileName')";
        if ($conn->query($insert_query) === TRUE) {
            include 'email_test.php';
            echo "<script type='text/javascript'>alert('Test Created & Email Sent Successfully'); window.location='tests.php';</script>";
        } else {
            echo "Error: " . $insert_query . "<br>" . $conn->error;
        }
    } else {
        echo "Error uploading file.";
    }
}

$appointment_id = $_GET['appointment_id'];
$sql = "SELECT * FROM appointments WHERE appointment_id = '$appointment_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointment_id = $row['appointment_id'];
        $patient_id = $row['patient_id'];
        $doctor_id = $row['doctor_id'];
        $technician_id = $row['technician_id'];
        $test_type = $row['test_type'];
        $appointed_date = $row['date'];
    }

    // Now fetch data from user_form based on the patient_id
    $user_form_sql = "SELECT * FROM user_form WHERE id = '$patient_id'";
    $user_form_result = $conn->query($user_form_sql);

    if ($user_form_result->num_rows > 0) {
        while ($user_form_row = $user_form_result->fetch_assoc()) {
            $patient_email = $user_form_row['email'];
            $patient_name = $user_form_row['name'];
        }
    } else {
        echo "No user form data found for the patient";
    }
} else {
    echo "No appointment found with the provided ID";
}

?>

<button id="toggle_menu" class="btn btn-danger rounded"><span aria-hidden="true">Toggle Panel</span></button>
<div class="dashboard_sidebar hide" id="dashboard_sidebar" >
    <h1>Technician Panel</h1>
    <ul>
        <li><a href="../technician_page.php">Technical Profile</a></li>
        <li><a href="../appointments.php">Appointments</a></li>
        <li><a href="../edit_profile.php">Edit Profile</a></li>
        <li><a href="../change_password.php">Change Password</a></li>
        <li class="active_li"><a href="tests.php">Tests</a></li>
    </ul>
</div>
<div class="dashboard_content" id="dashboard_content">
    <div class="dashboard_header">
        <h2 class="h5">Create Test<span class="text-danger"> Report</span></h2>
        <div>
            <a href="tests.php" class="btn btn-secondary">👈 Back</a>
            <a href="../../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
    <div class="col-md-12 order-md-2 my-4">
        <ul class="list-group mb-3">

        <li class="list-group-item d-flex justify-content-between bg-light">
                <span class="text-danger">Appointment ID</span>
                <strong class="text-danger">#<?php echo $appointment_id; ?></strong>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <h6 class="my-0">Patient ID</h6>
                </div>
                <span class="text-muted">#<?php echo $patient_id; ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <h6 class="my-0">Doctor ID</h6>
                </div>
                <span class="text-muted">#<?php echo $doctor_id; ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <h6 class="my-0">Technician ID</h6>
                </div>
                <span class="text-muted">#<?php echo $technician_id; ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <h6 class="my-0">Test Type</h6>
                </div>
                <span class="text-muted"><?php echo $test_type; ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <h6 class="my-0">Appointed Date</h6>
                </div>
                <span class="text-muted"><?php echo $appointed_date; ?></span>
            </li>
        </ul>
    </div>
    <div class="border shadow rounded my-4 p-3 bg-light">
        <h5>Submit Test Report for Appointment ID #<?php echo $appointment_id; ?></h6>
            <form id="appointment_form" method="POST" enctype="multipart/form-data">
            <input class="form-control" type="hidden" name="appointment_id" value="<?php echo $appointment_id; ?>">
            <input class="form-control" type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
            <input class="form-control" type="hidden" name="doctor_id" value="<?php echo $doctor_id; ?>">
            <input class="form-control" type="hidden" name="technician_id" value="<?php echo $technician_id; ?>">
            <input class="form-control" type="hidden" name="test_date" value="<?php echo $appointed_date; ?>">
            <input class="form-control" type="hidden" name="test_type" value="<?php echo $test_type; ?>">
            <input class="form-control" type="hidden" name="email" value="<?php echo $patient_email; ?>">
            <input class="form-control" type="hidden" name="name" value="<?php echo $patient_name; ?>">
                <div class="form-group">
                    <input type="text"  class="form-control" name="test_result" placeholder="Enter Test Results">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="test_room" placeholder="Enter Test Room">
                </div>
                <div class="form-group">
                    <select name="test_status" id="test_status" required class="form-control p-2 mb-3" style="color: #555;">
                        <option value="" disabled selected style="color: #555;">Test Status</option>
                        <option value="Successful" style="color: #555;">Successfully Completed</option>
                        <option value="Processing" style="color: #555;">Processing</option>
                        <option value="Postponed" style="color: #555;">Postponed</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="documents">Upload Test Report</label>
                <input type="file" id="documents" name="documents" accept=".pdf, .doc, .docx, .jpg, .jpeg, .png" multiple>
                </div>
                <button type="submit" name="submit" class="btn btn-primary mt-3 w-100">Submit Details</button>
            </form>
        </div>
</div>
<script src="../../js/admin.js"></script>
<?php include('../../templates/footer.php'); ?>