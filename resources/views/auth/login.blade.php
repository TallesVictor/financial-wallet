@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">Controle de Carteira</h3>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form id="login-form">
            @csrf
        
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
        
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
        
            <div class="mb-3 form-check">
                <input type="checkbox" id="remember" class="form-check-input">
                <label class="form-check-label" for="remember">Lembrar-me</label>
            </div>
        
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Entrar</button>
            </div>
        </form>
        
        <div id="login-alert" class="alert d-none mt-3"></div>
        
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/auth/login.js') }}?v={{ time() }}"></script>
@endpush