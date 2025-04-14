<?php

namespace Tests\Unit\Services;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Tests\TestCase;
use TypeError;

class TransactionServiceTest extends TestCase
{
    use RefreshDatabase;
    protected $transactionService;

    public function setUp(): void
    {
        parent::setUp();
        $this->transactionService = new TransactionService();
    }

    public function testSuccessfulTransfer()
    {
        $sender = User::factory()->create(['balance' => 100]);
        $recipient = User::factory()->create();

        $amount = 50;

        $transaction = $this->transactionService->transfer($sender, $recipient->id, $amount);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals($sender->id, $transaction->sender_id);
        $this->assertEquals($recipient->id, $transaction->recipient_id);
        $this->assertEquals($amount, $transaction->amount);
        $this->assertEquals(TransactionStatus::SUCCESS->value, $transaction->status);
    }

    public function testInsufficientSenderBalance()
    {
        $sender = User::factory()->create(['balance' => 50]);
        $recipient = User::factory()->create();
        $amount = 100;

        $this->expectException(UnprocessableEntityHttpException::class);
        $this->expectExceptionMessage('Insufficient balance.');
        
        $this->transactionService->transfer($sender, $recipient->id, $amount);
    }

    public function testInvalidRecipientId()
    {
        $sender = User::factory()->create(['balance' => 100]);
        $amount = 50;

        $this->expectException(ModelNotFoundException::class);
        $this->transactionService->transfer($sender, 9999999, $amount);
    }

    public function testTransferWithoutDescription()
    {
        $sender = User::factory()->create(['balance' => 100]);
        $recipient = User::factory()->create();
        $amount = 50;

        $transaction = $this->transactionService->transfer($sender, $recipient->id, $amount);
        $this->assertNull($transaction->description);
    }

    public function testTransferWithInvalidAmount()
    {

        $sender = User::factory()->create(['balance' => 100]);
        $recipient = User::factory()->create();
        $amount = -50;

        $this->expectException(UnprocessableEntityHttpException::class);
        $this->expectExceptionMessage('Amount must be greater than 0.');
        $this->transactionService->transfer($sender, $recipient->id, $amount);
    }

    public function testTransferWithZeroAmount()
    {

        $sender = User::factory()->create(['balance' => 100]);
        $recipient = User::factory()->create();
        $amount = 0;

        $this->expectException(UnprocessableEntityHttpException::class);
        $this->expectExceptionMessage('Amount must be greater than 0.');
        $this->transactionService->transfer($sender, $recipient->id, $amount);
    }

    public function testSuccessfulDeposit()
    {
        $user = User::factory()->create();
        $amount = 100.0;

        $transaction =  $this->transactionService->deposit($user, $amount);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals($user->id, $transaction->recipient_id);
        $this->assertEquals($user->id, $transaction->sender_id);
        $this->assertEquals($amount, $transaction->amount);

        $this->assertEquals(TransactionType::DEPOSIT->value, $transaction->type);
        $this->assertEquals(TransactionStatus::SUCCESS->value, $transaction->status);
    }

    public function testDepositWithInvalidUser()
    {
        $amount = 100.0;

        $this->expectException(TypeError::class);
        $this->transactionService->deposit(null, $amount);
    }

    public function testDepositWithInvalidAmount()
    {
        $user = User::factory()->create();
        $amount = -100.0;

        $this->expectException(UnprocessableEntityHttpException::class);
        $this->expectExceptionMessage('Amount must be greater than 0.');

        $this->transactionService->deposit($user, $amount);
    }

    public function testDepositWithZeroAmount()
    {
        $user = User::factory()->create();
        $amount = 0.0;

        $this->expectException(UnprocessableEntityHttpException::class);
        $this->expectExceptionMessage('Amount must be greater than 0.');
        $this->transactionService->deposit($user, $amount);
    }


    public function testTransferReversal()
    {
        $sender = User::factory()->create(['balance' => 100]);
        $recipient = User::factory()->create();

        $amount = 50;

        $transaction = $this->transactionService->transfer($sender, $recipient->id, $amount);

        $description = 'Test description';
        $this->transactionService->reverse($transaction, $description);

        $this->assertEquals(TransactionStatus::REVERSED->value, $transaction->status);
        $this->assertEquals($description, $transaction->description);

        $sender->refresh();
        $recipient->refresh();

        $this->assertEquals(100, $sender->balance);
        $this->assertEquals(0, $recipient->balance);
    }

    public function testDepositReversal()
    {
        $user = User::factory()->create();
        $amount = 100.0;

        $transaction =  $this->transactionService->deposit($user, $amount);

        $description = 'Test description';
        $this->transactionService->reverse($transaction, $description);

        $this->assertEquals(TransactionStatus::REVERSED->value, $transaction->status);
        $this->assertEquals($description, $transaction->description);

        $user->refresh();

        $this->assertEquals(0, $user->balance);
    }

    public function testInvalidTransaction()
    {
        $transaction = Transaction::factory()->create(['status' => TransactionStatus::REVERSED->value]);
        $description = 'Test description';

        $this->expectException(UnprocessableEntityHttpException::class);
        $this->expectExceptionMessage('Transaction is not reversible.');
        $this->transactionService->reverse($transaction, $description);
    }

}
