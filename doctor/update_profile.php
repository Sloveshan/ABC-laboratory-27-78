<?php
session_start();
@include '../config.php';

if (!isset($_SESSION['doctor_id'])) {
    header('location:../../login_form.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_id = $_POST['doctor_id'];
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
    $medical_license_number = $_POST['medical_license_number'];
    $doc_specialty = $_POST['doc_specialty'];

    $check_query = "SELECT id FROM user_form WHERE email = ? AND id != ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("si", $email, $doctor_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $message = "Email already exists. Please choose a different email.";
        echo "<script type='text/javascript'>alert('$message'); window.location='edit_profile.php';</script>";
        exit;
    }

    $query = "UPDATE user_form SET name=?, email=?, gender=?, dob=?, address_street=?, address_city=?, address_state=?, address_postal_code=?, address_country=?, phone_number=?, nic=?, medical_license_number=?, doc_specialty=?  WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssssssssi", $name, $email, $gender, $dob, $street, $city, $state, $postal, $country, $phone, $nic, $medical_license_number, $doc_specialty,  $doctor_id);
    

    if ($stmt->execute()) {
        header('location:doctor_page.php?success=Doctor details updated successfully.');
        exit;
    } else {
        header('location:doctor_page.php?error=Failed to update Doctor details.');
        exit;
    }
} else {
    header('location:doctor_page.php');
    exit;
}
?>
