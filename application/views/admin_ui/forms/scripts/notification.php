<script>
    $(function() {

        let BASEURL = "<?php echo base_url() ?>";
        // function load notification
        const showRow = (dataArr=null) => {
            let data = [];
            if (dataArr!=null) {
                dataArr.forEach(nt => {
                    let cdate = nt.created_datetime;
                    cd = cdate.split(" ");
                    date = cd[0].split("-").reverse().join("/");

                    let action = `<td class="text-right">
                        <a class="btn btn-danger btn-sm delete-notification" href="#" id=${nt.id}>
                          <i class="fas fa-trash">
                          </i>
                        </a>
                      </td>`
                    row = [nt.notification, date, action];
                    data.push(row)

                });
            }
            $("#notification-tbody").dataTable().fnDestroy()
            $('#notification-tbody').dataTable({
                aaData: data,
            });
        }

        const loadNotifcation = () => {
            let siteUrl = BASEURL + 'api/Notification/';
            $.get(siteUrl).done(function(data) {
                let dataArr = data.data;
                showRow(dataArr);
            }).fail(function(jqxhr, data) {
                if(jqxhr.responseJSON.status==404){
                    showRow();
                }
            });
        }

        $('body').on('click', '.delete-notification', function() {
            let txt;
            let id = $(this).attr('id');
            let siteUrl = BASEURL + 'api/Notification/delete';
            var r = confirm("Are you sure want to delete!");
            if (r == true) {
                $.post(siteUrl, {
                    id
                }).done(function(data) {
                    // setTimeout(() => {
                    //     window.location.reload();
                    // }, 300)
                    showRow();
                }).fail(function(jqxhr, data) {
                    showRow();
                    // console.log(jqxhr);
                });
            } else {
                return false;
            }
        });
        // Save notification;
        $('#notification-form').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            let url = '<?php echo base_url('api/Notification/insert') ?>';
            $.post(url, formData).done(function(data) {
                let notification = `<div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Success!</strong> ${data.message}.
                    </div>`;
                $('.notification').html(notification);
            }).fail(function(jqxhr, data) {
                let notification = `<div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Danger!</strong> ${jqxhr.responseJSON.message}.
                    </div>`;
                $('.notification').html(notification);
            });
        });

        loadNotifcation();

    });
</script>