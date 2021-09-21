<!-- ============================================================== -->
<!-- GST form  -->
<!-- ============================================================== -->

<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header mb-0">
            <div class="row">
                <div class="col-sm-4">
                    <h2 class="pageheader-title">GST User :<span><?php echo isset($user[0]['first_name']) ? $user[0]['first_name'] : '' ?></span></h2>
                </div>
                <div class="col-sm-4">

                </div>

            </div>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Users</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">ITR</a></li>
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
    <div class="card splash-container">

        <div class="card-body">
            <div class="form-group">
                <input type="hidden" name="userid" id="userid" value="<?php echo !empty($user)?$user[0]['userid']:''?>">
                <label for="input-select-fy">Financial Year</label>
                <select class="form-control" id="input-select-fy">
                    <?php
                    if (!empty($fy)) {
                        foreach ($fy as $i) { ?>
                            <option value="<?php echo $i['fy'] ?>"> <?php echo 'Financial Year ' . $i['fy'] ?></option>
                        <?php }
                    } else {
                        $cy = date('Y');
                        $fy = $cy + 1;
                        $finY = $cy . '-' . $fy;
                        ?>
                        <option value="<?php echo $finY ?>"> <?php echo 'Financial Year ' . $finY ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="upload-doc">Upload Documents</label>
                <input type="file" class="form-control" name="file[]" id="upload-doc" doc-title="documents" multiple>
            </div>
            <div class="form-group">
                <button class="btn btn-info" id="show-doc">Show documents</button>
            </div>
            <div class="form-group">
                <label for="ack">ITR Copy/Acknowledge documents</label>
                <input type="file" name="file[]" class="form-control upload-ack" data-trid="" id="ack" doc-title="acknowledge" multiple required>
            </div>
            <div class="form-group">
                <button class="btn btn-info" data-trid="" id="show-ack-files">Show Acknowledge </button>
            </div>
            <div class="form-group">
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="payment-status"><span class="custom-control-label">Check if payment done</span>
                </label>
            </div>
        </div>

    </div>
</div>

<!-- Modal -->
<div class="modal right fade" id="docModal" tabindex="-1" role="dialog" aria-labelledby="docModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="docModalLabel">ITR Documents</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="documents-files">
                <h6>File not found</h6>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>   
            </div>
        </div>
    </div>
</div>