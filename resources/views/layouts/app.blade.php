<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Painel')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 5 CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    @stack('styles')
</head>

<body>
    {{-- mostrar se tiver logado apenas --}}

    
    @auth
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Financial Wallet</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Alternar navegação">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        {{-- <a class="nav-link" href="{{ route('users.index') }}">Controle de Usuários</a> --}}
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('transactions.transfer') }}">Transferência</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('transactions.deposit') }}">Depósito</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('transactions.list') }}">Minhas Transações</a>
                    </li>
                    <li class="nav-item"></li>
                    <a class="nav-link" onclick="logout()">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @endauth


    <div class="container mt-4">
        @yield('content')
    </div>

    <!-- jQuery + Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <!-- JQuery Mask -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"
        integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <!-- Custom JS -->
    <script src="{{ asset('js/constants.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/utils.js') }}?v={{ time() }}"></script>

    @stack('scripts')
</body>

</html>
