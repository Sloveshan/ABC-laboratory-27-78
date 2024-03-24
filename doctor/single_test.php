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
                    <li ><a href="appointments.php">Appointments</a></li>
                    <li><a href="edit_profile.php">Edit Profile</a></li>
                    <li><a href="change_password.php">Change Password</a></li>
                    <li class="active_li"><a href="tests.php">Tests</a></li>
                </ul>
            </div>
            <div class="dashboard_content" id="dashboard_content">
                <div class="dashboard_header">
                    <h2 class="h5">Advanced Details of <span class="text-danger">Test</span></h2>
                    <div>
                        <a href="tests.php" class="btn btn-secondary">👈 Back</a>
                        <?php
                            $test_query = "SELECT * FROM tests WHERE appointment_id = $appointmentId";
                            $test_result = $conn->query($test_query);
                            ?>
                                <?php if ($test_result->num_rows > 0): ?>
                                <button id="downloadButton" class="btn btn-success">Download Report</button>
                            <?php else: ?>
                                <button id="downloadButton" class="btn btn-success" disabled>No Details To Download</button>
                            <?php endif; ?>
                        <a href="../logout.php" class="btn btn-danger">Logout</a>
                    </div>
                </div>
                 
                <?php
                    $test_query = "SELECT * FROM tests WHERE appointment_id = $appointmentId";
                    $test_result = $conn->query($test_query);

if ($test_result->num_rows > 0) {
    echo '<div class="col-md-12 order-md-2 my-4 shadow">';
    echo '<ul class="list-group mb-3">';

    while ($test_row = $test_result->fetch_assoc()) {
        echo '<li class="list-group-item d-flex justify-content-between bg-light">';
        echo '<span class="text-danger">Test ID</span>';
        echo '<strong class="text-danger">#' . $test_row['test_id'] . '</strong>';
        echo '</li>';

        echo '<li class="list-group-item d-flex justify-content-between lh-condensed">';
        echo '<div>';
        echo '<h6 class="my-0">Appointment ID</h6>';
        echo '</div>';
        echo '<span class="text-muted">#' . $test_row['appointment_id'] . '</span>';
        echo '</li>';

        echo '<li class="list-group-item d-flex justify-content-between lh-condensed">';
        echo '<div>';
        echo '<h6 class="my-0">Test Type</h6>';
        echo '</div>';
        echo '<span class="text-muted">' . $test_row['test_type'] . '</span>';
        echo '</li>';

        echo '<li class="list-group-item d-flex justify-content-between lh-condensed">';
        echo '<div>';
        echo '<h6 class="my-0">Test Date</h6>';
        echo '</div>';
        echo '<span class="text-muted">' . $test_row['test_date'] . '</span>';
        echo '</li>';

        echo '<li class="list-group-item d-flex justify-content-between lh-condensed">';
        echo '<div>';
        echo '<h6 class="my-0">Test Room</h6>';
        echo '</div>';
        echo '<span class="text-muted">' . $test_row['test_room'] . '</span>';
        echo '</li>';

        echo '<li class="list-group-item d-flex justify-content-between lh-condensed">';
        echo '<div>';
        echo '<h6 class="my-0">Test Result</h6>';
        echo '</div>';
        echo '<span class="text-muted">' . $test_row['test_result'] . '</span>';
        echo '</li>';

        echo '<li class="list-group-item d-flex justify-content-between lh-condensed">';
        echo '<div>';
        echo '<h6 class="my-0">Test Status</h6>';
        echo '</div>';
        echo '<span class="text-muted">' . $test_row['test_status'] . '</span>';
        echo '</li>';

        echo '<li class="list-group-item d-flex justify-content-between lh-condensed">';
        echo '<div>';
        echo '<h6 class="my-0">Test Timestamp</h6>';
        echo '</div>';
        echo '<span class="text-muted">' . $test_row['test_timestamp'] . '</span>';

        if (!empty($test_row['documents'])) {
            $fileName = basename($test_row['documents']);
            $filePath = "../technician/tests/uploads/Test Documents/Appointment ID_" . $appointmentId . "/" . $fileName;
            
            echo '<li class="list-group-item d-flex justify-content-between lh-condensed">';
            echo '<div>';
            echo '<h6 class="my-0">Test Report/Documents</h6>';
            echo '</div>';
            echo '<span class="text-muted"><a href="' . $filePath . '" download>Download</a></span>';
            echo '</li>';
        } else {
            echo '<li class="list-group-item d-flex justify-content-between lh-condensed">';
            echo '<div>';
            echo '<h6 class="my-0">Documents</h6>';
            echo '</div>';
            echo '<span class="text-muted">No documents available</span>';
            echo '</li>';
        }
    }

    echo '</ul>';
    echo '</div>';
} else {
    echo '<div class="col-md-12 order-md-2 my-4">';
    echo '<ul class="list-group mb-3">';
    echo '<li class="list-group-item">No test details available</li>';
    echo '</ul>';
    echo '</div>';
}
?>
                <h4 class="mt-5">Appointment Details</h4>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th scope="row">Appointment ID</th>
                            <td>#<?php echo $row['appointment_id']; ?></td>
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
                                            $file_path = '../../patient/uploads/Patient Previous Medical Documents/Patient ID_' . $patientId . '/' . $filename; // Add a directory separator here
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
                            <td>#<?php echo $row['doctor_id']; ?></td>
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
                            <td>#<?php echo $row['technician_id']; ?></td>
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
                            <td>#<?php echo $row['patient_id']; ?></td>
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
            </div>
        <?php
        } else {
            echo "<script>alert('Patient not found.'); window.location.href = 'appointments.php';</script>";
        }
    } else {
        echo "<script>alert('Appointment not found.'); window.location.href = 'appointments.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href = 'appointments.php';</script>";
}
?>
<script>
document.getElementById('downloadButton').addEventListener('click', function() {
    <?php
    $test_query = "SELECT * FROM tests WHERE appointment_id = $appointmentId";
    $test_result = $conn->query($test_query);

    if ($test_result->num_rows > 0) {
        $test_details = array();
        while ($test_row = $test_result->fetch_assoc()) {
            $test_details[] = $test_row;
        }
        $json_test_details = json_encode($test_details);
        echo "var testDetails = $json_test_details;";
    }
    ?>
    var text = 'Test Report From ABC Laboratory\n\n';
    testDetails.forEach(function(test) {
        for (var key in test) {
            text += key + ': ' + test[key] + '\n';
        }
        text += '\n';
    });
    var blob = new Blob([text], { type: 'text/plain' });
    var link = document.createElement('a');
    link.download = 'test_report.txt';
    link.href = window.URL.createObjectURL(blob);
    link.click();
});
</script>

<script src="../js/admin.js"></script>
<?php include('../templates/footer.php'); ?>