<?php

namespace App\Events;

use App\Models\Document\DocumentSignature;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentSignatureUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $signature;
    public $message;
    public $type;

    public function __construct(DocumentSignature $signature, $message, $type = 'info')
    {
        $this->signature = $signature;
        $this->message = $message;
        $this->type = $type;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('document.' . $this->signature->document_id);
    }

    public function broadcastWith()
    {
        return [
            'document_id' => $this->signature->document_id,
            'signature_id' => $this->signature->id,
            'message' => $this->message,
            'type' => $this->type,
            'status' => $this->signature->status,
            'updated_at' => $this->signature->updated_at->toDateTimeString()
        ];
    }
}