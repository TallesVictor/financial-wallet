<?php

namespace App\Http\Resources;

use App\Helpers\Help;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $sender = $this->sender;
        $recipient = $this->recipient;
        return [
            'transaction_id' => $this->transaction_id,
            'transaction_date' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'amount' => (float) $this->amount,
            'description' => $this->description,
            'sender' => [
                'id' => $sender->id,
                'name' => $sender->name,
            ],
            'recipient' => [
                'id' => $recipient->id,
                'name' => $recipient->name,
            ],
        ];
    }
}
