<?php $pageTitle = 'Documentation'; include('templates/header.php'); ?>
<?php include('templates/navbar.php') ?>
<h2 class="text-center my-5">Multi User Documentation</h2>
<div class="border shadow rounded m-5 p-3 bg-light">
        <h5 class="mb-3">Functionalities as a patient</h5>
        <small>➡️ You can view, edit & change password of your account.</small>
        <br>
        <small>➡️ You can create unlimited new appointments or cancel appointment.</small>
        <br>
        <small>➡️ Appointment can be made by choosing their medical test, date, time & upload their personal document.</small>
        <br>
        <small>➡️ You can make payments using credit/debit card or you can upload paid receipt.</small>
        <br>
        <small>➡️ You can view your transactions & download receipt</small>
        <br>
        <small>➡️ You can view your test completed results & download report</small>
        <br>
        <small>➡️ You can receive 4 kinds of email from this platform, They are: <br>
            1. 'Thank You for Registration' Email Sent Upon Successful Registration<br>
            2. Upon payment submission, a 'Thank You for the Payment' email<br>
            3. After admin confirmation 'Appointment has been confirmed'<br>
            4. After test completed 'Your Test Results Is Available Now'</small>
    </div>
    <div class="border shadow rounded m-5 p-3 bg-light">
        <h5 class="mb-3">Functionalities as a doctor</h5>
        <small>➡️ You can view, edit & change password of your account.</small>
        <br>
        <small>➡️ You can view & approve specific appointment & download report</small>
        <br>
        <small>➡️ You can view Advanced test details & also download test report</small>
    </div>


    <div class="border shadow rounded m-5 p-3 bg-light">
        <h5 class="mb-3">Functionalities as a technician</h5>
        <small>➡️ You can view, edit & change password of your account.</small>
        <br>
        <small>➡️ You can view & approve specific appointment.</small>
        <br>
        <small>➡️ You can create test results, upload tested document & also download test report</small>
    </div>

    <div class="border shadow rounded m-5 p-3 bg-light">
        <h5 class="mb-3">Functionalities of an admin</h5>
        <small>➡️ You can manage (Create, Read, Update, Delete) patients, doctors, technicians & administrators</small>
        <br>
        <small>➡️ You can view each detailed appointments & download specific reports</small>
        <br>
        <small>➡️ You can view each transaction, Confirm or Decline the transaction & download receipt</small>
    </div>
<?php include('templates/footer.php') ?>
