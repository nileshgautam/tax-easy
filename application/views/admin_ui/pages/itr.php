                <!-- pageheader -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h2 class="pageheader-title">ITR Administration</h2>
                                </div>
                                <!-- <div class="col-sm-6"><a href="<?php //echo base_url('new-customer') ?>" class="btn btn-outline-primary btn-sm float-right">New</a></div> -->
                            </div>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Users</a></li>

                                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">ITR-filling</a></li>


                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end pageheader -->
                <!-- ============================================================== -->
                <div class="row">
                    <!-- ============================================================== -->
                    <!-- basic table  -->
                    <!-- ============================================================== -->
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered first" id="itr-table">
                                        <thead>
                                            <tr>
                                                <th>Profile</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Status</th>
                                                <th></th>

                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Profile</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Status</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- end basic table  -->
                    <!-- ============================================================== -->
                </div>


                <!-- for assign to subadmin -->
                <!-- Modal -->
                <div class="modal fade" id="assignUser" tabindex="-1" role="dialog" aria-labelledby="assignUserTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="assignUserLongTitle">Assign to Sub admin</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="error_assign" style="display: none;">

                                </div>
                              <select name="select-subadmin" id="select-subadmin" class="form-control">
                                <option value="">Select sub admin</option>
                              </select>
                              <input type="hidden" name="hidden_customer_id" id="hidden_customer_id">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn assign-to-subadmin-submitbtn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>