<?php

require_once 'config.php';

class ErrorMessageHandler {
    public static function displayErrors($errors) {
        if(isset($errors)) {
            foreach($errors as $error) {
                echo '<span class="error-msg">' . $error . '</span>';
            }
        }
    }
}

class UserAuthentication {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }


    public function authenticateUser($email, $password) {
        $email = mysqli_real_escape_string($this->conn, $email); 
        $password = md5($password);
        $select = "SELECT * FROM user_form WHERE email = '$email' AND password = '$password'"; 

        $result = mysqli_query($this->conn, $select); 

        if(mysqli_num_rows($result) > 0) { 
            $row = mysqli_fetch_array($result);

            if($row['user_type'] == 'admin') { 
                $_SESSION['admin_name'] = $row['name']; 
                header('location: ./admin/admin_dashboard.php'); 
                exit; 
                
            } elseif($row['user_type'] == 'patient') { 
                $_SESSION['patient_id'] = $row['id']; 
                header('location: ./patient/patient_page.php'); 
                exit; 
            }elseif($row['user_type'] == 'doctor') { 
                $_SESSION['doctor_id'] = $row['id']; 
                header('location: ./doctor/doctor_page.php'); 
                exit; 
            }elseif($row['user_type'] == 'technician') { 
                $_SESSION['technician_id'] = $row['id']; 
                header('location: ./technician/technician_page.php'); 
                exit; 
            }
        } else {
            $error[] = 'Incorrect email or password!'; 
            return $error;
        }
    }
}

session_start();

$error = [];

$userAuth = new UserAuthentication($conn);

if(isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $error = $userAuth->authenticateUser($email, $password);
}

$pageTitle = 'Login'; 
include('templates/header.php'); 
include('templates/navbar.php'); 
function include_css() {
    echo '<link rel="stylesheet" type="text/css" href="./css/style.css">';
}include_css();
?>

<div class="inner_login rounded shadow border my-5">
    <div class="image-holder">
        <img class="login_poster" src="assets/registration-form-1.jpg" alt="Poster">
    </div>
    
    <form action="" method="post" class="login_form">
        <h4>Multi-User Login</h4>
        <span class="text-danger"><?php ErrorMessageHandler::displayErrors($error);?></span>
        <div class="form-wrapper">
            <input name="email" type="email" placeholder="Email Address" required class="form-control p-2 mb-3">
            <i class="fa fa-envelope" aria-hidden="true"></i>
        </div>
        <div class="input-group border rounded mb-3">
            <input type="password" name="password" id="password" class="form-control no-border p-2" placeholder="Enter your password" autocomplete="on" required>
            <button class="btn no-border" type="button" id="togglePassword">
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
            </button>
        </div>
        <input type="submit" name="submit" value="Login" class="btn btn-success w-100 mb-3">
        <a class="text-danger redirect_login_a" href="register_form.php" >Create New Account</a>
    </form>
</div>
<script src="./js/admin.js"></script>
<?php include('./templates/footer.php'); ?>
