<script>
    $(function() {

        $('#forgot-password-form').on('submit', function(e) {
            e.preventDefault();

            resp = '';
            let url = BASEURL + 'api/Auth/send_password_reset';
            let formData = $(this).serialize();


            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(res) {
                    // console.log(res)
                    if (res.status != 1) {
                        resp = res.message;
                        $('#response').removeClass('text-danger').addClass('text-success');
                    }
                    $('#response').text(resp);
                },
                error: function(data) {
                    //get the status code
                    // console.log(data);
                    if (data.status == 400) {
                        $('#response').removeClass('text-success').addClass('text-danger');
                        $('#response').text(data.responseJSON.message);
                        //   console.log(data.responseJSON.message);
                    } else if (data.status == 404) {
                        $('#response').removeClass('text-success').addClass('text-danger');
                        $('#response').text(data.responseJSON.message);
                        //   console.log(data.responseJSON.message);
                    } else if (data.status == 500) {
                        $('#response').removeClass('text-success').addClass('text-danger');
                        $('#response').text(data.responseJSON.message);
                        //   console.log(data.responseJSON.message);
                    }
                },
            });

            


        });

    });
</script>