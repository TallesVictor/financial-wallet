<?php

namespace Tests\Feature;

use App\Enums\TransactionStatus;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\User;
use App\Repositories\TransactionRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testTransferSuccessful()
    {
        $send = User::factory()->create(['balance' => 200000]);
        $recipient = User::factory()->create();

        $payload = [
            'amount' => 400.00,
            'recipient_id' => $recipient->id,
        ];

        $response = $this->actingAs($send)->postJson('/api/transaction/transfer', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['message' => 'Transfer successful'])
            ->assertJsonStructure([
                'message',
                'data' => ['transaction_id', 'amount', 'status', 'sender', 'recipient', 'transaction_date']
            ]);

        $transactionId = $response->json()['data']['transaction_id'];
        $transaction = app(TransactionRepository::class)->findByTransactionId($transactionId);

        $this->assertEquals($send->id, $transaction->sender_id);
        $this->assertEquals($recipient->id, $transaction->recipient_id);
        $this->assertEquals($payload['amount'], $transaction->amount);
        $this->assertEquals(TransactionStatus::SUCCESS->value, $transaction->status);
    }

    public function testTransferFailsWithInsufficientBalance()
    {
        $payer = User::factory()->create(['balance' => 1000]);
        $recipient = User::factory()->create();

        $payload = [
            'amount' => 2000,
            'recipient_id' => $recipient->id,
        ];

        $response = $this->actingAs($payer)->postJson('/api/transaction/transfer', $payload);

        $response->assertStatus(422);
        $response->assertJson(['message' => 'Insufficient balance.']);
    }

    public function testTransferFailsWithInvalidRecipient()
    {
        $send = User::factory()->create(['balance' => 5000]);

        $payload = [
            'amount' => 100,
            'recipient_id' => 9999999,
        ];

        $response = $this->actingAs($send)->postJson('/api/transaction/transfer', $payload);

        $response->assertStatus(422);
        $response->assertJson(['message' => 'The selected recipient id is invalid.']);
    }

    public function testTransferFailsWhenSendingToSelf()
    {
        $user = User::factory()->create(['balance' => 5000]);

        $payload = [
            'amount' => 100,
            'recipient_id' => $user->id,
        ];

        $response = $this->actingAs($user)->postJson('/api/transaction/transfer', $payload);

        $response->assertStatus(422);
        $response->assertJson(['message' => 'You cannot transfer to yourself.']);
    }

    public function testTransferFailsWithAmountLessThanZero()
    {
        $user = User::factory()->create(['balance' => 1000]);
        $recipient = User::factory()->create();

        $payload = [
            'amount' => -100,
            'recipient_id' => $recipient->id,
        ];

        $response = $this->actingAs($user)->postJson('/api/transaction/transfer', $payload);

        $response->assertStatus(422);
        $response->assertJson(['message' => 'The amount field must be at least 0.01.']);
    }

    public function testTransferFailsWithInvalidPayload()
    {
        $user = User::factory()->create(['balance' => 1000]);

        $payload = [
            'amount' => 100,
        ];

        $response = $this->actingAs($user)->postJson('/api/transaction/transfer', $payload);

        $response->assertStatus(422);
    }

    public function testTransferFailsNotAuthenticated()
    {
        $response = $this->postJson('/api/transaction/transfer', []);

        $response->assertStatus(500);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }

    public function testDepositSuccessful()
    {
        $send = User::factory()->create();
        $payload = [
            'amount' => 400.00,
        ];

        $response = $this->actingAs($send)->postJson('/api/transaction/deposit', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['message' => 'Deposit successful'])
            ->assertJsonStructure([
                'message',
                'data' => ['transaction_id', 'amount', 'status', 'sender', 'recipient', 'transaction_date']
            ]);

        $transactionId = $response->json()['data']['transaction_id'];
        $transaction = app(TransactionRepository::class)->findByTransactionId($transactionId);

        $this->assertEquals($send->id, $transaction->sender_id);
        $this->assertEquals($send->id, $transaction->recipient_id);
        $this->assertEquals($payload['amount'], $transaction->amount);
        $this->assertEquals(TransactionStatus::SUCCESS->value, $transaction->status);

        $send->refresh();
        $this->assertEquals($payload['amount'], $send->balance);
    }

    public function testDepositFailsWithAmountLessThanZero()
    {
        $user = User::factory()->create();

        $payload = [
            'amount' => -100,
        ];

        $response = $this->actingAs($user)->postJson('/api/transaction/deposit', $payload);

        $response->assertStatus(422);
        $response->assertJson(['message' => 'The amount field must be at least 0.01.']);
    }

    public function testDepositFailsWithInvalidPayload()
    {
        $user = User::factory()->create(['balance' => 1000]);

        $payload = [];

        $response = $this->actingAs($user)->postJson('/api/transaction/deposit', $payload);

        $response->assertStatus(422);
    }

    public function testDepositFailsNotAuthenticated()
    {
        $response = $this->postJson('/api/transaction/deposit', []);

        $response->assertStatus(500);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }

    public function testTransactionReverseSuccessful()
    {
        $user = User::factory()->create();
        $transaction = Transaction::factory()->create([
            'sender_id' => $user->id,
        ]);

        $payload = [
            'description' => 'Erro do cliente',
        ];

        $response = $this->actingAs($user)->postJson("/api/transaction/{$transaction->transaction_id}/reverse", $payload);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Reverse successful']);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => TransactionStatus::REVERSED->value,
        ]);
    }

    public function testTransactionReverseFailsWithInvalidPayload()
    {
        $user = User::factory()->create();
        $transaction = Transaction::factory()->create();

        $response = $this->actingAs($user)->postJson("/api/transaction/{$transaction->transaction_id}/reverse", []);

        $response->assertStatus(422);
    }

    public function testTransactionReverseFailsWhenAlreadyReversed()
    {
        $user = User::factory()->create();
        $transaction = Transaction::factory()->create([
            'sender_id' => $user->id,
            'status' => TransactionStatus::REVERSED->value,
        ]);

        $payload = ['description' => 'Transaction already reversed.'];

        $response = $this->actingAs($user)->postJson("/api/transaction/{$transaction->transaction_id}/reverse", $payload);

        $response->assertStatus(422);
        $response->assertJson(['message' => 'Transaction is not reversible.']);
    }

    public function testTransactionReverseFailsWhenUnauthenticated()
    {
        $transaction = Transaction::factory()->create();

        $payload = ['description' => 'Without authentication.'];

        $response = $this->postJson("/api/transaction/{$transaction->transaction_id}/reverse", $payload);

        $response->assertStatus(500);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }

    public function testTransactionListReturnsOnlyUserTransactions()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Transaction::factory()->count(2)->create([
            'sender_id' => $user->id
        ]);

        Transaction::factory()->count(3)->create([
            'sender_id' => $otherUser->id,
            'recipient_id' => $user->id
        ]);

        Transaction::factory()->count(3)->create();

        $response = $this->actingAs($user)->getJson('/api/transaction/list');

        $response->assertStatus(200)
            ->assertJsonCount(5);

        $responseData = $response->json();

        foreach ($responseData as $transaction) {
            $this->assertTrue($user->id === $transaction['sender']['id'] || $user->id === $transaction['recipient']['id']);
        }
    }

    public function testTransactionListFailsWhenNotAuthenticated()
    {
        $response = $this->getJson('/api/transaction/list');

        $response->assertStatus(500);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }

    public function testTransactionListResponseStructure()
    {
        $user = User::factory()->create();

        Transaction::factory()->create([
            'sender_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->getJson('/api/transaction/list');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'transaction_id',
                    'transaction_date',
                    'amount',
                    'description',
                    'status',
                    'sender' => ['id', 'name'],
                    'recipient' => ['id', 'name'],
                ]
            ]);
    }
}
