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
            console.log(dataArr);
            if (dataArr) {
                dataArr.forEach(i => {
                    // let cdate = nt.created_datetime;
                    // cd = cdate.split(" ");
                    // date = cd[0].split("-").reverse().join("/");

                    let action=`<td class="text-right">
                        <a class="btn btn-danger btn-sm delete-subadmin" href="#" id=${i.id}>
                          <i class="fas fa-trash">
                          </i>
                        </a>
                      </td>`;
                      let name= i.first_name +' '+i.last_name;
                    row=[i.username,name,i.email,i.mobile,action];
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
                console.log(jqxhr);
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
            console.log(error);
            if (!error) {
                let url = '<?php echo base_url('api/Subadmin/insert') ?>';
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