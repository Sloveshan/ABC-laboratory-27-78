<?php
session_start();
include '../../config.php';

if (!isset($_SESSION['admin_name'])) {
    header('location:../../login_form.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['doctor_id']) && isset($_POST['name']) && isset($_POST['email'])) {
        $doctor_id = mysqli_real_escape_string($conn, $_POST['doctor_id']);
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $medicalLicense = mysqli_real_escape_string($conn, $_POST['medical_license_number']);
        $docSpecialty = mysqli_real_escape_string($conn, $_POST['doc_specialty']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone_number']);
        $dob = mysqli_real_escape_string($conn, $_POST['dob']);
        $nic = mysqli_real_escape_string($conn, $_POST['nic']);
        $street = mysqli_real_escape_string($conn, $_POST['address_street']);
        $city = mysqli_real_escape_string($conn, $_POST['address_city']);
        $state = mysqli_real_escape_string($conn, $_POST['address_state']);
        $postal = mysqli_real_escape_string($conn, $_POST['address_postal_code']);
        $country = mysqli_real_escape_string($conn, $_POST['address_country']);
        $password = isset($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : '';
        $cpassword = isset($_POST['cpassword']) ? mysqli_real_escape_string($conn, $_POST['cpassword']) : '';


        $check_query = "SELECT id FROM user_form WHERE email = '$email' AND id != '$doctor_id'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $message = "Email already exists. Please choose a different email.";
            echo "<script type='text/javascript'>alert('$message'); window.location='manage_doctor.php';</script>";
            exit;
        }

        if (!empty($password) && $password != $cpassword) {
            echo "<script type='text/javascript'>alert('Passwords do not match.'); window.location='manage_doctor.php';</script>";
            exit;
        }

        $pass = !empty($password) ? md5($password) : '';

        $query = "UPDATE user_form SET name = '$name', email = '$email', gender = '$gender', dob = '$dob', address_street = '$street', address_city = '$city', address_state = '$state', address_postal_code = '$postal', address_country = '$country', phone_number = '$phone', nic = '$nic', medical_license_number = '$medicalLicense', doc_specialty = '$docSpecialty'";
        
        if (!empty($password)) {
            $query .= ", password = '$pass'";
        }
        
        $query .= " WHERE id = '$doctor_id' AND user_type = 'doctor'";
        
        $result = mysqli_query($conn, $query);

        if ($result) {
            header('location:manage_doctor.php?success=Doctor details updated successfully.');
            exit;
        } else {
            header('location:edit_doctor.php?id='.$doctor_id.'&error=Failed to update doctor details.');
            exit;
        }
    } else {
        header('location:manage_doctor.php?error=Invalid request. Please provide all required fields.');
        exit;
    }
} else {
    header('location:manage_doctor.php');
    exit;
}
?>
