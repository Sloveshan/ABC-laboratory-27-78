<?php
session_start();
@include '../config.php';

if (!isset($_SESSION['doctor_id'])) {
   header('location:../../login_form.php');
   exit;
}

$query = "SELECT * FROM appointments";
$result = $conn->query($query);


$pageTitle = 'All Appointments';
include('../templates/header.php');

function include_css() {
    echo '<link rel="stylesheet" type="text/css" href="../css/dashboard.css">';
}include_css();
?>

<button id="toggle_menu" class="btn btn-danger rounded"><span aria-hidden="true">Toggle Panel</span></button>
<div class="dashboard_sidebar hide" id="dashboard_sidebar" >
    <h1>Doctor Panel</h1>
    <ul>
        <li><a href="doctor_page.php">Medical Profile</a></li>
        <li class="active_li"><a href="appointments.php">Appointments</a></li>
        <li><a href="edit_profile.php">Edit Profile</a></li>
        <li><a href="change_password.php">Change Password</a></li>
        <li><a href="tests.php">Tests</a></li>
    </ul>
</div>
<div class="dashboard_content" id="dashboard_content">
    <div class="dashboard_header">
        <h2 class="h5">Manage <span class="text-danger"> Appointments</span></h2>
        <div>
        <a href="../logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <div class="appointments_list">
        <?php if ($result->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Test Type</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Full Details</th>
                        <th>Availability</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['appointment_id']; ?></td>
                            <td><?php echo $row['test_type']; ?></td>
                            <td><?php echo $row['date']; ?></td>
                            <td><?php echo $row['time']; ?></td>
                            <td><a href="single_appointment.php?appointment_id=<?php echo $row['appointment_id']; ?>" class="btn btn-primary">More Info</a></td>
                            <td>
                                <?php if ($row['doctor_availability'] === 'yes' && $row['payment_status'] === 'Paid'): ?>
                                    <button class="btn btn-success approve-btn" disabled>Appointed</button>
                                <?php else: ?>
                                    <button class="btn <?php echo $row['doctor_availability'] === 'yes' ? 'btn-secondary' : ($row['doctor_availability'] === 'no' ? 'btn-warning' : 'btn-warning'); ?> approve-btn" data-appointment-id="<?php echo $row['appointment_id']; ?>" <?php echo $row['doctor_availability'] === 'yes' ? 'disabled' : ''; ?>>
                                        <?php echo $row['doctor_availability'] === 'yes' ? 'Approved' : 'Approve'; ?>
                                    </button>
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No appointments found.</p>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var approveButtons = document.querySelectorAll('.approve-btn');
    approveButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var appointmentId = this.getAttribute('data-appointment-id');
            var confirmation = confirm("You cannot cancel this appointment once approved, Are you sure you want to approve this appointment? ");
            if (confirmation) {
                approveAppointment(appointmentId, this);
                location.reload(); 
            }
        });
    });

    function approveAppointment(appointmentId, button) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '<?php echo $_SERVER["PHP_SELF"]; ?>', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log(xhr.responseText);
                    button.textContent = 'Approved';
                    button.classList.remove('btn-danger');
                    button.classList.add('btn-success');
                } else {
                    console.error('Error:', xhr.statusText);
                }
            }
        };
        xhr.send('approve_id=' + appointmentId);
    }
});
</script>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['approve_id'])) {
        $appointmentId = $_POST['approve_id'];
        
        $stmt = $conn->prepare("UPDATE appointments SET doctor_availability = 'yes' WHERE appointment_id = ?");
        $stmt->bind_param("i", $appointmentId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $doctorIdQuery = "SELECT id FROM user_form WHERE user_type = 'doctor'";
            $doctorIdResult = $conn->query($doctorIdQuery);
            if ($doctorIdResult->num_rows > 0) {
                $doctorRow = $doctorIdResult->fetch_assoc();
                $doctorId = $doctorRow['id'];

                $updateAppointmentQuery = "UPDATE appointments SET doctor_id = ? WHERE appointment_id = ?";
                $updateStmt = $conn->prepare($updateAppointmentQuery);
                $updateStmt->bind_param("ii", $doctorId, $appointmentId);
                $updateStmt->execute();

                echo "Appointment approved successfully.";
            } else {
                echo "Failed to find the doctor ID.";
            }
        } else {
            echo "Failed to approve appointment.";
        }
    }
} else {
    echo "Invalid request method.";
}
?>

<script src="../js/admin.js"></script>
<?php include('../templates/footer.php'); ?>


