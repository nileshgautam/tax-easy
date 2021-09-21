<div class="dashboard-header">
    <nav class="navbar navbar-expand-lg bg-white fixed-top">
        <a class="navbar-brand" href="<?php echo base_url('dashboard') ?>"><?php echo BRAND ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse " id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto navbar-right-top">
                <li class="nav-item">
                    <div id="custom-search" class="top-search-bar">
                        <input class="form-control" type="text" placeholder="Search..">
                    </div>
                </li>
                <li class="nav-item dropdown nav-user">
                    <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="assets/images/avatar-1.jpg" alt="" class="user-avatar-md rounded-circle"></a>
                    <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
                        <div class="nav-user-info">
                            <h5 class="mb-0 text-white nav-user-name"><?php
                                                                        $name = $this->session->userdata('name');
                                                                        echo isset($name) ? $name : ''; ?> </h5>
                            <span class="status"></span><span class="ml-2">Available</span>
                        </div>

                        <!-- <a class="dropdown-item" href="#"><i class="fas fa-cog mr-2"></i>Setting</a> -->
                        <a class="dropdown-item" href="<?php echo base_url('logout') ?>"><i class="fas fa-power-off mr-2"></i>Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>
<!-- ============================================================== -->
<!-- end navbar -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- left sidebar -->
<!-- ============================================================== -->

<?php
$role = $this->session->userdata('role');
$permission = json_decode($this->session->userdata('permission'), true);
$show="display:none";
$showITR = "display:none";
$showGST = "display:none";
if ($role == 1) {
    $show="display:block";
    $showITR = "display:block";
    $showGST = "display:block";
} else {
    if ($permission[0]['itr'] == 'true') {
        $showITR = "display:block";
    }
    if ($permission[1]['gst'] == 'true') {
        $showGST = "display:block";
    }
}
?>

<div class="nav-left-sidebar sidebar-dark">
    <div class="menu-list">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="d-xl-none d-lg-none" href="<?php echo base_url('dashboard') ?>">Dashboard</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav flex-column">
                    <li class="nav-divider">
                        Menu
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url('dashboard') ?>"><i class="fa fa-fw fa-user-circle"></i>Dashboard <span class="badge badge-success"></span></a>
                        <div id="submenu-1">
                        </div>
                    </li>

                    <li class="nav-divider">
                        Features
                    </li>
                    <!-- for admin -->
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-5" aria-controls="submenu-5"><i class="fa fa-fw  fa-user-circle"></i> Enquiry </a>
                        <div id="submenu-5" class="collapse submenu">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo base_url('new-enquiry') ?>">New</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo base_url('show-enquiry') ?>">Show All</a>
                                </li>

                            </ul>
                        </div>
                    </li> -->
                    <li class="nav-item" style="<?php echo $show?>">
                        <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-6" aria-controls="submenu-6"><i class="fa fa-fw  fa-user-circle"></i> Users </a>
                        <div id="submenu-6" class="collapse submenu">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo base_url('customers') ?>">Customers</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo base_url('subadmin') ?>">Sub Admins</a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    <li class="nav-item" style="<?php echo $showITR?>">
                        <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-7" aria-controls="submenu-7"><i class="fas fa-fw fa-file"></i> ITR &nbsp;<small>(Yearly)</small></a>
                        <div id="submenu-7" class="collapse submenu">
                            <ul class="nav flex-column">
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="#">Enquiry</a>
                                </li> -->
                                <li class="nav-item" >
                                    <a class="nav-link" href="<?php echo base_url('itr-filing') ?>">ITR Customers
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <!-- <li class="nav-item" style="<?php echo $showGST?>">
                        <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-8" aria-controls="submenu-8"><i class="fas fa-fw fa-file"></i> GST &nbsp;<small>(Monthly)</small> </a>
                        <div id="submenu-8" class="collapse submenu" >
                            <ul class="nav flex-column">
                               
                                <li class="nav-item" >
                                    <a class="nav-link" href="<?php echo base_url('gst-filing') ?>">GST Customers
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li> -->
                    <li class="nav-item" style="<?php echo $show?>">
                        <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-9" aria-controls="submenu-9"><i class="fas fa-fw fa-bell"></i>Notification</small> </a>
                        <div id="submenu-9" class="collapse submenu">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo base_url('new-notice') ?>">New</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo base_url('notification') ?>">Show All
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <!-- For users -->
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-10" aria-controls="submenu-10"><i class="fa fa-fw  fa-user-circle"></i> Services </a>
                        <div id="submenu-10" class="collapse submenu">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo base_url('new-service') ?>">New</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo base_url('show-services') ?>">Show all</a>
                                </li>

                            </ul>
                        </div>
                    </li> -->
                </ul>
            </div>
        </nav>
    </div>
</div>
<!-- ============================================================== -->
<!-- end left sidebar -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- wrapper  -->
<!-- ============================================================== -->
<!-- wrapper  -->
<!-- ============================================================== -->
<div class="dashboard-wrapper">
    <div class="dashboard-ecommerce">
        <div class="container-fluid dashboard-content ">