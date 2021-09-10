<script>
    $(function() {
        let BASEURL='<?php echo base_url()?>';
        $('#login-form').on('submit', function(e) {
            e.preventDefault();
            let url = BASEURL + 'api/Auth/verify';
            let formData = $(this).serialize();
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                complete: function(xhr, statusText) {
                    if(statusText==='success')
                    window.location.href = BASEURL + 'dashboard';
                },
                error: function(xhr, statusText, err) {
                    $('.login-box-error').text(xhr.responseJSON.message);
                    return false;
                }
            });
        });

    });
</script>