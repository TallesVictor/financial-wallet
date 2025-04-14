@extends('layouts.app')

@section('title', 'Depósito')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
            <div class="card-body">
                <h4 class="card-title mb-4 text-center">Novo Depósito</h4>

                {{-- Loading Spinner --}}
                @include('layouts.spinner')
                
                <div id="divDeposit" class="d-none">

                    <div class="mb-3">
                        <div class="input-group">
                            <label class="form-label align-self-center">Seu saldo atual:</label> &nbsp;
                            <span class="fw-bolder" id="myBalance"></span>
                        </div>
                    </div>

                    <form id="depositForm">
                        @csrf

                        <div class="mb-3">
                            <label for="amount" class="form-label">Valor</label>
                            <input type="text" class="form-control money" id="amount" name="amount" required
                                placeholder="0,00">
                        </div>

                        <button type="submit" class="btn btn-primary w-100" id="depositButton">Depositar</button>
                    </form>
                    <div id="responseMessage" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/transactions/deposit/deposit.js') }}?v={{ time() }}"></script>
@endpush
