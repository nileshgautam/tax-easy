<!doctype html>
<html lang="en">
 
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo isset($title)?$title.' | '.BRAND:''?></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo base_url()?>/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="<?php echo base_url()?>/assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url()?>/assets/libs/css/style.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <style>
    html,
    body {
        height: 100%;
    }

    body {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
    }
    </style>
</head>

<body>
    <!-- ============================================================== -->
    <!-- forgot password  -->
    <!-- ============================================================== -->
    <div class="splash-container">
        <div class="card">
            <div class="card-header text-center">
                <!-- <img class="logo-img" src="<?php echo base_url(LOGO) ?>" alt="logo"> -->
                <a class="navbar-brand" href="#"><?php echo BRAND ?></a>
                <span class="splash-description">Please enter your user information.</span></div>
            <div class="card-body">
                <form>
                    <p>Don't worry, we'll send you an email to reset your password.</p>
                    <div class="form-group">
                        <input class="form-control form-control-lg" type="email" name="email" required="" placeholder="Your Email" autocomplete="off">
                    </div>
                    <div class="form-group pt-1"><a class="btn btn-block btn-primary btn-xl" href="#">Reset Password</a></div>
                </form>
            </div>
            <div class="card-footer text-center">
                <span>Don't have an account? <a href="<?php echo base_url('signup')?>">Sign Up</a></span>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- end forgot password  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <script src="<?php echo base_url()?>/assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="<?php echo base_url()?>/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
</body>

 
</html>