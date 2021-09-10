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
        const showRow = (dataArr) => {
            let data = [];
            if (dataArr) {
                dataArr.forEach(i => {
                    // <a class="btn btn-danger btn-sm delete-subadmin" href="#" id=${i.id}>
                    //       <i class="fas fa-trash">
                    //       </i>
                    let action=`<td class="text-right">
                          <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle btn-sm">Action</button>
                                                    <div class="dropdown-menu"><a href="#" class="dropdown-item edit-customer" id=${i.id}><i class="fas fa-edit" aria-hidden="true"></i> Edit</a><a href="#" class="dropdown-item delete-customer" id=${i.id}><i class="fas fa-trash-alt" aria-hidden="true"></i> Delete</a><a href="#" class="dropdown-item assign-subadmin" id=${i.id}><i class="fas fa-location-arrow" aria-hidden="true"> </i> Assign to subadmin</a>
                                                      
                                                    </div>
                        </a>
                      </td>`;
                      console.log(i);
                      let status=(i.status==true)?'Active':'Deactive';
                      let name= i.first_name +' '+i.last_name;
                    row=[i.username,name,i.email,i.mobile,status,action];
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
                console.log(jqxhr);
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
                let url = '<?php echo base_url('api/Customer/insert') ?>';
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
    });
</script>