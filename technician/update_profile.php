<?php
session_start();
@include '../config.php';

if (!isset($_SESSION['technician_id'])) {
    header('location:../../login_form.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $technician_id = $_POST['technician_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $specialization = $_POST['technician_specialization'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $street = $_POST['address_street'];
    $city = $_POST['address_city'];
    $state = $_POST['address_state'];
    $postal = $_POST['address_postal_code'];
    $country = $_POST['address_country'];
    $phone = $_POST['phone_number'];
    $nic = $_POST['nic'];


    $check_query = "SELECT id FROM user_form WHERE email = ? AND id != ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("si", $email, $technician_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $message = "Email already exists. Please choose a different email.";
        echo "<script type='text/javascript'>alert('$message'); window.location='edit_profile.php';</script>";
        exit;
    }

    $query = "UPDATE user_form SET name=?, email=?, gender=?, dob=?, address_street=?, address_city=?, address_state=?, address_postal_code=?, address_country=?, phone_number=?, nic=?, technician_specialization=?  WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssssssi", $name, $email, $gender, $dob, $street, $city, $state, $postal, $country, $phone, $nic, $specialization, $technician_id);
    

    if ($stmt->execute()) {
        header('location:technician_page.php?success=Technician details updated successfully.');
        exit;
    } else {
        header('location:technician_page.php?error=Failed to update Technician details.');
        exit;
    }
} else {
    header('location:technician_page.php');
    exit;
}
?>
