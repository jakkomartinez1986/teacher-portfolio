<?php

namespace App\Events;

use App\Models\Document\Document;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $document;
    public $message;
    public $type;

    public function __construct(Document $document, $message, $type = 'info')
    {
        $this->document = $document;
        $this->message = $message;
        $this->type = $type;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('document.' . $this->document->id);
    }

    public function broadcastWith()
    {
        return [
            'document_id' => $this->document->id,
            'message' => $this->message,
            'type' => $this->type,
            'status' => $this->document->status,
            'updated_at' => $this->document->updated_at->toDateTimeString()
        ];
    }
}