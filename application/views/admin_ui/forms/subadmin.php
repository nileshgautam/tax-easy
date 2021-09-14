<?php
$first_name='';
$last_name='';
$email='';
$mobile='';
$id='';
    $hide="required";

if (!empty($users)) {
    $hide="disabled";
    $first_name=(isset($users[0]['first_name']))?$users[0]['first_name']:'';
    $last_name=(isset($users[0]['last_name']))?$users[0]['last_name']:'';
    $email=(isset($users[0]['email']))?$users[0]['email']:'';
    $mobile =(isset($users[0]['mobile']))?$users[0]['mobile']:'';
    $id=(isset($users[0]['userid']))?$users[0]['userid']:'';
} ?>

<!-- ============================================================== -->
<!-- subadmin form  -->
<!-- ============================================================== -->
<form class="splash-container" id="subadmin-form">
    <div class="card">
        <input type="hidden" name="userid" id="userid" value="<?php echo $id?>">
        <div class="card-header">
            <div class="" id="subadmin-notify" style="display: none;">
            </div>
            <h3 class="mb-1">Sub Admin Registrations Form</h3>
            <p id="subadmin-notify">Please enter your user information.</p>
        </div>
        <div class="card-body">
            <div class="form-group">
                <input class="form-control form-control-lg" type="text" required="" placeholder="First Name" name="fname" value="<?php echo $first_name ?>">
            </div>
            <div class="form-group">
                <input class="form-control form-control-lg" required="" placeholder="Last Name" type="text" name="lname" value="<?php echo $last_name ?>">
            </div>
            <div class="form-group">
                <input class="form-control form-control-lg" type="email" name="email" required="" placeholder="E-mail" autocomplete="off" value="<?php echo $email ?>">
            </div>
            <div class="form-group">
                <input class="form-control form-control-lg" type="mobile" name="mobile" required="" placeholder="Mobile" autocomplete="off" maxlength="15" minlength="10" value="<?php echo $mobile ?>">
            </div>
            <div class="form-group" style="">
                <input class="form-control form-control-lg" id="password" type="password" name="password" <?php echo $hide ?> placeholder="Password" >

            </div>
            <div class="form-group" style="<?php echo $hide?>">
                <input class="form-control form-control-lg" type="password" name="cnfpassword" <?php echo $hide?> id="cnfpassword" placeholder="Confirm Password">
                <p id="subadmin-notify-p"></p>
            </div>
            <div class="form-group pt-2">
                <button class="btn btn-block btn-primary" type="submit">Submit</button>
            </div>

        </div>

    </div>
</form>