<?php

if(isset($_POST["submit"])){
    include 'email_config.php';

    $mail->addAddress($_POST["email"]); 

    $mail->isHTML(true);

    $mail->Subject = "Welcome to ABC Laboratories";
    $mail->Body = "Thank You For Registration, " . $_POST["name"] . "<br><br>Now you can login to your dashboard & please update your Medical Profile before making an appointment<br><br><div class=\"border shadow rounded my-4 p-3 bg-light\">
    <h5 class=\"mb-3\">Functionalities as a patient in this platform</h5>
    <small>➡️ You can view, edit & change password of your account.</small>
    <br>
    <small>➡️ You can create unlimited new appointments or cancel appointment.</small>
    <br>
    <small>➡️ You can make payments using credit/debit card or you can upload paid receipt.</small>
    <br>
    <small>➡️ You can view your transactions & download receipt</small>
    <br>
    <small>➡️ You can view your test completed results & download report</small>
</div><br><br> Regards,<br>ABC Medical Laboratories, Crafted By Sloveshan Dayalan (CL/BSCSD/27/78)";

    $mail->send();

}
?>
