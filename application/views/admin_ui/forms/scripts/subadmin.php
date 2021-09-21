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
            let data = [];
            if (dataArr != null) {
                dataArr.forEach(i => {
                    let action = `<td class="text-right">
                    <a class="btn btn-primary btn-sm" href="${BASEURL+'subadmin-form/'+ btoa(i.id)}" id=${i.id}>
                          <i class="fas fa-edit">
                          </i>
                        </a>
                        <a class="btn btn-danger btn-sm delete-subadmin" href="#" id=${i.id}>
                          <i class="fas fa-trash">
                          </i>
                        </a>
                        <a class="btn btn-info btn-sm permission-subadmin-modal"  title="Give permission" data-toggle="modal" data-target="#permissionModal" href="#" id=${i.id}>
                          <i class="fas fa-check">
                          </i>
                        </a>
                      </td>`;
                    let name = i.first_name + ' ' + i.last_name;
                    row = [i.username, name, i.email, i.mobile, action];
                    data.push(row)

                });
            }
            $("#subadmin-table").dataTable().fnDestroy()
            $('#subadmin-table').dataTable({
                aaData: data,
            });
        }
        
        const loadSubAdmin = () => {
            let siteUrl = BASEURL + 'api/Subadmin/';
            $.get(siteUrl).done(function(data) {
                let dataArr = data.data;
                showRow(dataArr);
            }).fail(function(jqxhr, data) {
                showRow();
            });
        }
        loadSubAdmin();

        $('body').on('click', '.delete-subadmin', function() {
            let txt;
            let id = $(this).attr('id');
            let siteUrl = BASEURL + 'api/Subadmin/delete';
            var r = confirm("Are you sure want to delete!");
            if (r == true) {
                $.post(siteUrl, {
                    id
                }).done(function(data) {
                    setTimeout(() => {
                        loadSubAdmin();
                    }, 300);
                }).fail(function(jqxhr, data) {
                    console.log(jqxhr);
                });
            } else {
                return false;
            }
        });

        // Save subadmin;
        $('#subadmin-form').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            if (!error) {
                let url = ($('#userid').val() == '') ? BASEURL + 'api/Subadmin/insert' : BASEURL + 'api/Subadmin/update';
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

        $('body').on('click', '.permission-subadmin-modal', function() {
            let userid = $(this).attr('id');
            $('#userid').val(userid);
            const url = BASEURL + 'api/Subadmin/get_permission';
            $.post(url, {
                userid
            }).done(function(res) {
                let checkbox = `<div class="checkbox-container">                              
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="itr" value="itr"><span class="custom-control-label">ITR</span>
                                </label>
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="gst" value="gst"><span class="custom-control-label">GST</span>
                                </label>
                                </div>`;
                if (res.data != null) {
                    per = JSON.parse(res.data);
                    checkbox = `<div class="checkbox-container">                              
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" ${(per[0].itr=='true')?'checked=true':''} class="custom-control-input permission"  id="itr" value="itr"><span class="custom-control-label">ITR</span>
                                </label>
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input permission" id="gst" ${(per[1].gst=='true')?'checked="true"':''} value="gst"><span class="custom-control-label">GST</span>
                                </label>
                                </div>`;
                }
                $('#give-permission-modal').html(checkbox);
            }).fail(function(jqxhr, err) {
                console.log(jqxhr);
            });
        });

        $('body').on('click', '#save-permission', function() {
            let permission = [];
            let userid = $('#userid').val();
            if ($('#itr').prop('checked')) {
                let item = {
                    "itr": "true"
                }
                permission.push(item);
            } else {
                let item = {
                    "itr": "false"
                }
                permission.push(item);
            }
            if ($('#gst').prop('checked')) {
                let item = {
                    "gst": "true"
                }
                permission.push(item);
            } else {
                let item = {
                    "gst": "false"
                }
                permission.push(item);
            }

            const url = BASEURL + 'api/Common/subadmin_permission';
            if (permission.length == 0) {
                permission = [{
                        "itr": "false"
                    },
                    {
                        "gst": "false"
                    }
                ];
            }
            let formdata = {
                userid,
                permission
            }
            $.post(url, formdata).done(function(data) {
                let alert = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Permission granted!.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>`;
                $('#notification').html(alert);
            }).fail(function(jqxhr, data) {
                let alert = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Warning!</strong>${jqxhr.responseJSON.message}.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>`;
                $('#notification').html(alert);
            })


        });
    });
</script>