
hideSpinner('divLogin');
$('#login-form').on('submit', function (e) {
    showSpinner('divLogin');

    e.preventDefault();

    let form = $(this);

    $.ajax({
        url: '/api/login',
        method: 'POST',
        data: form.serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        xhrFields: {
            withCredentials: true
        },
        success: function (response) {
            localStorage.setItem('token_financial_wallet', response.token);
            window.location.href = '/transactions/transfer';
            setTimeout(() => {
                hideSpinner('divLogin');
            }, 3000);
        },
        error: function (response) {
            $('#login-alert')
                .removeClass('d-none alert-success')
                .addClass('alert alert-danger')
                .text(response.responseJSON.message || 'Invalid credentials or internal error.');
            hideSpinner('divLogin');
        }
    });
})
