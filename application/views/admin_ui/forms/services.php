<!-- ============================================================== -->
<!-- subadmin form  -->
<!-- ============================================================== -->
<form class="splash-container" id="subadmin-form">
    <div class="card">
        <div class="card-header">
            <div class="" id="subadmin-notify" style="display: none;">
            </div>
            <h3 class="mb-1">Sub Admin Registrations Form</h3>
            <p id="subadmin-notify">Please enter your user information.</p>

        </div>
        <div class="card-body">
            <div class="form-group">
                <input class="form-control form-control-lg" type="text" required="" placeholder="First Name" name="fname">
            </div>
            <div class="form-group">
                <input class="form-control form-control-lg" required="" placeholder="Last Name" type="text" name="lname">
            </div>
            <div class="form-group">
                <input class="form-control form-control-lg" type="email" name="email" required="" placeholder="E-mail" autocomplete="off">
            </div>
            <div class="form-group">
                <input class="form-control form-control-lg" type="mobile" name="mobile" required="" placeholder="Mobile" autocomplete="off" maxlength="15" minlength="10">
            </div>
            <div class="form-group">
                <input class="form-control form-control-lg" id="password" type="password" name="password" required="" placeholder="Password" >
               
            </div>
            <div class="form-group">
                <input class="form-control form-control-lg" type="password" name="cnfpassword" required="" id="cnfpassword" placeholder="Confirm Password">
                <p id="subadmin-notify-p"></p>
            </div>
            <div class="form-group pt-2">
                <button class="btn btn-block btn-primary" type="submit">Submit</button>
            </div>

        </div>

    </div>
</form>