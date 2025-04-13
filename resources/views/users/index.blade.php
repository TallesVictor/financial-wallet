@extends('layouts.app')

@section('title', 'Usuários')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Usuários</h2>
        <input type="text" id="search" class="form-control w-25" placeholder="Buscar usuário...">
    </div>

    <div id="users-table">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status"></div>
            <p>Carregando usuários...</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/user/list.js') }}?v={{ time() }}"></script>
@endpush
