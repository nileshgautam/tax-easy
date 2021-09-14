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

    .card-body .upload-btn {
        border: 2px solid gray;
        color: gray;
        background-color: white;
        padding: 8px 20px;
        border-radius: 8px;
        font-size: 12px;
        cursor: pointer !important;
    }

    .upload-btn-wrapper input[type=file] {
        font-size: 100px;
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
   

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
                    <select class="form-control" id="input-select">
                        <option>Financial Year 2021-22</option>
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
            <div class="row">

                <?php
                $year = date('y');
                $fy = array('apr-' . $year, 'may-' . $year, 'jun-' . $year, 'july-' . $year, 'aug-' . $year, 'sept-' . $year, 'oct-' . $year, 'nov-' . $year, 'dec-' . $year, 'jan-' . ($year + 1), 'feb-' . ($year + 1), 'march-' . ($year + 1));
                for ($i = 0; $i < count($fy); $i++) {
                ?>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-header" id="<?php echo 'headingSeven' . $i ?>">
                                <h5 class="mb-0">
                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#<?php echo 'collapseSeven' . $i ?>" aria-expanded="false" aria-controls="collapseSeven">
                                        <span class="fas fa-angle-down mr-3"><?php echo ucfirst($fy[$i]) ?></span>
                                    </button>
                                </h5>
                            </div>
                            <div id="<?php echo 'collapseSeven' . $i ?>" class="collapse" aria-labelledby="<?php echo 'headingSeven' . $i ?>" data-parent="#accordion3" style="">
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-sm-12 row">
                                            <div class="col-sm-10">
                                                <label for="myfile">Purchage Documents</label>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="upload-btn-wrapper">
                                                    <button class="upload-btn">Upload</button>
                                                    <input type="file" name="myfile" multiple />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 row">
                                            <div class="col-sm-10">
                                                <label for="myfile">Sales Documents</label>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="upload-btn-wrapper">
                                                    <button class="upload-btn">Upload</button>
                                                    <input type="file" name="myfile" multiple />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 row">
                                            <div class="col-sm-10">
                                                <label for="myfile">Return Calculation</label>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="upload-btn-wrapper">
                                                    <button class="upload-btn">Upload</button>
                                                    <input type="file" name="myfile" multiple />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                  
                                    <div class="row">
                                        <div class="col-sm-12 row">
                                            <div class="col-sm-10">
                                                <label for="myfile">Acknowledgement Slip</label>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="upload-btn-wrapper">
                                                    <button class="upload-btn">Upload</button>
                                                    <input type="file" name="myfile" multiple />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 row">
                                            <div class="col-sm-10">
                                                <label for="myfile">Payment Received <input type="checkbox" name="payment" id="payment"></label>
                                            </div>
                                          
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>


<script>
    $(function() {
        const today = new Date()
        month = today.toLocaleString('default', {
            month: 'short'
        });
        console.log(month);
    })
</script>

<!-- <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
            <div class="card border-3 border-top border-top-primary">
                <div class="card-body">
                    <h5 class="text-muted fy"></h5>
                    <div class="metric-value d-inline-block">
                        <h1 class="mb-1"><a href="#"><?php echo ucfirst($fy[$i]) ?></a></h1>
                    </div>
                    <div class="metric-label d-inline-block float-right text-success font-weight-bold">
                        <span class="icon-circle-small icon-box-xs text-success bg-success-light"><i class="fa fa-fw fa-arrow-up"></i></span><span class="ml-1"></span>
                    </div>
                </div>
            </div>
        </div> -->