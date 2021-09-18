<script>
    let BASEURL = "<?php echo base_url() ?>";
    const showRow = (dataArr = null) => {
        let data = [];
        if (dataArr != null) {
            dataArr.forEach(i => {
                activebtn = `<td class="text-right">
                        <a class="btn btn-danger btn-sm open-gst" href="#" title="open for GST" id=${i.userid}>
                          <i class="fas fa-folder">
                          </i>
                        </a>
                      </td>`;
                let status = (i.status == true) ? 'Active' : 'Deactive';
                let name = i.first_name + ' ' + i.last_name;
                row = [i.username, name, i.email, i.mobile, status, activebtn];
                data.push(row)
            });
        }
        $("#gst-table").dataTable().fnDestroy()
        $('#gst-table').dataTable({
            aaData: data,
        });
    }
    const loadCustomers = () => {
        let siteUrl = BASEURL + 'api/Customer/';
        $.get(siteUrl).done(function(data) {
            let dataArr = data.data;
            console.log(dataArr)
            showRow(dataArr);
        }).fail(function(jqxhr, data) {
            showRow();
        });
    }

    const loadGSTmonth=()=>{
        let year= new Date();
    //    console.log(year.getFullYear());
    }

    $(function() {

        loadCustomers();
        loadGSTmonth();




        $('body').on('click', '.open-gst', function() {
            let customer_id = $(this).attr('id');
            window.location.href = BASEURL + 'Users/gst_page/' + btoa(customer_id);
        });

        const today = new Date()
        month = today.toLocaleString('default', {
            month: 'long'
        });

        // console.log(today);


        $('body').on('change', '.upload-sale-doc', function() {
            console.log('sale-doc');
        });

    })
</script>