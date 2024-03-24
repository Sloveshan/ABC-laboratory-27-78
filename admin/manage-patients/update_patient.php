<?php
session_start();
@include '../../config.php';

if (!isset($_SESSION['admin_name'])) {
    header('location:../../login_form.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['patient_id']) && !empty($_POST['patient_id']) && isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['email']) && !empty($_POST['email'])) {
        $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $dob = mysqli_real_escape_string($conn, $_POST['dob']);
        $street = mysqli_real_escape_string($conn, $_POST['address_street']);
        $city = mysqli_real_escape_string($conn, $_POST['address_city']);
        $state = mysqli_real_escape_string($conn, $_POST['address_state']);
        $postal = mysqli_real_escape_string($conn, $_POST['address_postal_code']);
        $country = mysqli_real_escape_string($conn, $_POST['address_country']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone_number']);
        $nic = mysqli_real_escape_string($conn, $_POST['nic']);
        $medicalConditions = mysqli_real_escape_string($conn, $_POST['medical_conditions']);
        $allergies = mysqli_real_escape_string($conn, $_POST['allergies']);
        $medications = mysqli_real_escape_string($conn, $_POST['medications']);
        $procedures = mysqli_real_escape_string($conn, $_POST['previous_procedures']);
        $history = mysqli_real_escape_string($conn, $_POST['family_medical_history']);
        $emrName = mysqli_real_escape_string($conn, $_POST['emergency_contact_name']);
        $emrRelationship = mysqli_real_escape_string($conn, $_POST['emergency_contact_relationship']);
        $emrPhone = mysqli_real_escape_string($conn, $_POST['emergency_contact_phone']);
        $password = isset($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : '';
        $cpassword = isset($_POST['cpassword']) ? mysqli_real_escape_string($conn, $_POST['cpassword']) : '';


        $check_query = "SELECT id FROM user_form WHERE email = '$email' AND id != '$patient_id'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $message = "Email already exists. Please choose a different email.";
            echo "<script type='text/javascript'>alert('$message'); window.location='manage_patients.php';</script>";
            exit;
        }
        

        if (!empty($password) && $password != $cpassword) {
            echo "<script type='text/javascript'>alert('Passwords do not match.'); window.location='manage_patients.php';</script>";
            exit;
        }
        
        
        $pass = !empty($password) ? md5($password) : '';

        if (!empty($password)) {
            $query = "UPDATE user_form SET name = '$name', email = '$email', gender = '$gender', dob = '$dob', address_street = '$street', address_city = '$city', address_state = '$state', address_postal_code = '$postal', address_country = '$country', phone_number = '$phone', nic = '$nic', medical_conditions = '$medicalConditions', allergies = '$allergies', medications = '$medications', previous_procedures = '$procedures', family_medical_history = '$history', emergency_contact_name = '$emrName', emergency_contact_relationship = '$emrRelationship', emergency_contact_phone = '$emrPhone',  password = '$pass' WHERE id = '$patient_id' AND user_type = 'patient'";
        } else {
            $query = "UPDATE user_form SET name = '$name', email = '$email', gender = '$gender', dob = '$dob', address_street = '$street', address_city = '$city', address_state = '$state', address_postal_code = '$postal', address_country = '$country', phone_number = '$phone', nic = '$nic', medical_conditions = '$medicalConditions', allergies = '$allergies', medications = '$medications', previous_procedures = '$procedures', family_medical_history = '$history', emergency_contact_name = '$emrName', emergency_contact_relationship = '$emrRelationship', emergency_contact_phone = '$emrPhone' WHERE id = '$patient_id' AND user_type = 'patient'";
        }
        
        $result = mysqli_query($conn, $query);

        if ($result) {
            header('location:manage_patients.php?success=Patient details updated successfully.');
            exit;
        } else {
            header('location:edit_patient.php?id='.$patient_id.'&error=Failed to update patient details.');
            exit;
        }
    } else {
        header('location:manage_patients.php?error=Invalid request. Please provide all required fields.');
        exit;
    }
} else {
    header('location:manage_patients.php');
    exit;
}
?>
