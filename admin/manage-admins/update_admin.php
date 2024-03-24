<?php
session_start();
include '../../config.php';

if (!isset($_SESSION['admin_name'])) {
    header('location:../../login_form.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['admin_id']) && isset($_POST['name']) && isset($_POST['email'])) {
        $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone_number']);
        $password = isset($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : '';
        $cpassword = isset($_POST['cpassword']) ? mysqli_real_escape_string($conn, $_POST['cpassword']) : '';


        $check_query = "SELECT id FROM user_form WHERE email = '$email' AND id != '$admin_id'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $message = "Email already exists. Please choose a different email.";
            echo "<script type='text/javascript'>alert('$message'); window.location='manage_admins.php';</script>";
            exit;
        }

        if (!empty($password) && $password != $cpassword) {
            echo "<script type='text/javascript'>alert('Passwords do not match.'); window.location='manage_admins.php';</script>";
            exit;
        }

        $pass = !empty($password) ? md5($password) : '';

        $query = "UPDATE user_form SET name = '$name', email = '$email', phone_number = '$phone'";
        
        if (!empty($password)) {
            $query .= ", password = '$pass'";
        }
        
        $query .= " WHERE id = '$admin_id' AND user_type = 'admin'";
        
        $result = mysqli_query($conn, $query);

        if ($result) {
            header('location:manage_admins.php?success=Admin details updated successfully.');
            exit;
        } else {
            header('location:edit_admin.php?id='.$admin_id.'&error=Failed to update Admin details.');
            exit;
        }
    } else {
        header('location:manage_admins.php?error=Invalid request. Please provide all required fields.');
        exit;
    }
} else {
    header('location:manage_admins.php');
    exit;
}
?>
