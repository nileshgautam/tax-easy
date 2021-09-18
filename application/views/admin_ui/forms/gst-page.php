<!-- ============================================================== -->
<!-- GST form  -->
<!-- ============================================================== -->
<style>
    .upload-btn-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
        cursor: pointer;
    }

    label {
        font-size: 12px;
    }

    /* .card-body .upload-btn {
        border: 2px solid gray;
        color: gray;
        background-color: white;
        padding: 8px 20px;
        border-radius: 8px;
        font-size: 12px;
        cursor: pointer !important;
    } */

    .upload-btn-wrapper input[type=file] {
        font-size: 12px;
        /* position: absolute;
        left: 0;
        top: 0;
        opacity: 0; */
    }
</style>
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-4">
                    <h2 class="pageheader-title">GST User :<span><?php echo isset($user[0]['first_name']) ? $user[0]['first_name'] : '' ?></span></h2>
                </div>
                <div class="col-sm-4">

                </div>
                <div class="col-sm-4">
                    <select class="form-control" id="input-select-fy">
                        <?php 
                        if (!empty($fy)) {

                            $cy=date('Y');
                            $fy=$cy+1;
                            $finY=$cy.'-'. $fy;

                            foreach ($fy as $i) { ?>
                                <option value="<?php echo$i['fy']?>"> <?php echo 'Financial Year '.$i['fy']?></option>
                        <?php }
                        }else{
                            $cy=date('Y');
                            $fy=$cy+1;
                            $finY=$cy.'-'. $fy;
                            ?>
                            <option value="<?php echo $finY?>"> <?php echo 'Financial Year '.$finY?></option>
                       <?php } ?>
                    </select>
                </div>
            </div>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Users</a></li>

                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">GST</a></li>


                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- end pageheader -->
<!-- ============================================================== -->



<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
    <div class="accrodion-regular">
        <div id="accordion3">
            <div class="row" id="gst-calender">


            </div>
        </div>
    </div>
</div>