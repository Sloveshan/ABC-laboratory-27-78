<?php
session_start();
include '../../config.php';

if (!isset($_SESSION['admin_name'])) {
    header('location:../../login_form.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['technician_id']) && isset($_POST['name']) && isset($_POST['email'])) {
        $technician_id = mysqli_real_escape_string($conn, $_POST['technician_id']);
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $techSpecialty = mysqli_real_escape_string($conn, $_POST['technician_specialization']);
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


        $check_query = "SELECT id FROM user_form WHERE email = '$email' AND id != '$technician_id'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $message = "Email already exists. Please choose a different email.";
            echo "<script type='text/javascript'>alert('$message'); window.location='manage_technicians.php';</script>";
            exit;
        }

        if (!empty($password) && $password != $cpassword) {
            echo "<script type='text/javascript'>alert('Passwords do not match.'); window.location='manage_technicians.php';</script>";
            exit;
        }

        $pass = !empty($password) ? md5($password) : '';

        $query = "UPDATE user_form SET name = '$name', email = '$email', gender = '$gender', dob = '$dob', address_street = '$street', address_city = '$city', address_state = '$state', address_postal_code = '$postal', address_country = '$country', phone_number = '$phone', nic = '$nic', technician_specialization = '$techSpecialty'";
        
        if (!empty($password)) {
            $query .= ", password = '$pass'";
        }
        
        $query .= " WHERE id = '$technician_id' AND user_type = 'technician'";
        
        $result = mysqli_query($conn, $query);

        if ($result) {
            header('location:manage_technicians.php?success=Technician details updated successfully.');
            exit;
        } else {
            header('location:edit_technician.php?id='.$technician_id.'&error=Failed to update Technician details.');
            exit;
        }
    } else {
        header('location:manage_technicians.php?error=Invalid request. Please provide all required fields.');
        exit;
    }
} else {
    header('location:manage_technicians.php');
    exit;
}
?>
