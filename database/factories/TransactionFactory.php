<?php

namespace Database\Factories;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sender_id' => function () {
                return User::factory()->create()->id;
            },
            'recipient_id' => function () {
                return User::factory()->create()->id;
            },
            'transaction_id' => function () {
                return (string) \Illuminate\Support\Str::orderedUuid();
            },
            'type' => TransactionType::TRANSFER,
            'amount' => fake()->numberBetween(10, 1000),
            'description' => fake()->sentence(3),
            'status' => TransactionStatus::SUCCESS->value,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
