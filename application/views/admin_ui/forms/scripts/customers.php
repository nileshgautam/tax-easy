<script>
    $(function() {
        let BASEURL = "<?php echo base_url() ?>";
        let error = false;
        $('#cnfpassword').on('keyup', function() {
            if ($('#password').val() == $('#cnfpassword').val()) {
                $('#subadmin-notify-p').html('Matching').css('color', 'green');
                error = false;
            } else {
                $('#subadmin-notify-p').html('Not Matching').css('color', 'red');
                error = true;
            }
        });
        // function load notification
        const showRow = (dataArr = null) => {
            // <a href="${BASEURL+'edit-details/'+btoa(i.id)}" class="dropdown-item edit-customer" id=${i.id}><i class="fas fa-edit" aria-hidden="true"></i> Edit</a>
            let data = [];
            if (dataArr != null) {
                dataArr.forEach(i => {
                    activebtn = `<a href="#" class="dropdown-item toggle_users" status=${i.status} id=${i.id}><i class="fas fa-check" aria-hidden="true"></i> Active</a>`;
                    let deactivatebtn = `<a href="#" class="dropdown-item toggle_users" status=${i.status} id=${i.id}><i class="fas fa-ban" aria-hidden="true"></i> Deactivate</a>`;
                    let active_status = (i.status == '1') ? deactivatebtn : activebtn;
                    let action = `<td class="text-right">
                          <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle btn-sm">Action</button><div class="dropdown-menu">${active_status}<a href="#" class="dropdown-item delete-customer" id=${i.id}><i class="fas fa-trash-alt" aria-hidden="true"></i> Delete</a><a href="#" class="dropdown-item assign-subadmin" id=${i.userid}><i class="fas fa-location-arrow" aria-hidden="true"> </i> Assign to subadmin</a></div>
                        </a>
                      </td>`;
                    let status = (i.status == true) ? 'Active' : 'Deactive';
                    let name = i.first_name + ' ' + i.last_name;
                    row = [i.username, name, i.email, i.mobile, status, action];
                    data.push(row)
                });
            }
            $("#customers-table").dataTable().fnDestroy()
            $('#customers-table').dataTable({
                aaData: data,
            });
        }

        const loadCustomers = () => {
            let siteUrl = BASEURL + 'api/Customer/';
            $.get(siteUrl).done(function(data) {
                let dataArr = data.data;
                showRow(dataArr);
            }).fail(function(jqxhr, data) {
                showRow();
            });
        }

        const loadAllSubadmin = () => {
            let siteUrl = BASEURL + 'api/Subadmin/';
            $.get(siteUrl).done(function(data) {
                let dataArr = data.data;
                let option = `<option value="0">Select sub admin</option>`;
                dataArr.forEach(e => {
                    option += `<option value="${e.userid}">${e.first_name +' '+e.last_name}</option>`;
                });
                $('#select-subadmin').html(option);
            }).fail(function(jqxhr, data) {
                $('#select-subadmin').html(option);
            });
        }

        loadCustomers();

        $('body').on('click', '.delete-customer', function() {
            let txt;
            let id = $(this).attr('id');
            let siteUrl = BASEURL + 'api/Customer/delete';
            var r = confirm("Are you sure want to delete!");
            if (r == true) {
                $.post(siteUrl, {
                    id
                }).done(function(data) {
                    setTimeout(() => {
                        loadCustomers();
                    }, 300);
                }).fail(function(jqxhr, data) {
                    console.log(jqxhr);
                });
            } else {
                return false;
            }
        });

        // Save subadmin;
        $('#customer-form').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            if (!error) {
                
                let url = ($('#userid').val()=='')?BASEURL+'api/Customer/insert':BASEURL+'api/Customer/update';
                $.post(url, formData).done(function(data) {
                    let notification = `<div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Success!</strong> ${data.message}.
                    </div>`;
                    $('#subadmin-notify').html(notification).css('display', 'block');;
                }).fail(function(jqxhr, data) {
                    let notification = `<div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Danger!</strong> ${jqxhr.responseJSON.message}.
                    </div>`;
                    $('#subadmin-notify').html(notification).css('display', 'block');
                });
            } else {
                return false;
            }
        });


        $('body').on('click', '.toggle_users', function() {

            let id = $(this).attr('id');
            let status = $(this).attr('status');

            let message = (status == "1") ? 'Are you sure want to deactivate!' : 'Are you sure want to activate!';
            let siteUrl = BASEURL + 'api/Common/toggle_user';
            var r = confirm(message);
            if (r == true) {
                $.post(siteUrl, {
                    id,
                    status
                }).done(function(data) {
                    setTimeout(() => {
                        loadCustomers();
                    }, 300);
                }).fail(function(jqxhr, data) {
                    console.log(jqxhr);
                });
            } else {
                return false;
            }
        });

        $('body').on('click', '.assign-subadmin', function(e) {
            e.preventDefault();
            let customer_id = this.id;
            $('#hidden_customer_id').val(customer_id);
            loadAllSubadmin();
            $('#assignUser').modal('show');
        });

        $('body').on('click', '.assign-to-subadmin-submitbtn', function() {
            let subamin_id = $('#select-subadmin').val();
            let customer_id = $('#hidden_customer_id').val();
            let siteURL = BASEURL + 'api/common/assign_customer';
            if (subamin_id!='0') {
                $.post(siteURL, {
                    subamin_id,
                    customer_id
                }).done(function(data) {
                    let notification = `<div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Danger!</strong> ${data.message}
                    </div>`;
                    $('#error_assign').html(notification).css('display', 'block');
                }).fail(function(jqxhr, data) {
                    let notification = `<div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Danger!</strong> ${jqxhr.responseJSON.message}.
                    </div>`;
                })
            }else{
                let notification = `<div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Warning!</strong> Please select subadmin.
                    </div>`;
                    $('#error_assign').html(notification).css('display', 'block');
               
                return false;
            }

        });
    });
</script>