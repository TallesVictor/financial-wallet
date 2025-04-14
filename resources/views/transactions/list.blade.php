@extends('layouts.app')

@section('title', 'Transferência')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="card shadow-sm" style="width: 100%; max-width: 800px;">
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center">
                    <h4 class="card-title mb-4">Minhas Transações</h4>
                </div>
                <div class="d-flex justify-content-end align-items-center mb-4">
                    <h5 class="card-title" id="totalBalance"></h5>
                </div>

                <div style="max-height: 50rem; overflow-y: auto;">
                    <table class="table table-striped">
                        <thead style="position: sticky; top: 0; z-index: 1; background-color: white;">
                            <tr>
                                <th>ID</th>
                                <th>Remetente</th>
                                <th>Destinatário</th>
                                <th>Valor</th>
                                <th>Descrição</th>
                                <th>Status</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="transactionsTable" style="max-height: 43rem; overflow-y: auto;">
                            <tr>
                                <td colspan="8" class="text-center">Carregando transferências...</td>
                            </tr>
                        </tbody>
                    </table>

                    <div id="responseMessage" class="mt-3"></div>
                </div>
            </div>
        </div>

        {{-- Criar modal para colocar a descrição da reversão --}}
        <div class="modal fade" id="reverseModal" tabindex="-1" aria-labelledby="reverseModalLabel" aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reverseModalLabel">Reversão</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="reverseForm">
                        <div class="modal-body">
                            <input type="hidden" id="transactionId">
                            <div class="mb-3">
                                <label for="reverseDescription" class="form-label">Descrição da Reversão</label>
                                <input type="text" class="form-control" id="reverseDescription" name="description"
                                    required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary" id="reverseButton">Reverter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/transactions/show/list.js') }}?v={{ time() }}"></script>
@endpush
