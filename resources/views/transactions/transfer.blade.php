@extends('layouts.app')

@section('title', 'Transferência')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
            <div class="card-body">
                <h4 class="card-title mb-4 text-center">Nova Transferência</h4>

                {{-- Loading Spinner --}}
                <div id="loadingSpinner" class="d-flex justify-content-center align-items-center mb-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                </div>
                <div id="divTransfer" class="d-none">

                    <div class="mb-3">
                        <label class="form-label">Seu saldo atual:</label>
                        <div class="input-group">
                            <span class="input-group-text" id="myBalance"></span>
                        </div>
                    </div>

                    <form id="transferForm">
                        @csrf
                        <div class="mb-3">
                            <label for="receipientUser" class="form-label">Transferir para</label>
                            <select class="form-select" id="receipientUser" name="recipient_id" required>
                                <option value="" selected disabled>Selecione um usuário</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Valor</label>
                            <input type="text" class="form-control money" id="amount" name="amount" required
                                placeholder="0,00">
                        </div>

                        <button type="submit" class="btn btn-primary w-100" id="transferButton">Transferir</button>
                    </form>
                    <div id="responseMessage" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/transactions/transfer/list.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/transactions/transfer/transfer.js') }}?v={{ time() }}"></script>
@endpush
