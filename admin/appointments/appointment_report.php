<?php
session_start();
@include '../../config.php';

if (!isset($_SESSION['admin_name'])) {
    header('location:../../login_form.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['appointment_id'])) {
    $appointmentId = $_GET['appointment_id'];

    $stmt = $conn->prepare("SELECT appointments.*, patient.*
                            FROM appointments 
                            LEFT JOIN user_form AS patient ON appointments.patient_id = patient.id
                            WHERE appointments.appointment_id = ?");
    $stmt->bind_param("i", $appointmentId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        ob_start();

        echo "Full Report of this appointment \n\n";
        echo "Appointment ID: " . htmlspecialchars($row['appointment_id']) . "\n";
        echo "Test Type: " . htmlspecialchars($row['test_type']) . "\n";
        echo "Price: " . htmlspecialchars($row['price']) . "\n";
        echo "Date of test: " . htmlspecialchars($row['date']) . "\n";
        echo "Time of test: " . htmlspecialchars($row['time']) . "\n";
        echo "Message From Patient: " . htmlspecialchars($row['patient_message']) . "\n";
        echo "Appointment Status: " . htmlspecialchars($row['appointment_status']) . "\n";
        echo "Payment Status: " . htmlspecialchars($row['payment_status']) . "\n";
        echo "Doctor Availability: " . htmlspecialchars($row['doctor_availability']) . "\n";
        echo "Technician Availability: " . htmlspecialchars($row['technician_availability']) . "\n\n\n";

        echo "---------- Patient Details ---------- \n\n";
        echo "Patient ID: " . htmlspecialchars($row['id']) . "\n";
        echo "Patient Name: " . htmlspecialchars($row['name']) . "\n";
        echo "Patient Email: " . htmlspecialchars($row['email']) . "\n";
        echo "Patient Phone Number: " . htmlspecialchars($row['phone_number']) . "\n";
        echo "Patient Gender: " . htmlspecialchars($row['gender']) . "\n";
        echo "Patient Date Of Birth: " . htmlspecialchars($row['dob']) . "\n";
        echo "Patient NIC: " . htmlspecialchars($row['nic']) . "\n";
        echo "Patient Medical Conditions: " . htmlspecialchars($row['medical_conditions']) . "\n";
        echo "Patient Allergies: " . htmlspecialchars($row['allergies']) . "\n";
        echo "Patient Medications: " . htmlspecialchars($row['medications']) . "\n";
        echo "Patient Previous Procedures: " . htmlspecialchars($row['previous_procedures']) . "\n";
        echo "Patient Family Medical History: " . htmlspecialchars($row['family_medical_history']) . "\n";
        echo "Patient Emergency Contact Name: " . htmlspecialchars($row['emergency_contact_name']) . "\n";
        echo "Patient Emergency Contact Relationship: " . htmlspecialchars($row['emergency_contact_relationship']) . "\n";
        echo "Patient Emergency Contact Number: " . htmlspecialchars($row['emergency_contact_phone']) . "\n";
        echo "Patient Address: " . htmlspecialchars($row['address_street']) . "\n";
        echo "Patient City: " . htmlspecialchars($row['address_city']) . "\n";
        echo "Patient State: " . htmlspecialchars($row['address_state']) . "\n";
        echo "Patient Postal Code: " . htmlspecialchars($row['address_postal_code']) . "\n";
        echo "Patient Country: " . htmlspecialchars($row['address_country']) . "\n\n";

        $reportContent = ob_get_clean();

        header("Content-type: text/plain");
        header("Content-Disposition: attachment; filename=appointment_report_" . $appointmentId . ".txt");

        echo $reportContent;
    } else {
        header('location:appointment.php');
        exit;
    }
} else {
    header('location:appointment.php');
    exit;
}
?>
