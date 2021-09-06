<script>
  $(function() {

    $('#recover-password-form').on('submit', function(e) {
      e.preventDefault();
      let resp = '';
      let password = $('#password').val(),
        cnfp = $('#confirm-password').val();
      if (password == cnfp) {
        let url = BASEURL + 'api/Auth/reset_password';
        let formData = $(this).serialize();
        $.ajax({
          url: url,
          type: 'POST',
          data: formData,
          success: function(res) {
            // console.log(res)
            if (res.status != 1) {
              resp = res.message;
              $('#response').removeClass('text-success').addClass('text-danger');
            } else {
              resp = res.message;
              $('#response').removeClass('text-danger').addClass('text-success');
              location.href = BASEURL;
            }
            $('#response').text(resp);
          },
          error: function(data) {
            //get the status code
            // console.log(data);
            if (data.status == 400) {
              $('#response').removeClass('text-success').addClass('text-danger');
              $('#response').text(data.responseJSON.message);
              console.log(data.responseJSON.message);
            } else if (data.status == 404) {
              $('#response').removeClass('text-success').addClass('text-danger');
              $('#response').text(data.responseJSON.message);
              console.log(data.responseJSON.message);
            } else if (data.status == 500) {
              $('#response').removeClass('text-success').addClass('text-danger');
              $('#response').text(data.responseJSON.message);
              console.log(data.responseJSON.message);
            }
          },
        });
      } else {
        res = 'Password did not matched. try again';
        $('#response').removeClass('text-success').addClass('text-danger');
        $('#response').text(res);
      }

    });

  });
</script>