<script>
    let BASEURL = "<?php echo base_url() ?>";
    let userid = "<?php echo isset($user[0]['userid']) ? $user[0]['userid'] : '' ?>";

    const showRow = (dataArr = null) => {
        let data = [];
        if (dataArr != null) {
            dataArr.forEach(i => {
                activebtn = `<td class="text-right">
                        <a class="btn btn-danger btn-sm open-itr" href="#" title="open for ITR" id=${i.userid}>
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

    const loadHistory = (obj = null) => {
        let html = '';
        if (obj != null) {
            for (item of obj) {
                let paystatus = (item.payment == '0') ? 'Pending' : 'done';
                html += `<tr><td><a href="#" id="${item.transactionid}" class="itrtrid">${item.transactionid}</a></td><td>${item.financial_year_month}</td><td>${paystatus}</td><td></td></tr>`;
            }
        }
        $('#customer-itr-history-table-body').html(html);
    }

    const loadCustomersITRHistory = () => {
        let siteUrl = BASEURL + 'api/Common/getitrhistory';
        $.post(siteUrl, {
            userid
        }).done(function(data) {
            let dataArr = data.data;
            loadHistory(dataArr);
        }).fail(function(jqxhr, data) {
            loadHistory();
        });
    }
    const showDocs = (data) => {
        let fileList = '';
        let files = JSON.parse(data[0].douments);
        if (files != null) {
            for (item of files) {
                fileList += `<div> <a href="${BASEURL+'/'+item.path}" target=_blanck>${item.title}</a></div>`;
            }
            $('#documents-files').html(fileList);
            $('#docModal').modal('show');
        } else {
            $('#documents-files').html(fileList);
        }
    }

    const showACKDocs = (data) => {
        let fileList = '';
        let files = JSON.parse(data[0].acknowledge_document);
        if (files != null) {
            for (item of files) {
                fileList += `<div> <a href="${BASEURL+'/'+item.path}" target=_blanck>${item.title}</a></div>`;
            }
            $('#documents-files').html(fileList);
            $('#docModal').modal('show');
        } else {
            $('#documents-files').html(fileList);
        }
    }

    $(function() {
        // Function to redirect on new itr page
        $('body').on('click', '.open-itr', function() {
            let userid = $(this).attr('id');
            window.location.href = BASEURL + 'Users/itr_page/' + btoa(userid);
            localStorage.removeItem('trid');
        });
        // Upload itr document 
        $(`body`).on('change', '#upload-doc', function() {
            let userid = $('#userid').val();
            let title = $(this).attr('doc-title');
            let files = $(this).prop('files');
            let fy = $('#input-select-fy').children(':selected').val();
            let form_data = new FormData();
            for (var x = 0; x < files.length; x++) {
                let f = files[x];
                form_data.append("files[]", f);
            }
            form_data.append("userid", userid);
            form_data.append("title", title);
            form_data.append("fy", fy);
            // AJAX request
            if (form_data) {
                $.ajax({
                    url: BASEURL + 'api/Common/upload_itrdoc',
                    type: 'post',
                    data: form_data,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        localStorage.setItem("trid", res.data);
                        $('#ack').attr('data-trid', res.data);
                        $('#show-ack-files').attr('data-trid', res.data);
                        
                        loadCustomersITRHistory();
                    },
                    error(jqxhr, err) {
                        alert(jqxhr.responseJSON.message);
                        return false;
                    }
                });
            } else {
                return false;
            }
        });
        // Function to show file when document uploaded
        $('body').on('click', '#show-doc', function() {
            let trid = localStorage.getItem("trid");
            let url = BASEURL + 'api/Common/getitrdoc';
            $.post(url, {
                trid
            }).done(function(res) {
                showDocs(res.data);
            }).fail(function(jqxhr, err) {
                console.log()
                alert(jqxhr.responseJSON.message);

            })
        });
        // Function to show transaction histrory
        $('body').on('click', '.itrtrid', function() {
            let trid = $(this).attr('id');
            let url = BASEURL + 'api/Common/getitrdoc';
            $.post(url, {
                trid
            }).done(function(res) {
                let hs = res.data;
                let history = `<div class="card-body"><div class="form-group">
                <input type="hidden" name="userid" id="userid" value="${hs[0].customer_id}">
                <label for="input-select-fy">Financial Year</label>
                <select class="form-control" id="input-select-fy">
                    <option value="${hs[0].fy}" selected>Financial Year ${hs[0].fy}</option>
                </select>
            </div>
            <div class="form-group">
                <label for="upload-doc">Upload Documents</label>
                <input type="file" class="form-control" name="file[]" id="upload-doc" doc-title="documents" multiple>
            </div>
            <div class="form-group">
                <button class="btn btn-info show-doc-files" data-trid="${hs[0].transactionid}"  id="show-doc-files">Show documents</button>
            </div>
            <div class="form-group">
                <label for="ack">ITR Copy/Acknowledge documents</label>
                <input type="file" name="file[]" class="form-control upload-ack" id="ack" doc-title="acknowledge" data-trid="${hs[0].transactionid}" required>
            </div>
            <div class="form-group">
                <button class="btn btn-info" data-trid="${hs[0].transactionid}"  id="show-ack-files">Show Acknowledge </button>
            </div>
            <div class="form-group">
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="payment-status" ${(hs[0].payment=='1')?'Checked="true"':''} data-trid="${hs[0].transactionid}" ><span class="custom-control-label">Check if payment done</span>
                </label>
            </div>
            </div>`;
                $('#dochistoryfile').html(history);
                $('#showDocHistrory').modal('show');
            }).fail(function(jqxhr, err) {
                alert(jqxhr.responseJSON.message);
            });
        });
        // Function to show transaction histrory document
        $('body').on('click', '.show-doc-files', function() {
            let trid = $(this).attr('data-trid');
            let url = BASEURL + 'api/Common/getitrdoc';
            $.post(url, {
                trid
            }).done(function(res) {
                showDocs(res.data);
            }).fail(function(jqxhr, err) {
                alert(jqxhr.responseJSON.message);

            })
        });
        // Upload itr document 
        $(`body`).on('change', '.upload-ack', function() {
            let userid = $('#userid').val();
            let id = $(this).attr('id');
            let title = $(this).attr('doc-title');
            let files = $(this).prop('files');
            let fy = $('#input-select-fy').children(':selected').val();
            let form_data = new FormData();
            for (var x = 0; x < files.length; x++) {
                let f = files[x];
                form_data.append("files[]", f);
            }
       
            form_data.append("userid", userid);
            form_data.append("title", title);
            form_data.append("fy", fy);
            // AJAX request
            if (form_data) {
                $.ajax({
                    url: BASEURL + 'api/Common/upload_itrack',
                    type: 'post',
                    data: form_data,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        $(`#show-ack-files`).attr('data-trid', res.data);
                        loadCustomersITRHistory();
                    },
                    error(jqxhr, err) {
                        alert(jqxhr.responseJSON.message);
                        return false;
                    }
                });
            } else {
                return false;
            }
        });
        $('body').on('click', '#show-ack-files', function() {
            let trid = $(this).attr('data-trid');
            let url = BASEURL + 'api/Common/getitrdoc';
            $.post(url, {
                trid
            }).done(function(res) {
                showACKDocs(res.data);
            }).fail(function(jqxhr, err) {
                alert(jqxhr.responseJSON.message);

            })
        });

        $('body').on('click', '#payment-status', function() {
            let trid = $('#show-ack-files').attr('data-trid');
            if (confirm("Are you sure change the payment status!")) {
                let url = BASEURL + 'api/Common/update_payment';
                let status = 0;
                if ($(this).prop('checked')) {
                    status = 1;
                }
                $.post(url, {
                    trid,
                    status
                }).done(function(res) {
                    $('#payment-status').attr('checked','true');
                    loadCustomersITRHistory();
                }).fail(function(jqxhr, err) {
                    alert(jqxhr.responseJSON.message);
                });
            } else {
                return false;
            }
        });

        loadCustomersITRHistory();
        loadCustomers();
    })
</script>