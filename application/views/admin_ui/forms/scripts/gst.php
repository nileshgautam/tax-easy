<script>
    let BASEURL = "<?php echo base_url() ?>";
    let CustomerID = "<?php echo $userid ?>";

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
    const loadGSTmonth = () => {
        fy = $('#input-select-fy').children(':selected').val();
        let gst_m = get_month(fy);
        let month_widget = '';

        gst_m.forEach((month, i) => {
            let data = '';
            let showFile = `<a href="#" month="${month}" customer="${CustomerID}" fy=${fy} data=${JSON.stringify(data)} type="button">Files</a>`;
            month_widget += `<div class="col-sm-6">
                        <div class="card">
                            <div class="card-header" id="headingSeven${i}">
                                <h5 class="mb-0">
                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSeven${i}" aria-expanded="false" aria-controls="collapseSeven">
                                        <span class="fas fa-angle-down mr-3">${capitalize(month)}</span>
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseSeven${i}" class="collapse" aria-labelledby="headingSeven${i}" data-parent="#accordion3" style="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12 row">
                                            <div class="col-sm-4">
                                                <label for="myfile">Purchage Documents</label>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="upload-btn-wrapper">
                                                    <input csid="${CustomerID}" doc_title="Purchage Documents" month="${month}" fy="${fy}"  type="file" name="files[]" class="upload_pd" id="upload-pd${i}" multiple />
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                            ${showFile}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 row">
                                            <div class="col-sm-4">
                                                <label for="myfile">Sales Documents</label>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="upload-btn-wrapper">
                                                
                                                    <input type="file" name="file[]" multiple csid="${CustomerID}" doc_title="Sales Documents" month="${month}" id="sd${i}"  fy="${fy}" class="upload-sd" />
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                            ${showFile}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 row">
                                            <div class="col-sm-4">
                                                <label for="myfile">Return Calculation</label>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="upload-btn-wrapper">
                                                    <input type="file" name="file[]" multiple csid="${CustomerID}" doc_title="Return Calculation" month="${month}" id="rc${i}"  fy="${fy}" class="upload-rc" />
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                            ${showFile}
                                            </div>
                                        </div>
                                    </div>
                                  
                                    <div class="row">
                                        <div class="col-sm-12 row">
                                            <div class="col-sm-4">
                                                <label for="myfile">Acknowledgement Slip</label>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="upload-btn-wrapper">
                                                    <input type="file" name="file[]" multiple csid="${CustomerID}" doc_title="Acknowledgement Slip" month="${month}"  id="rc${i}"  fy="${fy}" class="upload-ack" />
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                            ${showFile}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 row">
                                            <div class="col-sm-10">
                                                <label for="payment">Payment Received <input type="checkbox" csid="${CustomerID}" doc_title="Payment" month="${month}"  id="payment${i}"  fy="${fy}" name="payment"></label>
                                            </div>
                                          
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>`;
        });

        $('#gst-calender').html(month_widget);


    }

    $('#input-select-fy').on('change', function() {
        loadGSTmonth();
    });


    $(function() {
        loadGSTmonth();

        $('#input-select-fy').append(`<option value="${getCurrentFiscalYear()}" selected> Financial Year ${getCurrentFiscalYear()}</option>`);


        $(`body`).on('change', '.upload_pd', function() {
            let id = $(this).attr('id');
            let files = $(this).prop('files');
            let form_data = new FormData();
            for (var x = 0; x < files.length; x++) {
                let f = files[x];
                form_data.append("files[]", f);
            }

            form_data.append("csid", $(this).attr('csid'));
            form_data.append("month", $(this).attr('month'));
            form_data.append("doc_title", $(this).attr('doc_title'));
            form_data.append("fy", $(this).attr('fy'));


            // AJAX request
            if (form_data) {
                $.ajax({
                    url: BASEURL + 'api/Common/uploadpo_files',
                    type: 'post',
                    data: form_data,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log('file uploaded');
                    }
                });
            } else {
                return false;
            }
        });
    });
</script>