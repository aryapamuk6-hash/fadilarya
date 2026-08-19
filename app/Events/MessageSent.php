<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message->load('sender');
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel(
                'conversation.' .
                $this->message->conversation_id
            ),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,

            'conversation_id' =>
                $this->message->conversation_id,

            'message' =>
                $this->message->message,

            'sender_id' =>
                $this->message->sender_id,

            'sender' => [
                'id' =>
                    $this->message->sender->id,

                'name' =>
                    $this->message->sender->name,
            ],

            'created_at' =>
                $this->message->created_at,
        ];
    }
}