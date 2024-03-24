<?php
require_once 'config.php';

class UserRegistration {
    private $conn; 

    public function __construct($conn) {
        $this->conn = $conn; 
    }

    public function registerUser($name, $email, $gender, $dob, $password, $cpassword) {
        $name = mysqli_real_escape_string($this->conn, $name);
        $email = mysqli_real_escape_string($this->conn, $email);
        $gender = mysqli_real_escape_string($this->conn, $gender);
        $dob = mysqli_real_escape_string($this->conn, $dob);
        $pass = md5($password);
        $cpass = md5($cpassword);

        $select = "SELECT * FROM user_form WHERE email = '$email'";
        $result = mysqli_query($this->conn, $select);

        if(mysqli_num_rows($result) > 0) {
            $error[] = 'Email already exists!';
        } else {
            if($pass != $cpass) {
                $error[] = 'Passwords do not match!';
            } else {
                $insert = "INSERT INTO user_form(name, email, gender, dob, password, user_type) VALUES('$name','$email', '$gender', '$dob', '$pass','patient')";
                mysqli_query($this->conn, $insert);
                include 'send_email.php';
                echo "<script>alert('Registered & Email Sent Successfully');</script>";
            }
        }
        return isset($error) ? $error : [];
    }
}

session_start();

$userRegistration = new UserRegistration($conn);

if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    $error = $userRegistration->registerUser($name, $email, $gender, $dob, $password, $cpassword);
} else {
    $error = [];
}

$pageTitle = 'Register'; 
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
        <h4>Patient Registration</h4>
        <span class="text-danger"><?php
            if(!empty($error)){
                foreach($error as $error){
                    echo '<span class="error-msg">'.$error.'</span>';
                }
            }
        ?></span>
        <div class="form-wrapper">
            <input type="text" name="name" placeholder="Name" class="form-control p-2 mb-3" required>
            <i class="fa fa-user f15" aria-hidden="true"></i>
        </div>
        <div class="form-wrapper">
            <input name="email" type="email" placeholder="Email Address" class="form-control p-2 mb-3" required>
            <i class="fa fa-envelope f15" aria-hidden="true"></i>
        </div>
        <div class="form-wrapper">
            <select name="gender" id="gender" required class="form-control p-2 mb-3" style="color: #555;">
                <option value="" disabled selected style="color: #555;">Gender</option>
                <option value="Male" style="color: #555;">Male</option>
                <option value="Female" style="color: #555;">Female</option>
                <option value="Other" style="color: #555;">Other</option>
            </select>
            <i class="fa fa-caret-down" aria-hidden="true"></i>
        </div>
        
        <div class="form-wrapper">
    <input type="date" name="dob" id="dob" class="form-control p-2 mb-3" required style="color: #555;">
    <small id="age_warning" class="text-dark">Age limit at least 18</small>
</div>


        <div class="input-group border rounded mb-3">
            <input type="password" name="password" id="password" class="form-control no-border p-2" placeholder="Enter your password" required autocomplete="on">
            <button class="btn no-border" type="button" id="togglePassword">
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
            </button>
        </div>
        <div class="input-group border rounded mb-3">
            <input type="password" name="cpassword" id="confirmPassword" class="form-control no-border p-2" placeholder="Confirm your password" required autocomplete="on">
            <button class="btn no-border" type="button" id="toggleConfirmPassword">
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
            </button>
        </div>
        <input type="submit" name="submit" value="Register Now" class="btn btn-success w-100 mb-3">
        <a class="text-danger redirect_login_a" href="login_form.php" >Login with Email Address</a>
    </form>
</div>
<script>
    var today = new Date();
    var minDate = new Date();
    minDate.setFullYear(today.getFullYear() - 100);
    var maxDate = new Date();
    maxDate.setFullYear(today.getFullYear() - 18);
    var minDateString = minDate.toISOString().slice(0, 10);
    var maxDateString = maxDate.toISOString().slice(0, 10);
    document.getElementById("dob").setAttribute("min", minDateString);
    document.getElementById("dob").setAttribute("max", maxDateString);
</script>

<script src="./js/admin.js"></script>
<?php include('./templates/footer.php'); ?>
