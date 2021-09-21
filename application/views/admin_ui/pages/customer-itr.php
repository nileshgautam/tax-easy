                <!-- pageheader -->
                <!-- ============================================================== -->

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h2 class="pageheader-title">Customer ITR Administration:&nbsp; <?php echo !empty($user) ? $user[0]['first_name'] . ' ' . $user[0]['last_name'] : ''; ?></h2>
                                </div>
                                <div class="col-sm-6"><a href="<?php echo base_url('new-itr/') . base64_encode($user[0]['userid']); ?>" class="btn btn-outline-primary btn-sm float-right">New</a></div>
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
                                    <table class="table table-striped table-bordered first" id="customer-itr-history-table">
                                        <thead>
                                            <tr>
                                                <th>TRID</th>
                                                <th>Year</th>
                                                <th>Payment</th>
                                                <th></th>

                                            </tr>
                                        </thead>
                                        <tbody id="customer-itr-history-table-body"></tbody>

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
                <div class="modal fade" id="showDocHistrory" tabindex="-1" role="dialog" aria-labelledby="showDocHistroryTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="showDocHistroryTitle">Show doc</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="dochistoryfile">

                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- show file -->

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