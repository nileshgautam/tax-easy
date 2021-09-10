<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">New Notification</h2>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('notification') ?>" class="breadcrumb-link">Notification</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">New</a></li>

                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <h5 class="card-header">New</h5>
            <div class="card-body">
                <div class="notification">
                </div>
                <form id="notification-form">
                    <div class="form-group">
                        <label for="notification-description">Notice</label>
                        <textarea class="form-control" id="notification-description" rows="7" name="notification-description"></textarea>
                    </div>
                    <div class="border-top py-2">
                        <div class="form-group float-right">
                            <button type="submit" class="btn btn-outline-success save-notification">Save changes</button>
                            <a href="<?php echo base_url('notification') ?>" class="btn btn-outline-danger">Back to Notification</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>