<?php

namespace App\Http\Controllers;

use App\Actions\DepositAction;
use App\Actions\ReverseAction;
use App\Actions\TransferAction;
use App\Http\Requests\Transaction\TransactionDepositRequest;
use App\Http\Requests\Transaction\TransactionReverseRequest;
use App\Http\Requests\Transaction\TransactionTransferRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function transfer(TransactionTransferRequest $request, TransferAction $action)
    {
        $transaction = $action->execute($request->validated());
        return response()->json(['message' => 'Transfer successful', 'data' => new TransactionResource($transaction)], 201);
    }

    public function deposit(TransactionDepositRequest $request, DepositAction $action)
    {
        $transaction = $action->execute($request->validated());
        return response()->json(['message' => 'Deposit successful', 'data' => new TransactionResource($transaction)], 201);
    }

    public function reverse(Transaction $transaction, TransactionReverseRequest $request, ReverseAction $action)
    {
        $transaction = $action->execute($transaction, $request->validated());
        return response()->json(['message' => 'Reverse successful'], 200);
    }
}
