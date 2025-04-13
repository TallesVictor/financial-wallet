
$(document).ready(function () {
    fetchUsers(); // Carrega ao iniciar

    // // Busca dinâmica
    // $('#search').on('keyup', function () {
    //     const query = $(this).val();
    //     fetchUsers(query);
    // });

    // // Paginação AJAX
    // $(document).on('click', '.pagination a', function (e) {
    //     e.preventDefault();
    //     const url = new URL($(this).attr('href'));
    //     const page = url.searchParams.get('page');
    //     const query = $('#search').val();
    //     fetchUsers(query, page);
    // });
});

function fetchUsers(query = '', page = 1) {
    $.ajax({
        url: "api/users",
        type: 'GET',
        data: {
            search: query,
            page: page
        },
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token_financial_wallet')
        },
        success: function (response) {
            // $('#users-table').html(response);
        },
        error: function () {
            // $('#users-table').html('<div class="alert alert-danger">Erro ao carregar os usuários.</div>');
        }
    });
}
