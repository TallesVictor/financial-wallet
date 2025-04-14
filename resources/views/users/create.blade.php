@extends('layouts.app')

@section('title', 'Criar Usuário')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card shadow p-4" style="width: 100%; max-width: 400px;">

            <a href="{{ route('login') }}" class="btn btn-link text-start">Voltar</a>
            <div class="container mt-3">
                <h3 class="text-center mb-4">Criar Novo Usuário</h3>
                
                {{-- Loading Spinner --}}
                @include('layouts.spinner')

                <div id="divCreateUser" class="d-none">
                    <form id="createUserForm">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="document" class="form-label">Documento</label>
                            <input type="text" class="form-control" id="document" name="document" maxlength="11"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Criar Usuário</button>
                    </form>

                    <div id="responseMessage" class="mt-3"></div>
                </div>
            </div>

        </div>
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('js/user/create.js') }}?v={{ time() }}"></script>
@endpush
