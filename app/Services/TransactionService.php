<?php

namespace App\Services;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class TransactionService
{
    public function transfer(User $sender, int $recipientId, float $amount,  string $description = ''): Transaction
    {
        $senderBalance = (float) $sender->balance;

        if ($senderBalance < $amount) {
            throw new UnprocessableEntityHttpException('Insufficient balance.');
        }

        $transaction = DB::transaction(function () use ($sender, $recipientId, $amount, $description) {
            $recipient = User::findOrFail($recipientId);

            $sender->decrement('balance', $amount);
            $recipient->increment('balance', $amount);

            return Transaction::create([
                'sender_id' => $sender->id,
                'recipient_id' => $recipient->id,
                'amount' => $amount,
                'description' => $description,
                'type' => TransactionType::TRANSFER,
            ]);
        });

        $transaction->status = TransactionStatus::SUCCESS;
        $transaction->save();

        return $transaction;
    }

    public function deposit(User $user, float $amount, string $description = ''): Transaction
    {
        $transaction = DB::transaction(function () use ($user, $amount, $description) {
            $user->increment('balance', $amount);

            return Transaction::create([
                'recipient_id' => $user->id,
                'sender_id' => $user->id,
                'amount' => $amount,
                'description' => $description,
                'type' => TransactionType::DEPOSIT,
            ]);
        });

        $transaction->status = TransactionStatus::SUCCESS;
        $transaction->save();

        return $transaction;
    }

    public function reverse(Transaction $transaction, string $description): Transaction
    {
        if (!$transaction->isReversible()) {
            throw new UnprocessableEntityHttpException('Transaction is not reversible.');
        }

        return DB::transaction(function () use ($transaction, $description) {
            $recipient = $transaction->recipient;

            switch ($transaction->type) {
                case TransactionType::DEPOSIT->value:
                    $this->reverseDeposit($transaction, $recipient);
                    break;
                case TransactionType::TRANSFER->value:
                    $this->reverseTransfer($transaction, $recipient);
                    break;
                default:
                    throw new UnprocessableEntityHttpException('Transaction type not supported.');
            }

            $transaction->status = TransactionStatus::REVERSED;
            $transaction->description = $description;
            $transaction->save();

            return $transaction;
        });
    }

    private function reverseTransfer(Transaction $transaction, $recipient)
    {
        $sender = $transaction->sender;

        $recipient->decrement('balance', $transaction->amount);
        $sender->increment('balance', $transaction->amount);
    }

    private function reverseDeposit(Transaction $transaction, $recipient)
    {
        $recipient->decrement('balance', $transaction->amount);
    }
}
