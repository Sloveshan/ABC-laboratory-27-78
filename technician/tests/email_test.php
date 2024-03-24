<?php

if(isset($_POST["submit"])){
    include '../../email_config.php';

    $mail->addAddress($_POST["email"]); 

    $mail->isHTML(true);

    $mail->Subject = "Your Test Results Is Available Now";
    $mail->Body = "Congratulations on your successful medical test, " . $_POST["name"] . "
    
    <br><br>Our Technician has submitted your test reports & details:
    <br><br>Your Appointment ID: #" . $_POST["appointment_id"] . "<br>
    <br>Date Of Test: " . $_POST["test_date"] . "<br>
    <br>Your Test Type: " . $_POST["test_type"] . "<br>
    <br>Your Test Room: " . $_POST["test_room"] . "<br>
    <br>Your Test Results: " . $_POST["test_result"] . "<br>
    <br>Your Test Status: " . $_POST["test_status"] . "<br>
    <br>Please login to your dashboard to download the reports<br>

<br><br> Regards,<br>ABC Medical Laboratories, Crafted By Sloveshan Dayalan (CL/BSCSD/27/78)";

    $mail->send();

}
?>

