<?php
session_start();
@include '../config.php';

if (!isset($_SESSION['patient_id'])) {
    header('location:../../login_form.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_POST['patient_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $street = $_POST['address_street'];
    $city = $_POST['address_city'];
    $state = $_POST['address_state'];
    $postal = $_POST['address_postal_code'];
    $country = $_POST['address_country'];
    $phone = $_POST['phone_number'];
    $nic = $_POST['nic'];
    $medicalConditions = $_POST['medical_conditions'];
    $allergies = $_POST['allergies'];
    $medications = $_POST['medications'];
    $procedures = $_POST['previous_procedures'];
    $history = $_POST['family_medical_history'];
    $emrName = $_POST['emergency_contact_name'];
    $emrRelationship = $_POST['emergency_contact_relationship'];
    $emrPhone = $_POST['emergency_contact_phone'];

    $check_query = "SELECT id FROM user_form WHERE email = ? AND id != ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("si", $email, $patient_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $message = "Email already exists. Please choose a different email.";
        echo "<script type='text/javascript'>alert('$message'); window.location='edit_profile.php';</script>";
        exit;
    }

    $query = "UPDATE user_form SET name=?, email=?, gender=?, dob=?, address_street=?, address_city=?, address_state=?, address_postal_code=?, address_country=?, phone_number=?, nic=?, medical_conditions=?, allergies=?, medications=?, previous_procedures=?, family_medical_history=?, emergency_contact_name=?, emergency_contact_relationship=?, emergency_contact_phone=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssssssssssssssi", $name, $email, $gender, $dob, $street, $city, $state, $postal, $country, $phone, $nic, $medicalConditions, $allergies, $medications, $procedures, $history, $emrName, $emrRelationship, $emrPhone, $patient_id);
    

    if ($stmt->execute()) {
        header('location:patient_page.php?success=Patient details updated successfully.');
        exit;
    } else {
        header('location:patient_page.php?error=Failed to update patient details.');
        exit;
    }
} else {
    header('location:patient_page.php');
    exit;
}
?>
