<?php
session_start();
@include '../config.php';

if (!isset($_SESSION['doctor_id'])) {
    header('location:../../login_form.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['appointment_id'])) {
    $appointmentId = $_GET['appointment_id'];

    $stmt = $conn->prepare("SELECT * FROM appointments WHERE appointment_id = ?");
    $stmt->bind_param("i", $appointmentId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        $patientId = $row['patient_id'];
        $patient_stmt = $conn->prepare("SELECT name, gender, email, dob, nic, address_street, address_city, address_state, address_postal_code, address_country, phone_number, medical_conditions, allergies, medications, previous_procedures, family_medical_history, emergency_contact_name, emergency_contact_relationship, emergency_contact_phone FROM user_form WHERE id = ?");
        $patient_stmt->bind_param("i", $patientId);
        $patient_stmt->execute();
        $patient_result = $patient_stmt->get_result();
        
        if ($patient_result->num_rows > 0) {
            $patient_row = $patient_result->fetch_assoc();


            $pageTitle = 'Appointment Details';
            include('../templates/header.php');
            function include_css() {
                echo '<link rel="stylesheet" type="text/css" href="../css/dashboard.css">';
            }include_css();
        ?>
            <button id="toggle_menu" class="btn btn-danger rounded"><span aria-hidden="true">Toggle Panel</span></button>
            <div class="dashboard_sidebar hide" id="dashboard_sidebar">
                <h1>Doctor Panel</h1>
                <ul>
                <li><a href="doctor_page.php">Medical Profile</a></li>
                <li class="active_li"><a href="appointments.php">Appointments</a></li>
                <li><a href="edit_profile.php">Edit Profile</a></li>
                <li><a href="change_password.php">Change Password</a></li>
                <li><a href="tests.php">Tests</a></li>
                </ul>
            </div>
            <div class="dashboard_content" id="dashboard_content">
                <div class="dashboard_header">
                    <h2 class="h5">Appointment ID: <span class="text-danger"><?php echo $row['appointment_id']; ?></span></h2>
                    <div>
                        <a href="appointments.php" class="btn btn-secondary">👈 Back</a>
                        <a href="appointment_report.php?appointment_id=<?php echo $row['appointment_id']; ?>" class="btn btn-success">Download Report</a>
                        <a href="../logout.php" class="btn btn-danger">Logout</a>
                    </div>
                </div>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th scope="row">Appointment ID</th>
                            <td><?php echo $row['appointment_id']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Doctor Status</th>
                            <td>
                                <?php 
                                if ($row['doctor_availability'] == 'yes') {
                                    echo "Available for the test ✅";
                                } else {
                                    echo "Doctors are not available ❌";
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Technician Status</th>
                            <td>
                                <?php 
                                if ($row['technician_availability'] == 'yes') {
                                    echo "Available for the test ✅";
                                } else {
                                    echo "Technicians are not available ❌";
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Payment Status</th>
                            <td><?php echo $row['payment_status']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Test Type</th>
                            <td><?php echo $row['test_type']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Date Of Medical Test</th>
                            <td><?php echo $row['date']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Time Of Medical Test</th>
                            <td><?php echo $row['time']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Patient's Message</th>
                            <td><?php echo $row['patient_message']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Patient's previous reports</th>
                            <td>
                                <?php 
                                if (!empty($row['patient_prev_reports'])) {
                                    $uploaded_files = unserialize($row['patient_prev_reports']);
                                    if (!empty($uploaded_files)) {
                                        foreach ($uploaded_files as $file) {
                                            $filename = basename($file);
                                            $file_path = '../patient/uploads/Patient Previous Medical Documents/Patient ID_' . $patientId . '/' . $filename; // Add a directory separator here
                                            if (file_exists($file_path)) {
                                                echo '<a href="' . $file_path . '" download>' . $filename . '</a><br>';
                                            } else {
                                                echo 'Error: File not found<br>';
                                            }
                                        }
                                    } else {
                                        echo 'No documents uploaded';
                                    }
                                } else {
                                    echo 'No documents uploaded';
                                }
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php
                if ($row['doctor_id'] != 0) {
                    $doctorId = $row['doctor_id'];
                    $doctor_stmt = $conn->prepare("SELECT name, doc_specialty, medical_license_number, email, phone_number, gender, dob, nic FROM user_form WHERE id = ?");
                    $doctor_stmt->bind_param("i", $doctorId);
                    $doctor_stmt->execute();
                    $doctor_result = $doctor_stmt->get_result();
        
                    if ($doctor_result->num_rows > 0) {
                        $doctor_row = $doctor_result->fetch_assoc();
                ?>
                <h4 class="mt-5">Doctor Details</h4>
                <table id="doctor_table" class="table table-bordered">
                    <tbody>
                        <tr>
                            <th scope="row">ID</th>
                            <td><?php echo $row['doctor_id']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Name</th>
                            <td><?php echo $doctor_row['name']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Doctor Specialty</th>
                            <td><?php echo $doctor_row['doc_specialty']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Medical License Number</th>
                            <td><?php echo $doctor_row['medical_license_number']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Email</th>
                            <td><?php echo $doctor_row['email']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Phone Number</th>
                            <td><?php echo $doctor_row['phone_number']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Gender</th>
                            <td><?php echo $doctor_row['gender']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Date Of Birth</th>
                            <td><?php echo $doctor_row['dob']; ?></td>
                        </tr>
                        <tr>
                        <th scope="row">NIC</th>
                        <td><?php echo $doctor_row['nic']; ?></td>
                    </tr>
                    </tbody>
                </table>
                <?php
                    }
                }
                ?>



            <?php
                if ($row['technician_id'] != 0) {
                    $technicianId = $row['technician_id'];
                    $technician_stmt = $conn->prepare("SELECT name, technician_specialization, email, phone_number, gender, dob, nic FROM user_form WHERE id = ?");
                    $technician_stmt->bind_param("i", $technicianId);
                    $technician_stmt->execute();
                    $technician_result = $technician_stmt->get_result();
        
                    if ($technician_result->num_rows > 0) {
                        $technician_row = $technician_result->fetch_assoc();
                ?>
                <h4 class="mt-5">Technician Details</h4>
                <table id="technician_table" class="table table-bordered">
                    <tbody>
                        <tr>
                            <th scope="row">ID</th>
                            <td><?php echo $row['technician_id']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Name</th>
                            <td><?php echo $technician_row['name']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Technician Availability</th>
                            <td><?php echo $technician_row['technician_specialization']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Email</th>
                            <td><?php echo $technician_row['email']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Phone Number</th>
                            <td><?php echo $technician_row['phone_number']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Gender</th>
                            <td><?php echo $technician_row['gender']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Date Of Birth</th>
                            <td><?php echo $technician_row['dob']; ?></td>
                        </tr>
                        <tr>
                        <th scope="row">NIC</th>
                        <td><?php echo $technician_row['nic']; ?></td>
                    </tr>
                    </tbody>
                </table>
                <?php
                    }
                }
                ?>




                <h4 class="mt-5">Patient Details</h4>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th scope="row">ID</th>
                            <td><?php echo $row['patient_id']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Name</th>
                            <td><?php echo $patient_row['name']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Email</th>
                            <td><?php echo $patient_row['email']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Phone Number</th>
                            <td><?php echo $patient_row['phone_number']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Gender</th>
                            <td><?php echo $patient_row['gender']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Date Of Birth</th>
                            <td><?php echo $patient_row['dob']; ?></td>
                        </tr>
                        <tr>
                        <th scope="row">NIC</th>
                        <td><?php echo $patient_row['nic']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Medical Conditions</th>
                        <td><?php echo $patient_row['medical_conditions']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Allergies</th>
                        <td><?php echo $patient_row['allergies']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Medications</th>
                        <td><?php echo $patient_row['medications']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Previous Medical Procedures</th>
                        <td><?php echo $patient_row['previous_procedures']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Family Medical History</th>
                        <td><?php echo $patient_row['family_medical_history']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Emergency Contact Name</th>
                        <td><?php echo $patient_row['emergency_contact_name']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Relationship to Patient</th>
                        <td><?php echo $patient_row['emergency_contact_relationship']; ?></td>
                    </tr>

                    <tr>
                        <th scope="row">Emergency Contact Number</th>
                        <td><a href="tel:<?php echo $patient_row['emergency_contact_phone']; ?>"><?php echo $patient_row['emergency_contact_phone']; ?></a></td>
                    </tr>
                    <tr>
                        <th scope="row">Address Street</th>
                        <td><?php echo $patient_row['address_street']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Address State</th>
                        <td><?php echo $patient_row['address_state']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Address Postal Code</th>
                        <td><?php echo $patient_row['address_postal_code']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Address Country</th>
                        <td><?php echo $patient_row['address_country']; ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
<?php
       
    }else {
        echo "<script>alert('Patient not found.'); window.location.href = 'appointments.php';</script>";
        }

    } else {
        echo "<script>alert('Appointment not found.'); window.location.href = 'appointments.php';</script>";
    }
} else {
        echo "<script>alert('Invalid request.'); window.location.href = 'appointments.php';</script>";
}
?>
            <script src="../js/admin.js"></script>
<?php include('../templates/footer.php'); ?>