<?php
session_start();
@include '../config.php';

if (!isset($_SESSION['patient_id'])) {
    header('location:../../login_form.php');
    exit;
}

$patient_id = $_SESSION['patient_id'];
$query = "SELECT * FROM user_form WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $patient_details = $result->fetch_assoc();
} else {
    echo "Error: Patient details not found.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $test_type = $_POST['test_type'];
    $price = $_POST['price'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $patient_id = $_POST['patient_id'];
    $payment_status = $_POST['payment_status'];
    $doctor_availability = $_POST['doctor_availability'];
    $technician_availability = $_POST['technician_availability'];
    $patient_message = $_POST['patient_message'];

    $uploaded_files = [];

    $upload_directory = 'uploads/Patient Previous Medical Documents/Patient ID_' . $patient_id;
    if (!file_exists($upload_directory)) {
        mkdir($upload_directory, 0777, true);
    }

    foreach ($_FILES['patient_prev_reports']['name'] as $index => $filename) {
        $temp_name = $_FILES['patient_prev_reports']['tmp_name'][$index];
        $file_extension = pathinfo($filename, PATHINFO_EXTENSION);
        $new_filename = uniqid() . '.' . $file_extension;
        $destination = $upload_directory . '/' . $new_filename;

        if (move_uploaded_file($temp_name, $destination)) {
            $uploaded_files[] = $destination;
        }
    }

    $stmt = $conn->prepare("INSERT INTO appointments (test_type, price, date, time, patient_id, payment_status, doctor_availability, technician_availability, patient_message, patient_prev_reports) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $test_type, $price, $date, $time, $patient_id, $payment_status, $doctor_availability, $technician_availability, $patient_message, serialize($uploaded_files));
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Appointment created successfully.'); window.location='appointment.php'; </script>";
    } else {
        echo '<script>alert("Failed to create appointment. Please try again.");</script>';
    }
}

$pageTitle = 'Create Appointment';
include('../templates/header.php');
function include_css() {
    echo '<link rel="stylesheet" type="text/css" href="../css/dashboard.css">';
}include_css();
?>


<button id="toggle_menu" class="btn btn-danger rounded"><span aria-hidden="true">Toggle Panel</span></button>
<div class="dashboard_sidebar hide" id="dashboard_sidebar" >
    <h1>Patient Panel</h1>
    <ul>
    <li><a href="patient_page.php">Dashboard</a></li>
        <li ><a href="profile.php">Medical Profile</a></li>
        <li class="active_li"><a href="appointment.php">Appointments</a></li>
        <li><a href="transactions.php">Transactions</a></li>
        <li><a href="tests.php">Tests</a></li>
        <li ><a href="edit_profile.php">Edit Profile</a></li>
        <li><a href="change_password.php">Change Password</a></li>
    </ul>
</div>
<div class="dashboard_content" id="dashboard_content">
    <div class="dashboard_header">
        <h2 class="h5">Schedule<span class="text-danger"> Appointment</span></h2>
        <div>
        <a href="appointment.php" class="btn btn-secondary">👈 Back</a>
        <a href="../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <div class="appointment_form">
        <form id="appointment_form" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="patient_id" value="<?php echo $patient_details['id']; ?>">
            <input type="hidden" name="payment_status" value="Pending Payment">
            <input type="hidden" name="doctor_availability" value="no">
            <input type="hidden" name="technician_availability" value="no">
            <input type="hidden" name="price" id="price_hidden">

            <div class="form-group">
    <label for="test_type">Medical Test Type</label>
    <select name="test_type" id="test_type" class="form-control" required>
        <option value="" disabled selected style="color: #555;">Select Type</option>
        <option value="Blood Test">Blood Test</option>
                    <option value="Urine Tests">Urine Test</option>
                    <option value="X-rays">X-rays</option>
                    <option value="MRI">MRI Scan</option>
                    <option value="CT scan">CT scan</option>
                    <option value="ECG">ECG</option>
                    <option value="EEG">EEG</option>
                    <option value="EEG">EEG</option>
                    <option value="Ultrasound">Ultrasound Test</option>
                    <option value="Endoscopy">Endoscopy</option>
                    <option value="Colonoscopy">Colonoscopy</option>
                    <option value="Genetic Test">Genetic Test</option>
                    <option value="Microbiology Test">Microbiology Test</option>
                    <option value="Serology Test">Serology Test</option>
                    <option value="Hormone Test">Hormone Test</option>
                    <option value="Biopsy Test">Biopsy Test</option>
                    <option value="Chemistry Test">Chemistry Test</option>
                    <option value="Immunology Test">Immunology Test</option>
                    <option value="Allergy Test">Allergy Test</option>
                    <option value="Virology Test">Virology Test</option>
                    <option value="Cytology Test">Cytology Test</option>
                    <option value="Coagulation Test">Coagulation Test</option>
                    <option value="Electrolyte Test">Electrolyte Test</option>
                    <option value="Endocrine Test">Endocrine Test</option>
                    <option value="Toxicology Test">Toxicology Test</option>
                    <option value="Liver Function Test">Liver Function Test</option>
                    <option value="Renal Function Test">Renal Function Test</option>
    </select>
    <i class="fa fa-caret-down input_position" aria-hidden="true"></i>
    </div>
        <div class="form-group">
            <label for="price">Total Payable Amount</label>
            <input type="text" name="price" id="price" class="form-control text-danger" disabled>
        </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" name="date" id="date" class="form-control" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+60 days')); ?>" required>
            </div>
            <div class="form-group">
                <label for="time">Time</label>
                <select name="time" id="time" class="form-control" required>
                    <option value="" disabled selected>Select Time</option>
                    <option value="07:00 to 07:30">07:00 to 07:30</option>
                    <option value="07:30 to 08:00">07:30 to 08:00</option>
                    <option value="08:00 to 08:30">08:00 to 08:30</option>
                    <option value="08:30 to 09:00">08:30 to 09:00</option>
                    <option value="09:00 to 09:30">09:00 to 09:30</option>
                    <option value="09:30 to 10:00">09:30 to 10:00</option>
                    <option value="10:00 to 10:30">10:00 to 10:30</option>
                    <option value="10:30 to 11:00">10:30 to 11:00</option>
                    <option value="11:00 to 11:30">11:00 to 11:30</option>
                    <option value="11:30 to 12:00">11:30 to 12:00</option>
                    <option value="12:00 to 12:30">12:00 to 12:30</option>
                    <option value="12:30 to 13:00">12:30 to 13:00</option>
                    <option value="13:00 to 13:30">13:00 to 13:30</option>
                    <option value="13:30 to 14:00">13:30 to 14:00</option>
                    <option value="14:00 to 14:30">14:00 to 14:30</option>
                    <option value="14:30 to 15:00">14:30 to 15:00</option>
                    <option value="15:00 to 15:30">15:00 to 15:30</option>
                    <option value="15:30 to 16:00">15:30 to 16:00</option>
                </select>
                <i class="fa-solid fa-clock input_position"></i>
            </div>

            <div class="form-group">
            <label for="patient_message">Your Message (Optional)</label>
            <input type="text" name="patient_message" id="patient_message" class="form-control">
            </div>
            <div class="form-group">
            <label for="patient_prev_reports">Upload any previous Medical Reports/Documents:</label>
            <input type="file" id="patient_prev_reports" name="patient_prev_reports[]" accept=".pdf, .doc, .docx, .jpg, .jpeg, .png" multiple>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Create</button>
            <a href="appointment.php" class="btn btn-secondary mt-3">Cancel</a>
        </form>
    </div>
</div>

<script>
    document.getElementById('appointment_form').addEventListener('submit', function(event) {
        var testType = document.getElementById('test_type').value;
        var date = document.getElementById('date').value;
        var time = document.getElementById('time').value;
        
        if (!testType || !date || !time) {
            event.preventDefault();
            alert('Please fill in all fields.');
        }
    });
</script>


<script>
document.addEventListener("DOMContentLoaded", function() {
    var testTypeSelect = document.getElementById('test_type');
    var priceInput = document.getElementById('price');
    
    testTypeSelect.addEventListener('change', function() {
        var selectedTestType = testTypeSelect.value;
        var price;
        switch (selectedTestType) {
            case 'Blood Test':
                price = 'LKR 6,000';
                break;
            case 'Urine Tests':
                price = 'LKR 2,000';
                break;
            case 'X-rays':
                price = 'LKR 15,000';
                break;
            case 'MRI':
                price = 'LKR 20,000';
                break;
            case 'CT scan':
                price = 'LKR 20,000';
                break;
            case 'ECG':
                price = 'LKR 45,000';
                break;
            case 'EEG':
                price = 'LKR 12,000';
                break;
            case 'Ultrasound':
                price = 'LKR 15,000';
                break;
            case 'Endoscopy':
                price = 'LKR 18,000';
                break;
            case 'Colonoscopy':
                price = 'LKR 20,000';
                break;
            case 'Genetic Test':
                price = 'LKR 25,000';
                break;
            case 'Microbiology Test':
                price = 'LKR 12,000';
                break;
            case 'Serology Test':
                price = 'LKR 10,000';
                break;
            case 'Hormone Test':
                price = 'LKR 15,000';
                break;
            case 'Biopsy Test':
                price = 'LKR 18,000';
                break;
            case 'Chemistry Test':
                price = 'LKR 12,000';
                break;
            case 'Immunology Test':
                price = 'LKR 10,000';
                break;
            case 'Allergy Test':
                price = 'LKR 12,000';
                break;
            case 'Virology Test':
                price = 'LKR 15,000';
                break;
            case 'Cytology Test':
                price = 'LKR 12,000';
                break;
            case 'Coagulation Test':
                price = 'LKR 10,000';
                break;
            case 'Electrolyte Test':
                price = 'LKR 15,000';
                break;
            case 'Endocrine Test':
                price = 'LKR 18,000';
                break;
            case 'Toxicology Test':
                price = 'LKR 20,000';
                break;
            case 'Liver Function Test':
                price = 'LKR 15,000';
                break;
            case 'Renal Function Test':
                price = 'LKR 18,000';
                break;
            default:
                price = 'LKR 0';
        }
        priceInput.value = price;
document.getElementById('price_hidden').value = price;

    });
});
</script>
<script src="../js/admin.js"></script>
<?php include('../templates/footer.php'); ?>