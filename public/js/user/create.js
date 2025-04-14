
hideSpinner('divCreateUser');

$('#createUserForm').submit(function (e) {
    e.preventDefault(); // Prevent traditional form submission
    showSpinner('divCreateUser');

    // Capture form data
    let formData = $(this).serialize();

    // Display loading message
    $('#responseMessage').html(`
            <div class="text-center">
                <div class="spinner-border text-primary" role="status"></div>
                <p>Sending data...</p>
            </div>
        `);

    // Ajax request
    $.ajax({
        url: '/api/user',  // Route to create the user
        method: 'POST',
        data: formData,
        success: function (response) {
            $('#responseMessage').html(`
                        <div class="alert alert-success">${response.message}</div>
                    `);
            $('#createUserForm')[0].reset();

            setTimeout(function () {
                window.location.href = '/login';
                hideSpinner('divCreateUser');
            }, 1500);
        },
        error: function (response) {
            let errorMessage = response.responseJSON.message;

            $('#responseMessage').html(`
                    <div class="alert alert-danger">${errorMessage}</div>
                `);
            hideSpinner('divCreateUser');
        }
    });
});


