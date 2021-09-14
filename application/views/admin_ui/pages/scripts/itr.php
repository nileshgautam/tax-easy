<script>
    let BASEURL = "<?php echo base_url() ?>";
    const showRow = (dataArr = null) => {
        let data = [];
        if (dataArr != null) {
            dataArr.forEach(i => {
                activebtn = `<td class="text-right">
                        <a class="btn btn-danger btn-sm open-itr" href="#" title="open for ITR" id=${i.id}>
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
        $("#itr-table").dataTable().fnDestroy()
        $('#itr-table').dataTable({
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

    $(function() {
        loadCustomers();
        let cf = getCurrentFiscalYear();
    })
</script>