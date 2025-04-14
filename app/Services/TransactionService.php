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
    /**
     * Perform a transfer between two users.
     *
     * @param User $sender The user that is sending the money.
     * @param int $recipientId The id of the user that is receiving the money.
     * @param float $amount The amount of money being transferred.
     *
     * @throws UnprocessableEntityHttpException If the amount is not greater than 0.
     * @throws UnprocessableEntityHttpException If the sender does not have enough balance.
     *
     * @return Transaction The transaction that was created.
     */
    public function transfer(User $sender, int $recipientId, float $amount): Transaction
    {
        if ($amount <= 0) throw new UnprocessableEntityHttpException('Amount must be greater than 0.');
        
        $senderBalance = (float) $sender->balance;

        if ($senderBalance < $amount) {
            throw new UnprocessableEntityHttpException('Insufficient balance.');
        }

        $transaction = DB::transaction(function () use ($sender, $recipientId, $amount) {
            $recipient = User::findOrFail($recipientId);

            $sender->decrement('balance', $amount);
            $recipient->increment('balance', $amount);

            return Transaction::create([
                'sender_id' => $sender->id,
                'recipient_id' => $recipient->id,
                'amount' => $amount,
                'type' => TransactionType::TRANSFER->value,
            ]);
        });

        $transaction->status = TransactionStatus::SUCCESS->value;
        $transaction->save();

        return $transaction;
    }

    /**
     * Deposit money into the user's account.
     *
     * @param User $user The user to deposit money into.
     * @param float $amount The amount of money to deposit.
     *
     * @throws UnprocessableEntityHttpException If the amount is not greater than 0.
     *
     * @return Transaction The transaction that was created.
     */
    public function deposit(User $user, float $amount): Transaction
    {
        $transaction = DB::transaction(function () use ($user, $amount) {

            if ($amount <= 0) throw new UnprocessableEntityHttpException('Amount must be greater than 0.');

            $user->increment('balance', $amount);

            return Transaction::create([
                'recipient_id' => $user->id,
                'sender_id' => $user->id,
                'amount' => $amount,
                'type' => TransactionType::DEPOSIT->value,
            ]);
        });

        $transaction->status = TransactionStatus::SUCCESS->value;
        $transaction->save();

        return $transaction;
    }

    /**
     * Reverse a transaction.
     *
     * @param Transaction $transaction The transaction to reverse.
     * @param string $description The description for the reversal.
     *
     * @throws UnprocessableEntityHttpException If the transaction is not reversible.
     *
     * @return Transaction The reversed transaction.
     */
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
            }

            $transaction->status = TransactionStatus::REVERSED->value;
            $transaction->description = $description;
            $transaction->save();

            return $transaction;
        });
    }

    /**
     * Reverses a transfer transaction by incrementing the sender's balance and
     * decrementing the recipient's balance.
     *
     * @param Transaction $transaction The transaction to reverse.
     * @param User $recipient The recipient of the transaction to reverse.
     *
     * @return void
     */
    private function reverseTransfer(Transaction $transaction, $recipient)
    {
        $sender = $transaction->sender;

        $recipient->decrement('balance', $transaction->amount);
        $sender->increment('balance', $transaction->amount);
    }

    /**
     * Reverses a deposit transaction by decrementing the recipient's balance.
     *
     * @param Transaction $transaction The transaction to reverse.
     * @param User $recipient The recipient of the transaction to reverse.
     *
     * @return void
     */
    private function reverseDeposit(Transaction $transaction, $recipient)
    {
        $recipient->decrement('balance', $transaction->amount);
    }
}
