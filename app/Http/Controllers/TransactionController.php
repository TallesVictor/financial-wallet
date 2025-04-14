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
    /**
     * Create a new transfer transaction.
     *
     * @param  \App\Http\Requests\Transaction\TransactionTransferRequest  $request
     * @param  \App\Actions\TransferAction  $action
     * @return \Illuminate\Http\Response
     */
    public function transfer(TransactionTransferRequest $request, TransferAction $action)
    {
        $transaction = $action->execute($request->validated());
        return response()->json(['message' => 'Transfer successful', 'data' => new TransactionResource($transaction)], 201);
    }


    /**
     * Create a new deposit transaction.
     *
     * @param  \App\Http\Requests\Transaction\TransactionDepositRequest  $request
     * @param  \App\Actions\DepositAction  $action
     * @return \Illuminate\Http\Response
     */
    public function deposit(TransactionDepositRequest $request, DepositAction $action)
    {
        $transaction = $action->execute($request->validated());
        return response()->json(['message' => 'Deposit successful', 'data' => new TransactionResource($transaction)], 201);
    }

    /**
     * Reverse a transaction.
     *
     * @param  \App\Models\Transaction  $transaction
     * @param  \App\Http\Requests\Transaction\TransactionReverseRequest  $request
     * @param  \App\Actions\ReverseAction  $action
     * @return \Illuminate\Http\Response
     */
    public function reverse(Transaction $transaction, TransactionReverseRequest $request, ReverseAction $action)
    {
        $transaction = $action->execute($transaction, $request->validated());
        return response()->json(['message' => 'Reverse successful'], 200);
    }

    /**
     * Return a list of the transactions associated with the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function list() {
        $transactions =auth()->user()->transactions;
        return response()->json(TransactionResource::collection($transactions), 200);
    }
}
